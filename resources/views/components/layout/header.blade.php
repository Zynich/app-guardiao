@props(['mode' => 'Portal do Cidadão'])

<nav class="w-full py-6 px-8 flex justify-between items-center border-b border-zinc-800 bg-surface-darker/50 backdrop-blur-md sticky top-0 z-50">
    <div class="flex flex-col">
        <h1 class="text-xl font-bold tracking-tight text-white">Guardião da Cidade</h1>
        <p class="text-xs font-semibold text-zinc-500 uppercase tracking-widest mt-1">
            Modo: <span class="text-zinc-300">{{ $mode }}</span>
        </p>
    </div>

    {{ $slot }}
</nav>
