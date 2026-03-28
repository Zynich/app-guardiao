<button {!! $attributes->merge(['type' => 'submit', 'class' => 'relative inline-flex items-center justify-center px-8 py-3 bg-gradient-to-r from-primary to-primary-variant border-0 rounded-xl font-extrabold text-sm text-white uppercase tracking-widest hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-primary-variant focus:ring-offset-0 transition ease-in-out duration-150 w-full shadow-xl shadow-primary/40 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed']) !!}>
    {{ $slot }}
</button>
