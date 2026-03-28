<div {{ $attributes->merge(['class' => 'bg-surface border border-zinc-800/50 rounded-[2.5rem] p-10 shadow-2xl relative overflow-hidden group']) }}>
    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-primary to-primary-variant opacity-20 group-hover:opacity-100 transition-opacity"></div>
    
    {{ $slot }}
</div>
