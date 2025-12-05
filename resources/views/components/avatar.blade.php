@props(['name', 'size' => 'md', 'gradient' => 'purple'])

@php
    $sizeClasses = [
        'xs' => 'w-6 h-6 text-xs',
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-base',
        'lg' => 'w-12 h-12 text-lg',
        'xl' => 'w-16 h-16 text-2xl',
        '2xl' => 'w-20 h-20 text-3xl',
    ];

    $gradientClasses = [
        'purple' => 'from-purple-500 to-indigo-500',
        'pink' => 'from-pink-500 to-purple-500',
        'blue' => 'from-blue-500 to-indigo-500',
        'green' => 'from-emerald-500 to-teal-500',
        'orange' => 'from-orange-500 to-pink-500',
    ];

    $classes = ($sizeClasses[$size] ?? $sizeClasses['md']) . ' bg-gradient-to-br ' . ($gradientClasses[$gradient] ?? $gradientClasses['purple']);
    $initial = strtoupper(substr($name, 0, 1));
@endphp

<div {{ $attributes->merge(['class' => $classes . ' rounded-full flex items-center justify-center text-white font-semibold flex-shrink-0']) }}>
    {{ $initial }}
</div>
