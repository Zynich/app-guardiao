@props(['disabled' => false])

<textarea {{ $disabled ? 'disabled' : '' }} 
          {!! $attributes->merge(['class' => 'w-full bg-surface-darker/50 border border-zinc-700 text-white rounded-xl focus:border-primary-variant focus:ring-1 focus:ring-primary-variant transition-all duration-300 placeholder:text-zinc-500 px-4 py-3 min-h-[120px] resize-none focus:bg-surface font-medium']) !!}>{{ $slot }}</textarea>
