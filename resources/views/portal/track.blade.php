<x-guest-layout>
    <main class="flex-1 w-full flex flex-col items-center py-12 px-6 bg-surface-darker">

        <div class="w-full max-w-2xl space-y-6">

            <!-- Voltar -->
            <a href="{{ route('portal.index') }}"
               class="inline-flex items-center gap-2 text-zinc-500 hover:text-zinc-200 text-sm font-bold uppercase tracking-widest transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Voltar ao Portal
            </a>

            <!-- Card de Busca -->
            <x-ui.card class="p-8 rounded-3xl border border-zinc-800/50">
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-primary-variant/10 p-2 rounded-lg">
                        <svg class="w-5 h-5 text-primary-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-white uppercase tracking-tighter">Consultar Protocolo</h3>
                </div>
                <form action="{{ route('portal.track') }}" method="GET"
                      class="grid grid-cols-1 sm:grid-cols-4 gap-4"
                      x-data="{ localLoading: false }" @submit="localLoading = true">
                    <div class="sm:col-span-3">
                        <x-ui.input type="text" name="protocol"
                                     placeholder="Ex: 2026-ABC123"
                                     class="w-full uppercase"
                                     value="{{ $protocol }}"
                                     required />
                    </div>
                    <div class="sm:col-span-1">
                        <x-ui.button type="submit" class="w-full py-3" ::disabled="localLoading">
                            <span x-show="!localLoading">Consultar</span>
                            <span x-show="localLoading" x-cloak class="flex items-center justify-center gap-2">
                                <x-ui.spinner class="w-4 h-4 text-white" />
                            </span>
                        </x-ui.button>
                    </div>
                </form>
            </x-ui.card>

            @if($protocol && !$ticket)
                <!-- Não encontrado -->
                <x-ui.card class="p-8 rounded-3xl border border-red-500/20 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="w-14 h-14 rounded-full bg-red-500/10 border border-red-500/30 flex items-center justify-center">
                            <svg class="w-7 h-7 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-lg font-black text-white mb-2">Protocolo não encontrado</h4>
                    <p class="text-zinc-400 text-sm font-medium">
                        O protocolo <span class="text-white font-black">{{ $protocol }}</span> não existe em nosso sistema.
                        Verifique se digitou corretamente.
                    </p>
                </x-ui.card>
            @endif

            @if($ticket)
                <!-- Resultado do Ticket -->
                <x-ui.card class="p-8 rounded-3xl border border-zinc-800/50">

                    <!-- Cabeçalho: protocolo + status -->
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8 pb-8 border-b border-zinc-800/50">
                        <div>
                            <p class="text-xs font-black text-zinc-500 uppercase tracking-widest mb-1">Protocolo</p>
                            <p class="text-2xl font-black text-primary-variant tracking-wider">{{ $ticket->protocol }}</p>
                            <p class="text-xs text-zinc-600 mt-1 font-medium">
                                Aberto em {{ $ticket->created_at->format('d/m/Y \à\s H:i') }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-black uppercase tracking-widest border
                                     {{ $ticket->status->badgeClasses() }}">
                            {{ $ticket->status->label() }}
                        </span>
                    </div>

                    <!-- Detalhes -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-xs font-black text-zinc-500 uppercase tracking-widest mb-1">Categoria</p>
                            <p class="text-white font-bold">{{ $ticket->category?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-black text-zinc-500 uppercase tracking-widest mb-1">Endereço</p>
                            <p class="text-white font-bold">{{ $ticket->address }}</p>
                            @if($ticket->reference_point)
                                <p class="text-zinc-500 text-sm">{{ $ticket->reference_point }}</p>
                            @endif
                        </div>
                        <div class="sm:col-span-2">
                            <p class="text-xs font-black text-zinc-500 uppercase tracking-widest mb-1">Descrição</p>
                            <p class="text-zinc-300 font-medium leading-relaxed">{{ $ticket->description }}</p>
                        </div>
                    </div>

                    <!-- Timeline de Atualizações Públicas -->
                    @if($ticket->logs->isNotEmpty())
                        <div>
                            <p class="text-xs font-black text-zinc-500 uppercase tracking-widest mb-6">Histórico</p>
                            <div class="space-y-0">
                                @foreach($ticket->logs as $log)
                                    <div class="relative pl-8 pb-6 last:pb-0">
                                        <!-- Linha vertical -->
                                        @if(!$loop->last)
                                            <div class="absolute left-[11px] top-6 bottom-0 w-[2px] bg-zinc-800"></div>
                                        @endif
                                        <!-- Dot -->
                                        <div class="absolute left-0 top-1.5 w-6 h-6 rounded-full border-2 flex items-center justify-center
                                                    {{ $log->action === \App\Enums\TicketLogAction::CRIADO ? 'bg-primary-variant/20 border-primary-variant/60' : 'bg-zinc-800 border-zinc-700' }}">
                                            @if($log->action === \App\Enums\TicketLogAction::CRIADO)
                                                <svg class="w-3 h-3 text-primary-variant" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                                </svg>
                                            @else
                                                <div class="w-1.5 h-1.5 rounded-full bg-zinc-500"></div>
                                            @endif
                                        </div>
                                        <!-- Conteúdo -->
                                        <div>
                                            <p class="text-sm font-black text-white">{{ $log->action->label() }}</p>
                                            @if($log->action === \App\Enums\TicketLogAction::STATUS_ALTERADO && $log->new_value)
                                                @php
                                                    $newStatus = \App\Enums\TicketStatus::tryFrom($log->new_value);
                                                @endphp
                                                @if($newStatus)
                                                    <span class="inline-flex items-center mt-1 px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $newStatus->badgeClasses() }}">
                                                        {{ $newStatus->label() }}
                                                    </span>
                                                @endif
                                            @endif
                                            @if($log->comment)
                                                <p class="text-zinc-400 text-sm mt-1 font-medium">{{ $log->comment }}</p>
                                            @endif
                                            <p class="text-zinc-600 text-xs mt-1.5 font-medium">
                                                {{ $log->created_at->format('d/m/Y \à\s H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </x-ui.card>
            @endif

        </div>
    </main>

    <footer class="w-full py-8 text-center text-zinc-600 text-sm bg-surface-darker">
        &copy; {{ date('Y') }} Guardião - Sistema de Gestão de Ocorrências
    </footer>
</x-guest-layout>
