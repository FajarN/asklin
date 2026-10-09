@props([
    'title' => '',
    'value' => '0',
    'icon' => 'fas fa-chart-bar',
    'variant' => 'emerald',
    'subtitle' => null,
    'href' => null,
])

@php
$colorStyles = [
    'emerald' => [
        'bg_icon' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 border border-emerald-200/50 dark:border-emerald-800/50',
        'badge' => 'text-emerald-700 bg-emerald-50 dark:bg-emerald-950/60 dark:text-emerald-300',
        'accent_border' => 'hover:border-emerald-300 dark:hover:border-emerald-700',
    ],
    'blue' => [
        'bg_icon' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 border border-blue-200/50 dark:border-blue-800/50',
        'badge' => 'text-blue-700 bg-blue-50 dark:bg-blue-950/60 dark:text-blue-300',
        'accent_border' => 'hover:border-blue-300 dark:hover:border-blue-700',
    ],
    'amber' => [
        'bg_icon' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 border border-amber-200/50 dark:border-amber-800/50',
        'badge' => 'text-amber-700 bg-amber-50 dark:bg-amber-950/60 dark:text-amber-300',
        'accent_border' => 'hover:border-amber-300 dark:hover:border-amber-700',
    ],
    'rose' => [
        'bg_icon' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 border border-rose-200/50 dark:border-rose-800/50',
        'badge' => 'text-rose-700 bg-rose-50 dark:bg-rose-950/60 dark:text-rose-300',
        'accent_border' => 'hover:border-rose-300 dark:hover:border-rose-700',
    ],
    'indigo' => [
        'bg_icon' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 border border-indigo-200/50 dark:border-indigo-800/50',
        'badge' => 'text-indigo-700 bg-indigo-50 dark:bg-indigo-950/60 dark:text-indigo-300',
        'accent_border' => 'hover:border-indigo-300 dark:hover:border-indigo-700',
    ],
    'purple' => [
        'bg_icon' => 'bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 border border-purple-200/50 dark:border-purple-800/50',
        'badge' => 'text-purple-700 bg-purple-50 dark:bg-purple-950/60 dark:text-purple-300',
        'accent_border' => 'hover:border-purple-300 dark:hover:border-purple-700',
    ],
    'cyan' => [
        'bg_icon' => 'bg-cyan-50 dark:bg-cyan-950/60 text-cyan-600 dark:text-cyan-400 border border-cyan-200/50 dark:border-cyan-800/50',
        'badge' => 'text-cyan-700 bg-cyan-50 dark:bg-cyan-950/60 dark:text-cyan-300',
        'accent_border' => 'hover:border-cyan-300 dark:hover:border-cyan-700',
    ],
    'slate' => [
        'bg_icon' => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
        'badge' => 'text-slate-700 bg-slate-100 dark:bg-slate-800 dark:text-slate-300',
        'accent_border' => 'hover:border-slate-300 dark:hover:border-slate-600',
    ],
];
$style = $colorStyles[$variant] ?? $colorStyles['emerald'];
$tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }} @if($href) href="{{ $href }}" @endif
    {{ $attributes->merge(['class' => 'group relative block bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl p-5 shadow-card hover:shadow-card-hover transition-all duration-300 ' . $style['accent_border']]) }}>
    <div class="flex items-center justify-between">
        <span class="text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
            {{ $title }}
        </span>
        <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-transform group-hover:scale-110 duration-200 {{ $style['bg_icon'] }}">
            <i class="{{ $icon }} text-base"></i>
        </div>
    </div>

    <div class="mt-3 flex items-baseline justify-between">
        <span class="text-3xl font-extrabold tracking-tight text-slate-800 dark:text-white">
            {{ number_format((int)$value, 0, ',', '.') }}
        </span>
        @if ($subtitle)
            <span class="text-xs font-medium text-slate-400 dark:text-slate-500">
                {{ $subtitle }}
            </span>
        @endif
    </div>

    <div class="mt-3 flex items-center text-xs text-slate-500 dark:text-slate-400 font-medium">
        <span class="inline-flex items-center gap-1 group-hover:translate-x-0.5 transition-transform">
            <span>Lihat rincian</span>
            <i class="fas fa-arrow-right text-[10px] opacity-70"></i>
        </span>
    </div>
</{{ $tag }}>

