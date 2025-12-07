@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('jury.event.projects', $event) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <x-page-title>Calificar Proyecto</x-page-title>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-10">Evento: <span class="font-semibold text-purple-600 dark:text-purple-400">{{ $event->nombre }}</span></p>
        </div>

        <!-- Project Info Card -->
        <x-card class="mb-6 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-purple-200 dark:border-purple-800">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $project->nombre }}</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-3">{{ $project->descripcion }}</p>
                    <div class="flex flex-wrap items-center gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="font-medium text-gray-700 dark:text-gray-300">
                                <a href="{{ route('equipos.show', $project->team) }}" class="text-purple-600 dark:text-purple-400 hover:underline">{{ $project->team->nombre }}</a>
                            </span>
                        </div>
                        @if($project->github_url)
                        <a href="{{ $project->github_url }}" target="_blank" class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            Ver en GitHub
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </x-card>

        @if($event->estado === 'finalizado')
        <!-- Event Finalized Message -->
        <x-card class="bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-red-600 dark:text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-red-900 dark:text-red-100 mb-1">Evento Finalizado</h3>
                    <p class="text-red-700 dark:text-red-300">Este evento ha sido finalizado por el administrador. Ya no es posible modificar las calificaciones.</p>
                </div>
            </div>
        </x-card>

        <div class="flex justify-center">
            <a href="{{ route('jury.event.projects', $event) }}">
                <x-secondary-button type="button" class="w-full sm:w-auto">
                    Volver a Proyectos
                </x-secondary-button>
            </a>
        </div>
        @elseif(!$event->canEditRatings())
        <!-- Cannot Edit Ratings Message -->
        <x-card class="bg-yellow-50 dark:bg-yellow-900/20 border-yellow-200 dark:border-yellow-800">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-100 mb-1">No Disponible</h3>
                    <p class="text-yellow-700 dark:text-yellow-300">Las calificaciones solo pueden editarse cuando el evento está en estado de "Calificación".</p>
                </div>
            </div>
        </x-card>

        <div class="flex justify-center">
            <a href="{{ route('jury.event.projects', $event) }}">
                <x-secondary-button type="button" class="w-full sm:w-auto">
                    Volver a Proyectos
                </x-secondary-button>
            </a>
        </div>
        @else
        <!-- Rating Form -->
        <form method="POST" action="{{ route('jury.store.ratings', [$event, $project]) }}" class="space-y-6">
            @csrf

            <x-card>
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Calificar Requisitos</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Evalúa cada requisito del proyecto con una puntuación del 1 al 10</p>
                </div>

                <div class="space-y-8">
                    @foreach($event->requirements as $index => $requirement)
                    <div class="pb-8 @if(!$loop->last) border-b border-gray-200 dark:border-gray-700 @endif">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <label class="block text-base font-semibold text-gray-900 dark:text-white mb-1">
                                    {{ $index + 1 }}. {{ $requirement->name }}
                                </label>
                                @if($requirement->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $requirement->description }}</p>
                                @endif
                            </div>
                            <div class="flex-shrink-0">
                                <div class="flex flex-col items-center">
                                    <span id="value_{{ $requirement->id }}" class="text-4xl font-bold text-purple-600 dark:text-purple-400 w-16 text-center">
                                        {{ $existingRatings->get($requirement->id)?->rating ?? 5 }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">/ 10</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative">
                            <input
                                type="range"
                                name="rating_{{ $requirement->id }}"
                                id="rating_{{ $requirement->id }}"
                                min="1"
                                max="10"
                                value="{{ $existingRatings->get($requirement->id)?->rating ?? 5 }}"
                                class="w-full h-3 bg-gradient-to-r from-red-200 via-yellow-200 to-emerald-200 dark:from-red-900/50 dark:via-yellow-900/50 dark:to-emerald-900/50 rounded-lg appearance-none cursor-pointer slider"
                                oninput="updateValue({{ $requirement->id }}, this.value)"
                                required
                            >
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2 px-1">
                                <span>1 (Muy bajo)</span>
                                <span>5 (Medio)</span>
                                <span>10 (Excelente)</span>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('rating_' . $requirement->id)" class="mt-2" />
                    </div>
                    @endforeach
                </div>
            </x-card>

            <!-- Info Card -->
            <x-card class="bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Guía de Calificación</h4>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                            <li><strong>1-3:</strong> No cumple con el requisito o presenta deficiencias graves</li>
                            <li><strong>4-6:</strong> Cumple parcialmente, tiene áreas de mejora significativas</li>
                            <li><strong>7-8:</strong> Cumple bien el requisito, con pequeños detalles a mejorar</li>
                            <li><strong>9-10:</strong> Cumple completamente de manera excepcional</li>
                        </ul>
                    </div>
                </div>
            </x-card>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('jury.event.projects', $event) }}">
                    <x-secondary-button type="button" class="w-full sm:w-auto">
                        Cancelar
                    </x-secondary-button>
                </a>
                <x-primary-button type="submit" class="w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar Calificaciones
                </x-primary-button>
            </div>
        </form>
        @endif
    </div>
</div>

@push('styles')
<style>
    /* Custom slider styles */
    .slider::-webkit-slider-thumb {
        appearance: none;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgb(147, 51, 234); /* purple-600 */
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: all 0.2s;
    }

    .slider::-webkit-slider-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(147, 51, 234, 0.4);
    }

    .slider::-moz-range-thumb {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: rgb(147, 51, 234); /* purple-600 */
        cursor: pointer;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: all 0.2s;
    }

    .slider::-moz-range-thumb:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 8px rgba(147, 51, 234, 0.4);
    }

    .dark .slider::-webkit-slider-thumb,
    .dark .slider::-moz-range-thumb {
        background: rgb(192, 132, 252); /* purple-400 */
    }
</style>
@endpush

@push('scripts')
<script>
function updateValue(requirementId, value) {
    document.getElementById('value_' + requirementId).textContent = value;
}
</script>
@endpush
@endsection
