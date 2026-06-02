<x-admin-layout title="Ocorrências" subtitle="Gestão de chamados e incidentes urbanos">
    <x-slot:actions>
        @if(auth()->user()->hasRole(\App\Enums\UserRole::ADMIN, \App\Enums\UserRole::DESPACHANTE))
            <a href="{{ route('admin.tickets.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary to-primary-variant rounded-xl text-xs font-black text-white uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-primary/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nova Ocorrência
            </a>
        @endif
    </x-slot:actions>

    <!-- Filtros -->
    <x-admin.section-card class="mb-5">
        <form method="GET" action="{{ route('admin.tickets.index') }}" class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Buscar protocolo, endereço, cidadão..."
                   class="lg:col-span-2 bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm placeholder:text-zinc-600 focus:border-primary-variant focus:ring-primary-variant transition-all">

            <select name="status" class="bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all">
                <option value="">Todos os Status</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </select>

            <select name="priority" class="bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all">
                <option value="">Todas as Prioridades</option>
                @foreach(\App\Enums\Priority::cases() as $p)
                    <option value="{{ $p->value }}" {{ request('priority') === $p->value ? 'selected' : '' }}>
                        {{ $p->label() }}
                    </option>
                @endforeach
            </select>

            <div class="flex gap-2">
                <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-primary-variant/10 border border-primary-variant/30 text-primary-variant rounded-xl text-sm font-bold hover:bg-primary-variant/20 transition-all">
                    Filtrar
                </button>
                @if(request()->hasAny(['search','status','priority','category']))
                    <a href="{{ route('admin.tickets.index') }}"
                       class="px-4 py-2.5 border border-zinc-700 text-zinc-400 rounded-xl text-sm font-bold hover:bg-zinc-800 transition-all">
                        Limpar
                    </a>
                @endif
            </div>
        </form>
    </x-admin.section-card>

    <!-- Tabela -->
    <x-admin.section-card>
        @if($tickets->isEmpty())
            <x-admin.empty-state
                title="Nenhuma ocorrência encontrada"
                description="Tente ajustar os filtros ou aguarde novas denúncias do portal." />
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-zinc-800/70">
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest">Protocolo</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest hidden md:table-cell">Categoria</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest hidden lg:table-cell">Endereço</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest">Prioridade</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest">Status</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest hidden xl:table-cell">Agente</th>
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest hidden sm:table-cell">Abertura</th>
                            <th class="px-5 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50">
                        @foreach($tickets as $ticket)
                            <tr class="hover:bg-zinc-800/20 transition-colors group">
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}"
                                       class="font-black text-primary-variant hover:underline tracking-wider text-xs">
                                        {{ $ticket->protocol }}
                                    </a>
                                    @if($ticket->due_date && $ticket->due_date->isPast() && !in_array($ticket->status, [\App\Enums\TicketStatus::RESOLVIDO, \App\Enums\TicketStatus::REJEITADO, \App\Enums\TicketStatus::CANCELADO, \App\Enums\TicketStatus::DUPLICADO]))
                                        <span class="ml-1 text-[9px] font-black text-red-400 uppercase">SLA!</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-zinc-400 hidden md:table-cell">
                                    <span class="truncate max-w-[130px] block">{{ $ticket->category?->name ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-zinc-400 hidden lg:table-cell">
                                    <span class="truncate max-w-[180px] block">{{ Str::limit($ticket->address, 40) }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-admin.badge :classes="$ticket->priority->badgeClasses()">
                                        {{ $ticket->priority->label() }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-admin.badge :classes="$ticket->status->badgeClasses()">
                                        {{ $ticket->status->label() }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-5 py-3.5 text-zinc-500 text-xs hidden xl:table-cell">
                                    {{ $ticket->assignedTo?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3.5 text-zinc-500 text-xs hidden sm:table-cell">
                                    {{ $ticket->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-5 py-3.5">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}"
                                       class="text-zinc-500 hover:text-primary-variant transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            @if($tickets->hasPages())
                <div class="px-5 py-4 border-t border-zinc-800/70">
                    {{ $tickets->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @endif
    </x-admin.section-card>

</x-admin-layout>
