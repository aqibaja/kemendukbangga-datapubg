<a {{ $attributes->merge([
    'class' => 'inline-flex items-center justify-center
                w-10 h-10 rounded-xl
                text-slate-700
                transition hover:bg-black/10'
]) }}>
    {{ $slot }}
</a>