<x-admin-layout :title="'Ocorrência ' . $ticket->protocol">
    <x-slot:actions>
        <a href="{{ route('admin.tickets.index') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-zinc-700 text-zinc-400 rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-zinc-800 hover:text-white transition-all">
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Voltar
        </a>
    </x-slot:actions>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <!-- Coluna Principal (2/3) -->
        <div class="xl:col-span-2 space-y-5">

            <!-- Header do Ticket -->
            <x-admin.section-card>
                <div class="p-5">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-5 pb-5 border-b border-zinc-800/70">
                        <div>
                            <p class="text-xs font-black text-zinc-500 uppercase tracking-widest mb-1">Protocolo</p>
                            <p class="text-2xl font-black text-primary-variant tracking-wider">{{ $ticket->protocol }}</p>
                            <p class="text-xs text-zinc-600 mt-1">Aberto em {{ $ticket->created_at->format('d/m/Y \à\s H:i') }}</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <x-admin.badge :classes="$ticket->status->badgeClasses()">{{ $ticket->status->label() }}</x-admin.badge>
                            <x-admin.badge :classes="$ticket->priority->badgeClasses()">{{ $ticket->priority->label() }}</x-admin.badge>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Categoria</p>
                            <p class="text-sm font-bold text-white">{{ $ticket->category?->name ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">SLA / Prazo</p>
                            @if($ticket->due_date)
                                <p class="text-sm font-bold {{ $ticket->due_date->isPast() ? 'text-red-400' : 'text-white' }}">
                                    {{ $ticket->due_date->format('d/m/Y H:i') }}
                                    @if($ticket->due_date->isPast())
                                        <span class="text-[10px] text-red-400">(vencido)</span>
                                    @else
                                        <span class="text-[10px] text-zinc-500">({{ $ticket->due_date->diffForHumans() }})</span>
                                    @endif
                                </p>
                            @else
                                <p class="text-sm text-zinc-500">Sem prazo definido</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Endereço</p>
                            <p class="text-sm font-bold text-white break-words">{{ $ticket->address }}</p>
                            @if($ticket->reference_point)
                                <p class="text-xs text-zinc-500 mt-0.5 break-words">{{ $ticket->reference_point }}</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Criado por</p>
                            <p class="text-sm font-bold text-white">
                                {{ $ticket->creator ? $ticket->creator->name : 'Cidadão (portal)' }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 pt-5 border-t border-zinc-800/70">
                        <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-2">Descrição</p>
                        <p class="text-sm text-zinc-300 leading-relaxed break-words">{{ $ticket->description }}</p>
                    </div>
                </div>
            </x-admin.section-card>

            <!-- Dados do Cidadão -->
            @if(auth()->user()->hasRole(\App\Enums\UserRole::ADMIN, \App\Enums\UserRole::DESPACHANTE))
                <x-admin.section-card title="Dados do Solicitante">
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Nome</p>
                            <p class="text-sm font-bold text-white break-words">{{ $ticket->citizen_name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">E-mail</p>
                            <p class="text-sm font-bold text-white break-words">{{ $ticket->citizen_email }}</p>
                        </div>
                        @if($ticket->citizen_phone)
                            <div>
                                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">Telefone</p>
                                <p class="text-sm font-bold text-white">{{ $ticket->citizen_phone }}</p>
                            </div>
                        @endif
                        @if($ticket->citizen_cpf)
                            <div>
                                <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1">CPF</p>
                                <p class="text-sm font-bold text-white">{{ $ticket->citizen_cpf }}</p>
                            </div>
                        @endif
                    </div>
                </x-admin.section-card>
            @endif

            <!-- Mídias -->
            @if($ticket->media->isNotEmpty())
                <x-admin.section-card title="Fotos da Ocorrência">
                    <div class="p-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach($ticket->media as $media)
                            @php $mediaUrl = Storage::temporaryUrl($media->file_path, now()->addHour()) @endphp
                            <a href="{{ $mediaUrl }}" target="_blank"
                               class="aspect-square rounded-xl overflow-hidden border border-zinc-700 hover:border-primary-variant transition-all group">
                                <img src="{{ $mediaUrl }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                     alt="Foto da ocorrência">
                            </a>
                        @endforeach
                    </div>
                </x-admin.section-card>
            @endif

            <!-- Formulário de Comentário -->
            <x-admin.section-card title="Adicionar Comentário">
                <form method="POST" action="{{ route('admin.tickets.comments', $ticket) }}" class="p-5 space-y-4">
                    @csrf
                    <div x-data="{ count: {{ strlen(old('comment', '')) }} }">
                        <div class="flex items-center justify-between mb-1">
                            <span></span>
                            <span class="text-[10px] text-zinc-600" x-text="count + '/2000'"></span>
                        </div>
                        <textarea name="comment" rows="3" required maxlength="2000"
                                  @input="count = $event.target.value.length"
                                  placeholder="Escreva um comentário..."
                                  class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-3 text-sm placeholder:text-zinc-600 focus:border-primary-variant focus:ring-primary-variant resize-none transition-all">{{ old('comment') }}</textarea>
                    </div>

                    @if(auth()->user()->hasRole(\App\Enums\UserRole::ADMIN, \App\Enums\UserRole::DESPACHANTE))
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input type="checkbox" name="is_public" value="1"
                                   class="w-4 h-4 rounded border-zinc-600 bg-surface-darker text-primary-variant focus:ring-primary-variant">
                            <span class="text-sm text-zinc-400 group-hover:text-zinc-200 transition-colors">
                                Comentário visível para o cidadão
                            </span>
                        </label>
                    @endif

                    <button type="submit"
                            class="px-6 py-2.5 bg-primary-variant/10 border border-primary-variant/30 text-primary-variant rounded-xl text-sm font-bold hover:bg-primary-variant/20 transition-all">
                        Enviar Comentário
                    </button>
                </form>
            </x-admin.section-card>

        </div>

        <!-- Coluna Lateral (1/3) -->
        <div class="space-y-5">

            <!-- Ações de Status -->
            @php $transitions = $allowedTransitions; @endphp
            @if(count($transitions) > 0)
                <x-admin.section-card title="Atualizar Status">
                    <div class="p-4 space-y-2">
                        @foreach($transitions as $newStatus)
                            <form method="POST" action="{{ route('admin.tickets.status', $ticket) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="{{ $newStatus->value }}">
                                <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl border text-sm font-bold uppercase tracking-widest transition-all
                                               {{ $newStatus->badgeClasses() }} hover:opacity-80">
                                    {{ $newStatus->label() }}
                                </button>
                            </form>
                        @endforeach
                    </div>
                </x-admin.section-card>
            @endif

            <!-- Atribuição de Agente -->
            @if(auth()->user()->hasRole(\App\Enums\UserRole::ADMIN, \App\Enums\UserRole::DESPACHANTE))
                <x-admin.section-card title="Agente Responsável">
                    <form method="POST" action="{{ route('admin.tickets.assign', $ticket) }}" class="p-4 space-y-3">
                        @csrf
                        @method('PATCH')
                        <select name="user_id"
                                class="w-full bg-surface-darker border border-zinc-700 text-white rounded-xl px-4 py-2.5 text-sm focus:border-primary-variant focus:ring-primary-variant transition-all">
                            <option value="">Sem atribuição</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ $ticket->user_id == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                    @if($agent->badge_number) ({{ $agent->badge_number }}) @endif
                                </option>
                            @endforeach
                        </select>
                        <button type="submit"
                                class="w-full py-2.5 bg-zinc-800 border border-zinc-700 text-zinc-300 rounded-xl text-sm font-bold hover:bg-zinc-700 transition-all">
                            Salvar Atribuição
                        </button>
                    </form>
                </x-admin.section-card>
            @endif

            <!-- Timeline -->
            <x-admin.section-card title="Histórico">
                @if($ticket->logs->isEmpty())
                    <x-admin.empty-state title="Sem registros" description="Nenhuma ação registrada." />
                @else
                    <div class="p-4">
                        <div class="space-y-0">
                            @foreach($ticket->logs as $log)
                                <div class="relative pl-7 pb-5 last:pb-0">
                                    @if(!$loop->last)
                                        <div class="absolute left-[10px] top-5 bottom-0 w-[2px] bg-zinc-800"></div>
                                    @endif
                                    <div class="absolute left-0 top-1 w-5 h-5 rounded-full border flex items-center justify-center
                                                {{ $log->action === \App\Enums\TicketLogAction::CRIADO
                                                   ? 'bg-primary-variant/20 border-primary-variant/50'
                                                   : 'bg-zinc-800 border-zinc-700' }}">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $log->action === \App\Enums\TicketLogAction::CRIADO ? 'bg-primary-variant' : 'bg-zinc-500' }}"></div>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <p class="text-xs font-black text-white">{{ $log->action->label() }}</p>
                                            @if($log->is_public)
                                                <span class="text-[9px] font-black text-green-400 uppercase tracking-widest">público</span>
                                            @endif
                                        </div>
                                        @if($log->action === \App\Enums\TicketLogAction::STATUS_ALTERADO && $log->new_value)
                                            @php $ns = \App\Enums\TicketStatus::tryFrom($log->new_value); @endphp
                                            @if($ns)
                                                <x-admin.badge :classes="$ns->badgeClasses()" class="mt-1">{{ $ns->label() }}</x-admin.badge>
                                            @endif
                                        @endif
                                        @if($log->comment)
                                            <p class="text-xs text-zinc-400 mt-1 leading-relaxed break-words">{{ $log->comment }}</p>
                                        @endif
                                        <p class="text-[10px] text-zinc-600 mt-1">
                                            {{ $log->user?->name ?? 'Cidadão' }} · {{ $log->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </x-admin.section-card>

        </div>
    </div>

</x-admin-layout>
