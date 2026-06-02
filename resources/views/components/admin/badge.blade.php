@props(['classes' => ''])

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-widest border {$classes}"]) }}>
    {{ $slot }}
</span>
