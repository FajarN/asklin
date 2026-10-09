@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'href' => null,
    'type' => 'button',
])

@php
$variants = [
    'primary' => 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm focus:ring-emerald-500/30',
    'secondary' => 'bg-slate-100 hover:bg-slate-200 text-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-200 focus:ring-slate-500/30',
    'success' => 'bg-teal-600 hover:bg-teal-700 text-white shadow-sm focus:ring-teal-500/30',
    'danger' => 'bg-rose-600 hover:bg-rose-700 text-white shadow-sm focus:ring-rose-500/30',
    'outline' => 'border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-800/60 focus:ring-slate-500/20',
    'ghost' => 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 focus:ring-slate-500/20',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-xs rounded-lg gap-1.5',
    'md' => 'px-4 py-2 text-sm rounded-xl gap-2',
    'lg' => 'px-5 py-2.5 text-base rounded-xl gap-2.5',
];

$classes = 'inline-flex items-center justify-center font-medium transition-all duration-150 focus:outline-none focus:ring-2 disabled:opacity-50 disabled:cursor-not-allowed ' .
           ($variants[$variant] ?? $variants['primary']) . ' ' .
           ($sizes[$size] ?? $sizes['md']);

$tag = $href ? 'a' : 'button';
@endphp

<{{ $tag }}
    @if($href) href="{{ $href }}" @else type="{{ $type }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}>
    @if ($icon)
        <i class="{{ $icon }}"></i>
    @endif
    <span>{{ $slot }}</span>
</{{ $tag }}>

