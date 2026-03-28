@props(['placeholder' => 'Selecione uma opção', 'options' => [], 'name' => ''])

<div x-data="{ 
    open: false, 
    selected: '', 
    label: '{{ $placeholder }}',
    name: '{{ $name }}',
    select(value, label) {
        this.selected = value;
        this.label = label;
        this.open = false;
        
        // Dispatch standard event for parent
        this.$el.dispatchEvent(new CustomEvent('change', { 
            detail: value,
            bubbles: true 
        }));
    }
}" @click.away="open = false" class="relative group w-full">
    
    <!-- Hidden Input for Form Submission -->
    <input type="hidden" :name="name" :value="selected">

    <!-- Trigger -->
    <button type="button" 
            @click="open = !open"
            class="w-full bg-surface-darker/50 border border-zinc-800 text-left text-zinc-100 rounded-xl focus:outline-none focus:border-primary-variant/50 focus:ring-1 focus:ring-primary-variant/50 transition-all duration-300 px-4 py-3 flex items-center justify-between group-hover:border-zinc-700"
            :class="open ? 'border-primary-variant/50 ring-1 ring-primary-variant/50' : ''">
        <span x-text="label" :class="selected === '' ? 'text-zinc-500' : 'text-zinc-100'" class="truncate"></span>
        <svg class="h-5 w-5 text-zinc-500 group-hover:text-primary-variant transition-transform duration-300" 
             :class="open ? 'rotate-180 text-primary-variant' : ''" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown List -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute z-50 w-full mt-2 bg-[#1a1a1a] border border-zinc-800 rounded-xl shadow-2xl overflow-hidden backdrop-blur-xl">
        
        <div class="max-h-60 overflow-y-auto custom-scrollbar p-1">
            {{ $slot }}
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #444; }
</style>
