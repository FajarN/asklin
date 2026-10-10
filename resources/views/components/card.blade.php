@props([
    'title' => null,
    'subtitle' => null,
    'headerAction' => null,
    'padding' => 'p-6',
    'class' => '',
])

<div {{ $attributes->merge(['class' => 'analytics-card bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl shadow-card transition-all duration-200 ' . $class]) }}>
    @if ($title || $subtitle || isset($header) || $headerAction)
        <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between gap-4">
            <div>
                @if (isset($header))
                    {{ $header }}
                @else
                    @if ($title)
                        <h3 class="text-base font-semibold text-slate-800 dark:text-slate-100 tracking-tight">{{ $title }}</h3>
                    @endif
                    @if ($subtitle)
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
                    @endif
                @endif
            </div>
            @if ($headerAction || isset($action))
                <div class="flex items-center gap-2">
                    {{ $headerAction ?? $action }}
                </div>
            @endif
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if (isset($footer))
        <div class="px-6 py-3.5 bg-slate-50/60 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-800 rounded-b-2xl">
            {{ $footer }}
        </div>
    @endif
</div>

