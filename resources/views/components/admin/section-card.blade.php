@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'bg-surface border border-zinc-800/70 rounded-2xl overflow-hidden']) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-zinc-800/70 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-black text-white uppercase tracking-tight">{{ $title }}</h3>
                @if($subtitle)
                    <p class="text-xs text-zinc-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @isset($actions)
                <div>{{ $actions }}</div>
            @endisset
        </div>
    @endif
    {{ $slot }}
</div>
