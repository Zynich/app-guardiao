<x-admin-layout title="Dashboard" subtitle="Visão geral do sistema">

    <!-- KPIs de Status -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        @foreach(\App\Enums\TicketStatus::cases() as $status)
            @php $count = $statusCounts[$status->value] ?? 0; @endphp
            <a href="{{ route('admin.tickets.index', ['status' => $status->value]) }}"
               class="group bg-surface border border-zinc-800/70 rounded-2xl p-4 hover:border-zinc-700 transition-all">
                <p class="text-2xl font-black text-white group-hover:text-primary-variant transition-colors">{{ $count }}</p>
                <x-admin.badge :classes="$status->badgeClasses()" class="mt-2">{{ $status->label() }}</x-admin.badge>
            </a>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        <!-- Destaques do Dia -->
        <div class="grid grid-cols-1 gap-3">
            <x-admin.stat-card label="Abertas Hoje" :value="$todayCount" color="cyan">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </x-admin.stat-card>

            <x-admin.stat-card label="Abertas esta Semana" :value="$weekCount" color="blue">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </x-admin.stat-card>

            <x-admin.stat-card
                label="Com SLA Vencido"
                :value="$overdueCount"
                :color="$overdueCount > 0 ? 'red' : 'zinc'"
                :sub="$overdueCount > 0 ? 'Atenção imediata necessária' : 'Tudo dentro do prazo'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </x-admin.stat-card>
        </div>

        <!-- Distribuição por Prioridade -->
        <x-admin.section-card title="Por Prioridade" class="h-fit">
            <div class="p-4 space-y-3">
                @foreach(\App\Enums\Priority::cases() as $priority)
                    @php
                        $count = $priorityCounts[$priority->value] ?? 0;
                        $total = array_sum($priorityCounts->toArray());
                        $pct   = $total > 0 ? round(($count / $total) * 100) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <x-admin.badge :classes="$priority->badgeClasses()">{{ $priority->label() }}</x-admin.badge>
                            <span class="text-xs font-black text-zinc-400">{{ $count }}</span>
                        </div>
                        <div class="w-full bg-zinc-800 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full bg-primary-variant transition-all" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-admin.section-card>

        <!-- Top Categorias -->
        <x-admin.section-card title="Categorias Mais Acionadas">
            @if($topCategories->isEmpty())
                <x-admin.empty-state title="Sem dados" description="Nenhuma ocorrência registrada ainda." />
            @else
                <div class="divide-y divide-zinc-800/70">
                    @foreach($topCategories as $cat)
                        <div class="flex items-center justify-between px-5 py-3">
                            <span class="text-sm font-bold text-zinc-300 truncate">{{ $cat->name }}</span>
                            <span class="text-xs font-black text-primary-variant ml-2 shrink-0">{{ $cat->tickets_count }}</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-admin.section-card>

    </div>

    <!-- Ocorrências Recentes -->
    <x-admin.section-card title="Ocorrências Recentes">
        <x-slot:actions>
            <a href="{{ route('admin.tickets.index') }}"
               class="text-xs font-black text-primary-variant hover:underline uppercase tracking-widest">
                Ver todas
            </a>
        </x-slot:actions>

        @if($recentTickets->isEmpty())
            <x-admin.empty-state title="Sem ocorrências" description="Nenhuma ocorrência registrada ainda." />
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
                            <th class="px-5 py-3 text-left text-[10px] font-black text-zinc-500 uppercase tracking-widest hidden sm:table-cell">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/50">
                        @foreach($recentTickets as $ticket)
                            <tr class="hover:bg-zinc-800/20 transition-colors">
                                <td class="px-5 py-3">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}"
                                       class="font-black text-primary-variant hover:underline tracking-wider text-xs">
                                        {{ $ticket->protocol }}
                                    </a>
                                </td>
                                <td class="px-5 py-3 text-zinc-400 hidden md:table-cell truncate max-w-[140px]">
                                    {{ $ticket->category?->name ?? '—' }}
                                </td>
                                <td class="px-5 py-3 text-zinc-400 hidden lg:table-cell truncate max-w-[180px]">
                                    {{ Str::limit($ticket->address, 40) }}
                                </td>
                                <td class="px-5 py-3">
                                    <x-admin.badge :classes="$ticket->priority->badgeClasses()">
                                        {{ $ticket->priority->label() }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-5 py-3">
                                    <x-admin.badge :classes="$ticket->status->badgeClasses()">
                                        {{ $ticket->status->label() }}
                                    </x-admin.badge>
                                </td>
                                <td class="px-5 py-3 text-zinc-500 text-xs hidden sm:table-cell">
                                    {{ $ticket->created_at->format('d/m H:i') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-admin.section-card>

</x-admin-layout>
