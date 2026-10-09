@props([
    'variant' => 'slate',
    'size' => 'md',
    'dot' => false,
])

@php
$variants = [
    'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800/60',
    'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60 dark:bg-emerald-950/50 dark:text-emerald-300 dark:border-emerald-800/60',
    'warning' => 'bg-amber-50 text-amber-700 border-amber-200/60 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800/60',
    'amber' => 'bg-amber-50 text-amber-700 border-amber-200/60 dark:bg-amber-950/50 dark:text-amber-300 dark:border-amber-800/60',
    'danger' => 'bg-rose-50 text-rose-700 border-rose-200/60 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800/60',
    'rose' => 'bg-rose-50 text-rose-700 border-rose-200/60 dark:bg-rose-950/50 dark:text-rose-300 dark:border-rose-800/60',
    'info' => 'bg-sky-50 text-sky-700 border-sky-200/60 dark:bg-sky-950/50 dark:text-sky-300 dark:border-sky-800/60',
    'blue' => 'bg-blue-50 text-blue-700 border-blue-200/60 dark:bg-blue-950/50 dark:text-blue-300 dark:border-blue-800/60',
    'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200/60 dark:bg-indigo-950/50 dark:text-indigo-300 dark:border-indigo-800/60',
    'slate' => 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700',
];

$dotColors = [
    'success' => 'bg-emerald-500',
    'emerald' => 'bg-emerald-500',
    'warning' => 'bg-amber-500',
    'amber' => 'bg-amber-500',
    'danger' => 'bg-rose-500',
    'rose' => 'bg-rose-500',
    'info' => 'bg-sky-500',
    'blue' => 'bg-blue-500',
    'indigo' => 'bg-indigo-500',
    'slate' => 'bg-slate-500',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-[11px]',
    'md' => 'px-2.5 py-1 text-xs',
    'lg' => 'px-3 py-1 text-sm',
];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 font-medium rounded-full border ' . ($variants[$variant] ?? $variants['slate']) . ' ' . ($sizes[$size] ?? $sizes['md'])]) }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors[$variant] ?? 'bg-slate-400' }}"></span>
    @endif
    {{ $slot }}
</span>

