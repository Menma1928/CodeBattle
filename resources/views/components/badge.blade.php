@props(['type' => 'default', 'size' => 'md'])

@php
    $typeClasses = [
        'success' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300',
        'warning' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300',
        'error' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300',
        'info' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300',
        'purple' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300',
        'default' => 'bg-gray-100 text-gray-700 dark:bg-gray-900/30 dark:text-gray-300',
    ];

    $sizeClasses = [
        'sm' => 'px-2 py-0.5 text-xs',
        'md' => 'px-3 py-1 text-sm',
        'lg' => 'px-4 py-1.5 text-base',
    ];

    $classes = ($typeClasses[$type] ?? $typeClasses['default']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['md']);
@endphp

<span {{ $attributes->merge(['class' => $classes . ' inline-flex items-center font-semibold rounded-full']) }}>
    {{ $slot }}
</span>
