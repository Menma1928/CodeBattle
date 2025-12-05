@props(['title', 'message' => null, 'icon' => null])

<div {{ $attributes->merge(['class' => 'text-center py-12']) }}>
    @if($icon)
        <div class="mx-auto w-16 h-16 text-gray-400 mb-4">
            {!! $icon !!}
        </div>
    @endif

    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
        {{ $title }}
    </h3>

    @if($message)
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            {{ $message }}
        </p>
    @endif

    @if(isset($action))
        <div>
            {{ $action }}
        </div>
    @endif
</div>
