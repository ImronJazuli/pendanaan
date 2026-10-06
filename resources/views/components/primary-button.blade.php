<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-5 py-2.5 bg-primary hover:bg-primary-hover active:bg-primary-active border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2 transition-all shadow-sm active:scale-98']) }}>
    {{ $slot }}
</button>
