@props(['padding' => true, 'hover' => false])

@php
    $classes = 'bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700';
    if ($padding) {
        $classes .= ' p-6';
    }
    if ($hover) {
        $classes .= ' hover:shadow-md transition-shadow duration-200';
    }
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
