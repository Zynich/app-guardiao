<?php

namespace App\Http\Controllers\Portal;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\StoreTicketRequest;
use App\Mail\TicketProtocolMail;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\TicketMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function store(StoreTicketRequest $request): JsonResponse
    {
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
                'status'          => TicketStatus::PENDENTE_TRIAGEM,
                'priority'        => $category->priority,
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
                        'file_path' => $path,
                        'file_type' => $photo->getMimeType(),
                    ]);
                    TicketLog::logMediaAdded($ticket->id, null, $photo->getClientOriginalName());
                }
            }

            TicketLog::logCreated($ticket->id);
        } catch (\Throwable $e) {
            Log::error('Erro ao criar ocorrência: ' . $e->getMessage());
            return response()->json(['message' => 'Erro ao salvar a ocorrência. Tente novamente.'], 500);
        }

        // Envia confirmação ao cidadão — falha silenciosa para não bloquear o protocolo
        try {
            $ticket->load('category');
            Mail::to($ticket->citizen_email)->queue(new TicketProtocolMail($ticket));
        } catch (\Throwable $e) {
            Log::warning('Falha ao enviar e-mail de protocolo: ' . $e->getMessage(), ['ticket' => $ticket->id]);
        }

        return response()->json(['protocol' => $protocol], 201);
    }

    public function track(Request $request): View
    {
        $protocol = strtoupper(trim($request->query('protocol', '')));
        $ticket   = null;

        if ($protocol) {
            $ticket = Ticket::with([
                'category',
                'logs' => fn ($q) => $q->where('is_public', true)->orderBy('created_at', 'asc'),
            ])->where('protocol', $protocol)->first();
        }

        return view('portal.track', compact('ticket', 'protocol'));
    }

    private function generateProtocol(): string
    {
        do {
            $protocol = date('Y') . '-' . strtoupper(Str::random(6));
        } while (Ticket::where('protocol', $protocol)->exists());

        return $protocol;
    }
}
