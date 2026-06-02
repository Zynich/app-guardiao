<div x-show="step === 0"
     x-transition:enter="transition ease-out duration-500 delay-200"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="w-full max-w-4xl px-6 flex flex-col items-center">

    <x-ui.card class="max-w-2xl p-8 rounded-3xl w-full border border-zinc-800/50 backdrop-blur-md">
        <div class="flex items-center gap-3 mb-6">
            <div class="bg-primary-variant/10 p-2 rounded-lg">
                <svg class="w-5 h-5 text-primary-variant" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-black text-white uppercase tracking-tighter">
                Acompanhar Solicitação
            </h3>
        </div>

        <form action="{{ route('portal.track') }}" method="GET"
              class="grid grid-cols-1 sm:grid-cols-4 gap-4"
              x-data="{ localLoading: false }" @submit="localLoading = true">
            <div class="sm:col-span-3">
                <x-ui.input type="text" name="protocol"
                             placeholder="Protocolo (ex: 2026-ABC123)"
                             class="w-full h-full uppercase"
                             value="{{ request('protocol') }}"
                             required />
            </div>
            <div class="sm:col-span-1">
                <x-ui.button type="submit" class="w-full h-full py-4 sm:py-0" ::disabled="localLoading">
                    <span x-show="!localLoading">Consultar</span>
                    <span x-show="localLoading" x-cloak class="flex items-center justify-center gap-2">
                        <x-ui.spinner class="w-4 h-4 text-white" />
                    </span>
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
