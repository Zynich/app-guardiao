@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-bold text-xs tracking-wider text-zinc-300 uppercase ml-1 opacity-90']) }}>
    {{ $value ?? $slot }}
</label>
