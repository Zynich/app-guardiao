@props(['disabled' => false])

<input @disabled($disabled) {!! $attributes->merge(['class' => 'bg-surface/50 border border-zinc-800/50 text-white focus:border-primary-variant/50 focus:ring-primary-variant/50 rounded-xl shadow-sm px-4 py-3 placeholder:text-zinc-500 transition-all focus:bg-surface autofill:bg-surface autofill:text-white']) !!}>
