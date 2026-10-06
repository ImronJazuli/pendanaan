@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-border text-text-primary bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 rounded-xl shadow-xs transition-colors']) }}>
