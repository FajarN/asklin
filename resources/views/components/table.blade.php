@props([
    'striped' => false,
    'hover' => true,
])

<div class="w-full overflow-x-auto rounded-xl border border-slate-200/80 dark:border-slate-800">
    <table {{ $attributes->merge(['class' => 'w-full text-left text-sm text-slate-600 dark:text-slate-300 divide-y divide-slate-200/80 dark:divide-slate-800']) }}>
        @if (isset($header))
            <thead class="bg-slate-50/80 dark:bg-slate-900/80 text-xs font-semibold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                {{ $header }}
            </thead>
        @endif
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 bg-white dark:bg-slate-900 @if($striped) [&>tr:nth-child(even)]:bg-slate-50/50 dark:[&>tr:nth-child(even)]:bg-slate-900/40 @endif @if($hover) [&>tr:hover]:bg-slate-50/80 dark:[&>tr:hover]:bg-slate-800/50 @endif">
            {{ $slot }}
        </tbody>
    </table>
</div>

