@props([
    'items' => [],
    'class' => '',
])

<nav aria-label="Breadcrumb" {{ $attributes->merge(['class' => 'flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 text-xs shadow-xs text-slate-500 dark:text-slate-400 overflow-x-auto whitespace-nowrap ' . $class]) }}>
    <!-- Home Link -->
    <a href="{{ route('dashboard.index') }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 flex items-center justify-center shrink-0 transition-colors" title="Dashboard">
        <i class="fas fa-home text-sm"></i>
    </a>

    @if(!empty($items))
        @foreach($items as $index => $item)
            <i class="fas fa-chevron-right text-[10px] text-slate-400 shrink-0"></i>
            @php
                $isLast = ($index === count($items) - 1);
                $label = is_array($item) ? ($item['label'] ?? '') : $item;
                $url = is_array($item) ? ($item['url'] ?? null) : null;
            @endphp

            @if($isLast)
                <span class="text-slate-700 dark:text-slate-200 font-semibold truncate" aria-current="page">
                    {{ $label }}
                </span>
            @elseif(!empty($url))
                <a href="{{ $url }}" class="text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 font-medium transition-colors hover:underline shrink-0">
                    {{ $label }}
                </a>
            @else
                <span class="text-emerald-600 dark:text-emerald-400 font-medium shrink-0">
                    {{ $label }}
                </span>
            @endif
        @endforeach
    @else
        {{ $slot }}
    @endif
</nav>
