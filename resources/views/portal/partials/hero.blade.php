<div x-show="step === 0" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0" class="w-full max-w-4xl px-6 flex flex-col items-center text-center mb-12">
    <x-brand.logo class="w-20 h-20 text-primary-variant mb-8" />

    <h2 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl mb-4">
        Guardião da Cidade
    </h2>
    <p class="text-xl text-zinc-400 max-w-lg mx-auto">
        Melhorando nossa comunidade, um relato por vez.
    </p>

    <div class="mt-10">
        <x-ui.button class="text-lg px-12 py-4 shadow-2xl shadow-primary/20" @click="step = 1">
            Relatar Novo Problema
        </x-ui.button>
    </div>
</div>
