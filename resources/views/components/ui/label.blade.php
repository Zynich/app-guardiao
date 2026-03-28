@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-semibold text-xs tracking-wide text-zinc-400 uppercase ml-1']) }}>
    {{ $value ?? $slot }}
</label>
