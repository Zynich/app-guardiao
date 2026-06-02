@props(['label', 'value', 'color' => 'zinc', 'sub' => null])

@php
$colors = [
    'yellow' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20',
    'blue'   => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
    'green'  => 'text-green-400 bg-green-500/10 border-green-500/20',
    'red'    => 'text-red-400 bg-red-500/10 border-red-500/20',
    'orange' => 'text-orange-400 bg-orange-500/10 border-orange-500/20',
    'zinc'   => 'text-zinc-400 bg-zinc-500/10 border-zinc-500/20',
    'cyan'   => 'text-primary-variant bg-primary-variant/10 border-primary-variant/20',
];
$cls = $colors[$color] ?? $colors['zinc'];
@endphp

<div class="bg-surface border border-zinc-800/70 rounded-2xl p-5 flex items-center gap-4">
    @if($slot->isNotEmpty())
        <div class="shrink-0 w-12 h-12 rounded-xl border flex items-center justify-center {{ $cls }}">
            {{ $slot }}
        </div>
    @endif
    <div class="min-w-0">
        <p class="text-2xl font-black text-white">{{ $value }}</p>
        <p class="text-xs font-bold text-zinc-500 uppercase tracking-widest truncate">{{ $label }}</p>
        @if($sub)
            <p class="text-[10px] text-zinc-600 mt-0.5">{{ $sub }}</p>
        @endif
    </div>
</div>
