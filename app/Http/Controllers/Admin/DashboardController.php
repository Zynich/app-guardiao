<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statusCounts = collect(TicketStatus::cases())->mapWithKeys(
            fn ($s) => [$s->value => Ticket::where('status', $s)->count()]
        );

        $priorityCounts = collect(Priority::cases())->mapWithKeys(
            fn ($p) => [$p->value => Ticket::where('priority', $p)->count()]
        );

        $todayCount    = Ticket::whereDate('created_at', today())->count();
        $weekCount     = Ticket::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $overdueCount  = Ticket::whereNotNull('due_date')
                               ->where('due_date', '<', now())
                               ->whereNotIn('status', [
                                   TicketStatus::RESOLVIDO->value,
                                   TicketStatus::REJEITADO->value,
                                   TicketStatus::CANCELADO->value,
                                   TicketStatus::DUPLICADO->value,
                               ])->count();

        $topCategories = Category::withCount('tickets')
            ->having('tickets_count', '>', 0)
            ->orderByDesc('tickets_count')
            ->limit(5)
            ->get();

        $recentTickets = Ticket::with(['category', 'assignedTo'])
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('admin.dashboard', compact(
            'statusCounts', 'priorityCounts',
            'todayCount', 'weekCount', 'overdueCount',
            'topCategories', 'recentTickets'
        ));
    }
}
