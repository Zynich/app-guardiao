@props(['disabled' => false])

<input @disabled($disabled) {!! $attributes->merge(['class' => 'bg-guardiao-card/50 border border-zinc-700/50 text-white focus:border-guardiao-brand-end/50 focus:ring-guardiao-brand-end/50 rounded-xl shadow-sm px-4 py-3 placeholder:text-zinc-500 transition-all focus:bg-guardiao-card autofill:bg-guardiao-card autofill:text-white']) !!}>
