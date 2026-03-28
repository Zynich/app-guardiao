<x-guest-layout>

    <main class="flex-1 w-full max-w-4xl px-6 flex flex-col items-center justify-center py-12">
        <div class="mb-12 flex flex-col items-center text-center">
            <x-shield-logo class="w-20 h-20 text-guardiao-brand-end mb-8" />
            
            <h2 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl mb-4">
                Guardião da Cidade
            </h2>
            <p class="text-xl text-zinc-400 max-w-lg mx-auto">
                Melhorando nossa comunidade, um relato por vez.
            </p>

            <div class="mt-10" x-data="{ loading: false }">
                <x-primary-button class="text-lg px-12 py-4" @click="loading = true">
                    Relatar Novo Problema
                </x-primary-button>
            </div>
        </div>

        <div class="w-full max-w-2xl bg-guardiao-card border border-zinc-800 rounded-3xl p-8 shadow-2xl overflow-hidden relative group">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-guardiao-brand-start to-guardiao-brand-end opacity-20 group-hover:opacity-100 transition-opacity"></div>
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                Acompanhar Solicitação
            </h3>
            
            <form action="#" method="GET" class="flex flex-col sm:flex-row gap-4" x-data="{ loading: false }" @submit="loading = true">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-guardiao-brand-end/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <x-text-input 
                        type="text" 
                        name="protocol" 
                        placeholder="2024-ABC123" 
                        class="w-full pl-11"
                        required 
                    />
                </div>
                <button type="submit" class="bg-gradient-to-r from-guardiao-brand-start to-guardiao-brand-end hover:opacity-90 text-white font-extrabold py-3 px-8 rounded-xl transition-all shadow-xl shadow-guardiao-brand-start/40 active:scale-95 flex items-center justify-center gap-2 min-w-[140px] disabled:opacity-70 group" :disabled="loading">
                    <span x-show="!loading">Consultar</span>
                    <span x-show="loading" x-cloak class="flex items-center gap-2">
                        <x-spinner class="w-4 h-4 text-white" />
                        ...
                    </span>
                </button>
            </form>
        </div>
    </main>

    <footer class="w-full py-8 text-center text-zinc-600 text-sm">
        &copy; {{ date('Y') }} Guardião - Sistema de Gestão de Ocorrências
    </footer>
</x-guest-layout>
