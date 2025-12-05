@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('projects.show', $project) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <x-page-title>Editar Proyecto</x-page-title>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-10">Actualiza la información del proyecto: {{ $project->nombre }}</p>
        </div>

        <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Team Info Card (Read-only) -->
            <x-card class="bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border-purple-200 dark:border-purple-800">
                <div class="flex items-center gap-4">
                    @if($project->team->url_banner)
                        <img src="{{ $project->team->url_banner }}" alt="{{ $project->team->nombre }}" class="w-20 h-20 rounded-xl object-cover">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-br from-pink-500 to-purple-500 rounded-xl flex items-center justify-center text-white text-3xl font-bold">
                            {{ substr($project->team->nombre, 0, 1) }}
                        </div>
                    @endif

                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $project->team->nombre }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Evento: <a href="{{ route('eventos.show', $project->team->event) }}" class="font-semibold text-purple-600 dark:text-purple-400 hover:underline">{{ $project->team->event->nombre }}</a>
                        </p>
                        <div class="flex items-center gap-2 mt-2">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $project->team->users->count() }} miembros</span>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Project Information Card -->
            <x-card>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Información del Proyecto</h3>

                <div class="space-y-6">
                    <!-- Nombre -->
                    <div>
                        <x-input-label for="nombre" value="Nombre del Proyecto *" />
                        <x-text-input
                            id="nombre"
                            name="nombre"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('nombre', $project->nombre)"
                            required
                            autofocus
                            maxlength="255"
                            placeholder="Ej: Sistema de Gestión de Eventos"
                        />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    <!-- Descripción -->
                    <div>
                        <x-input-label for="descripcion" value="Descripción del Proyecto *" />
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="5"
                            maxlength="1000"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-lg shadow-sm"
                            required
                            placeholder="Describe el propósito del proyecto, tecnologías utilizadas y características principales..."
                        >{{ old('descripcion', $project->descripcion) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Máximo 1000 caracteres</p>
                        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                    </div>

                    <!-- GitHub URL -->
                    <div>
                        <x-input-label for="github_url" value="URL del Repositorio GitHub" />
                        <div class="mt-1 relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 {{ $eventIsActive ? 'text-gray-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </div>
                            <x-text-input
                                id="github_url"
                                name="github_url"
                                type="url"
                                class="pl-10 block w-full"
                                :value="old('github_url', $project->github_url)"
                                maxlength="255"
                                placeholder="https://github.com/usuario/repositorio"
                                :readonly="!$eventIsActive"
                            />
                        </div>

                        @if(!$eventIsActive)
                        <div class="mt-2 p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-sm text-amber-800 dark:text-amber-200">
                                    El evento no está activo. No puedes modificar la URL de GitHub en este momento.
                                </p>
                            </div>
                        </div>
                        @else
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Opcional: URL del repositorio público de tu proyecto</p>
                        @endif

                        <x-input-error :messages="$errors->get('github_url')" class="mt-2" />
                    </div>

                    @if($project->fecha_subida)
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Creado el: <span class="font-semibold text-gray-900 dark:text-white">{{ $project->fecha_subida->format('d/m/Y H:i') }}</span></span>
                        </div>
                    </div>
                    @endif
                </div>
            </x-card>

            @if($eventIsActive)
            <!-- Info Card -->
            <x-card class="bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Recomendaciones</h4>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1 list-disc list-inside">
                            <li>Mantén el repositorio actualizado durante el evento</li>
                            <li>Documenta los cambios importantes en el README</li>
                            <li>Asegúrate de que el código compile y funcione correctamente</li>
                            <li>Incluye tests si es posible</li>
                        </ul>
                    </div>
                </div>
            </x-card>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                <a href="{{ route('projects.show', $project) }}">
                    <x-secondary-button type="button" class="w-full sm:w-auto">
                        Cancelar
                    </x-secondary-button>
                </a>
                <div class="flex flex-col sm:flex-row gap-4">
                    @can('delete', $project)
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este proyecto? Esta acción no se puede deshacer.');" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar Proyecto
                        </x-danger-button>
                    </form>
                    @endcan
                    <x-primary-button type="submit" class="w-full sm:w-auto">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Guardar Cambios
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
