<div x-show="step === 4" x-cloak
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     class="w-full max-w-2xl px-6">

    <x-ui.card class="p-10 rounded-3xl border border-zinc-800/50 backdrop-blur-xl bg-surface/40 shadow-2xl text-center">

        <!-- Ícone de sucesso -->
        <div class="flex justify-center mb-8">
            <div class="w-20 h-20 rounded-full bg-green-500/10 border border-green-500/30 flex items-center justify-center">
                <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>

        <h3 class="text-2xl font-black text-white mb-2 uppercase tracking-tight">Relato Enviado!</h3>
        <p class="text-zinc-400 mb-10 font-medium">
            Sua solicitação foi registrada com sucesso. Use o protocolo abaixo para acompanhar o andamento.
        </p>

        <!-- Protocolo -->
        <div class="bg-surface-darker/60 border border-zinc-700 rounded-2xl p-6 mb-8">
            <p class="text-xs font-black text-zinc-500 uppercase tracking-widest mb-3">Número do Protocolo</p>
            <div class="flex items-center justify-center gap-3">
                <span class="text-3xl font-black text-primary-variant tracking-wider" x-text="protocol"></span>
                <button @click="copyProtocol()"
                        class="p-2 rounded-lg border border-zinc-700 text-zinc-400 hover:border-primary-variant hover:text-primary-variant transition-all active:scale-95"
                        :title="copied ? 'Copiado!' : 'Copiar protocolo'">
                    <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <svg x-show="copied" x-cloak class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </button>
            </div>
            <p class="text-xs text-zinc-600 mt-3 font-medium">Guarde este número — ele é a chave do seu chamado.</p>
        </div>

        <!-- Ações -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a :href="'/protocolo?protocol=' + protocol"
               class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-primary-variant/50 text-primary-variant font-bold text-sm uppercase tracking-widest hover:bg-primary-variant/10 transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Acompanhar Status
            </a>
            <button @click="resetForm()"
                    class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl border border-zinc-700 text-zinc-400 font-bold text-sm uppercase tracking-widest hover:bg-zinc-800 hover:text-white transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Novo Relato
            </button>
        </div>

    </x-ui.card>
</div>
