<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TicketStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCommentRequest;
use App\Http\Requests\Admin\StoreTicketRequest;
use App\Http\Requests\Admin\UpdateTicketStatusRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\TicketMedia;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function index(Request $request): View
    {
        $user  = $request->user();
        $query = Ticket::with(['category', 'assignedTo'])
            ->when($request->filled('status'),   fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->priority))
            ->when($request->filled('category'), fn ($q) => $q->where('category_id', $request->category))
            ->when($request->filled('search'),   fn ($q) => $q->where(function ($q) use ($request) {
                $term = "%{$request->search}%";
                $q->where('protocol', 'like', $term)
                  ->orWhere('address', 'like', $term)
                  ->orWhere('citizen_name', 'like', $term);
            }));

        if ($user->isAgente()) {
            $query->where('user_id', $user->id);
        }

        $tickets    = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $statuses   = TicketStatus::cases();

        return view('admin.tickets.index', compact('tickets', 'categories', 'statuses'));
    }

    public function create(): View
    {
        $this->authorizeRole(UserRole::ADMIN, UserRole::DESPACHANTE);

        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $agents     = User::where('is_active', true)->orderBy('name')->get();

        return view('admin.tickets.create', compact('categories', 'agents'));
    }

    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $this->authorizeRole(UserRole::ADMIN, UserRole::DESPACHANTE);

        $category = Category::findOrFail($request->category_id);
        $protocol = $this->generateProtocol();
        $dueDate  = $category->sla_hours ? now()->addHours($category->sla_hours) : null;

        try {
            $ticket = Ticket::create([
                'protocol'        => $protocol,
                'citizen_name'    => $request->citizen_name,
                'citizen_email'   => $request->citizen_email,
                'citizen_phone'   => $request->citizen_phone,
                'citizen_cpf'     => $request->citizen_cpf,
                'category_id'     => $category->id,
                'user_id'         => $request->user_id,
                'created_by_id'   => auth()->id(),
                'status'          => TicketStatus::PENDENTE_TRIAGEM,
                'priority'        => $request->priority ?? $category->priority,
                'description'     => $request->description,
                'address'         => $request->address,
                'reference_point' => $request->reference_point,
                'latitude'        => $request->latitude,
                'longitude'       => $request->longitude,
                'due_date'        => $dueDate,
            ]);

            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store("tickets/{$protocol}", 'public');
                    TicketMedia::create([
                        'ticket_id' => $ticket->id,
                        'user_id'   => auth()->id(),
                        'file_path' => $path,
                        'file_type' => $photo->getMimeType(),
                    ]);
                    TicketLog::logMediaAdded($ticket->id, auth()->id(), $photo->getClientOriginalName());
                }
            }

            TicketLog::logCreated($ticket->id, auth()->id());
        } catch (\Throwable $e) {
            Log::error('Erro ao criar ocorrência: ' . $e->getMessage());
            return back()->withInput()->withErrors(['general' => 'Erro ao salvar a ocorrência. Tente novamente.']);
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', "Ocorrência {$protocol} criada com sucesso.");
    }

    public function show(Ticket $ticket): View
    {
        $this->authorizeTicketAccess($ticket);

        $ticket->load([
            'category', 'assignedTo', 'creator', 'media',
            'logs' => fn ($q) => $q->with('user')->orderBy('created_at', 'asc'),
        ]);

        $agents = User::where('is_active', true)->orderBy('name')->get();

        $allowedTransitions = $this->getAllowedTransitions($ticket->status);

        return view('admin.tickets.show', compact('ticket', 'agents', 'allowedTransitions'));
    }

    public function updateStatus(UpdateTicketStatusRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorizeTicketAccess($ticket);

        $newStatus = TicketStatus::from($request->status);
        $allowed   = $this->getAllowedTransitions($ticket->status);

        if (! in_array($newStatus, $allowed, true)) {
            return back()->withErrors(['status' => 'Transição de status inválida.']);
        }

        $oldStatus = $ticket->status->value;
        $ticket->update(['status' => $newStatus]);
        TicketLog::logStatus($ticket->id, auth()->id(), $oldStatus, $newStatus->value);

        return back()->with('success', 'Status atualizado com sucesso.');
    }

    public function addComment(StoreCommentRequest $request, Ticket $ticket): RedirectResponse
    {
        $this->authorizeTicketAccess($ticket);

        $isPublic = $request->user()->hasRole(UserRole::ADMIN, UserRole::DESPACHANTE)
            ? $request->boolean('is_public')
            : false;

        TicketLog::logComment($ticket->id, auth()->id(), $request->comment, $isPublic);

        return back()->with('success', 'Comentário adicionado.');
    }

    public function assign(Request $request, Ticket $ticket): RedirectResponse
    {
        $this->authorizeRole(UserRole::ADMIN, UserRole::DESPACHANTE);

        $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
        ]);

        $ticket->update(['user_id' => $request->user_id]);

        return back()->with('success', $request->user_id ? 'Agente atribuído.' : 'Atribuição removida.');
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /** @return TicketStatus[] */
    private function getAllowedTransitions(TicketStatus $current): array
    {
        return match($current) {
            TicketStatus::PENDENTE_TRIAGEM => [
                TicketStatus::EM_ANDAMENTO,
                TicketStatus::REJEITADO,
                TicketStatus::DUPLICADO,
            ],
            TicketStatus::EM_ANDAMENTO => [
                TicketStatus::RESOLVIDO,
                TicketStatus::CANCELADO,
            ],
            default => [],
        };
    }

    private function generateProtocol(): string
    {
        do {
            $protocol = date('Y') . '-' . strtoupper(Str::random(6));
        } while (Ticket::where('protocol', $protocol)->exists());

        return $protocol;
    }

    private function authorizeRole(UserRole ...$roles): void
    {
        if (! auth()->user()->hasRole(...$roles)) {
            abort(403);
        }
    }

    private function authorizeTicketAccess(Ticket $ticket): void
    {
        $user = auth()->user();

        if ($user->isAgente() && $ticket->user_id !== $user->id) {
            abort(403);
        }
    }
}
