@props(['disabled' => false, 'icon' => null])

<div class="relative group">
    @if(isset($icon))
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-primary-variant transition-colors">
            {{ $icon }}
        </div>
    @endif
    <input @disabled($disabled) {!! $attributes->merge(['class' => 'bg-surface/50 border border-zinc-700 text-white focus:border-primary-variant focus:ring-primary-variant rounded-xl shadow-sm ' . (isset($icon) ? 'pl-11' : 'px-4') . ' py-3 placeholder:text-zinc-500 transition-all autofill:bg-surface autofill:text-white w-full font-medium']) !!}>
</div>
