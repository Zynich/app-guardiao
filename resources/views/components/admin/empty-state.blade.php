@props(['title' => 'Nenhum resultado', 'description' => 'Não há itens para exibir.', 'action' => null])

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="w-16 h-16 rounded-full bg-zinc-800/50 border border-zinc-700/50 flex items-center justify-center mb-4">
        <svg class="w-7 h-7 text-zinc-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
        </svg>
    </div>
    <p class="text-sm font-black text-zinc-400 uppercase tracking-tight">{{ $title }}</p>
    <p class="text-xs text-zinc-600 mt-1 max-w-xs">{{ $description }}</p>
    @if($action)
        <div class="mt-4">{{ $action }}</div>
    @endif
</div>
