<div {{ $attributes->merge(['class' => 'relative inline-block']) }}>
    <!-- Shield -->
    <svg class="w-full h-full text-guardiao-brand-end" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M12 22C12 22 20 18 20 12V5L12 2L4 5V12C4 18 12 22 12 22Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <!-- Person Circle -->
    <div class="absolute -bottom-1 -right-1 bg-guardiao-brand-end rounded-full p-0.5 border-2 border-guardiao-dark">
        <svg class="w-6 h-6 text-guardiao-dark" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
        </svg>
    </div>
</div>
