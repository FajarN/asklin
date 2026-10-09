@props([
    'variant' => 'info',
    'title' => null,
    'dismissible' => true,
])

@php
$variants = [
    'success' => [
        'container' => 'bg-emerald-50/90 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200',
        'icon' => 'fas fa-check-circle text-emerald-600 dark:text-emerald-400',
    ],
    'warning' => [
        'container' => 'bg-amber-50/90 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200',
        'icon' => 'fas fa-exclamation-triangle text-amber-600 dark:text-amber-400',
    ],
    'danger' => [
        'container' => 'bg-rose-50/90 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200',
        'icon' => 'fas fa-times-circle text-rose-600 dark:text-rose-400',
    ],
    'info' => [
        'container' => 'bg-sky-50/90 dark:bg-sky-950/40 border-sky-200 dark:border-sky-800 text-sky-800 dark:text-sky-200',
        'icon' => 'fas fa-info-circle text-sky-600 dark:text-sky-400',
    ],
];
$style = $variants[$variant] ?? $variants['info'];
@endphp

<div x-data="{ open: true }" x-show="open"
    {{ $attributes->merge(['class' => 'flex items-start gap-3.5 p-4 rounded-xl border text-sm transition-all ' . $style['container']]) }}>
    <div class="mt-0.5 shrink-0 text-base">
        <i class="{{ $style['icon'] }}"></i>
    </div>
    <div class="flex-1 min-w-0">
        @if ($title)
            <h4 class="font-semibold tracking-tight text-sm mb-0.5">{{ $title }}</h4>
        @endif
        <div class="leading-relaxed opacity-95">
            {{ $slot }}
        </div>
    </div>
    @if ($dismissible)
        <button type="button" @click="open = false" onclick="this.closest('[x-data]').remove()"
            class="shrink-0 -mr-1 -mt-1 p-1 rounded-lg opacity-60 hover:opacity-100 transition-opacity">
            <i class="fas fa-times text-xs"></i>
        </button>
    @endif
</div>

