@props(['subtitle' => null])

<div {{ $attributes->merge(['class' => 'mb-8']) }}>
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
        {{ $slot }}
    </h1>
    @if($subtitle)
        <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $subtitle }}</p>
    @endif
</div>
