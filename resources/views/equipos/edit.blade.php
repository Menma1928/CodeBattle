@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('equipos.show', $equipo) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <x-page-title>Editar Equipo</x-page-title>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-10">Actualiza la información del equipo: {{ $equipo->nombre }}</p>
        </div>

        <form method="POST" action="{{ route('equipos.update', $equipo) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Team Information Card -->
            <x-card>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Información del Equipo</h3>

                <div class="space-y-6">
                    <!-- Nombre -->
                    <div>
                        <x-input-label for="nombre" value="Nombre del Equipo *" />
                        <x-text-input
                            id="nombre"
                            name="nombre"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('nombre', $equipo->nombre)"
                            required
                            autofocus
                            maxlength="255"
                            placeholder="Ej: Los Desarrolladores"
                        />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    <!-- Descripción -->
                    <div>
                        <x-input-label for="descripcion" value="Descripción del Equipo *" />
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="4"
                            maxlength="1000"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-lg shadow-sm"
                            required
                            placeholder="Describe tu equipo, habilidades y objetivos..."
                        >{{ old('descripcion', $equipo->descripcion) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Máximo 1000 caracteres</p>
                        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                    </div>

                    <!-- Banner del Equipo -->
                    <div>
                        <x-input-label for="banner" value="Banner del Equipo" />
                        <input
                            id="banner"
                            name="banner"
                            type="file"
                            accept="image/jpeg,image/jpg,image/png"
                            class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900 dark:file:text-purple-300 dark:hover:file:bg-purple-800 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Opcional: Sube un nuevo banner para tu equipo (JPG, JPEG, PNG - Máx. 200 MB)
                        </p>
                        <x-input-error :messages="$errors->get('banner')" class="mt-2" />

                        @if($equipo->url_banner)
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Banner actual:</p>
                            <img src="{{ asset('storage/' . $equipo->url_banner) }}" alt="{{ $equipo->nombre }}" class="w-40 h-40 rounded-xl object-cover border-2 border-gray-200 dark:border-gray-700 shadow-md">
                        </div>
                        @endif
                    </div>
                </div>
            </x-card>

            <!-- Event Info Card (Read-only) -->
            <x-card class="bg-gray-50 dark:bg-gray-800/50">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Evento</h3>

                <div class="flex items-start gap-4">
                    @if($equipo->event->url_imagen)
                        <img src="{{ asset('storage/' . $equipo->event->url_imagen) }}" alt="{{ $equipo->event->nombre }}" class="w-16 h-16 rounded-lg object-cover">
                    @else
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-lg flex items-center justify-center text-white text-2xl font-bold">
                            {{ substr($equipo->event->nombre, 0, 1) }}
                        </div>
                    @endif

                    <div class="flex-1">
                        <a href="{{ route('eventos.show', $equipo->event) }}" class="text-lg font-semibold text-purple-600 dark:text-purple-400 hover:underline">
                            {{ $equipo->event->nombre }}
                        </a>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            {{ $equipo->event->fecha_inicio->format('d/m/Y') }}
                        </p>
                        @php
                            $badgeType = $equipo->event->estado === 'activo' ? 'success' : ($equipo->event->estado === 'finalizado' ? 'error' : 'warning');
                        @endphp
                        <x-badge :type="$badgeType" class="mt-2">
                            {{ ucfirst($equipo->event->estado) }}
                        </x-badge>
                    </div>
                </div>

                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 italic">
                    Los equipos no pueden cambiar de evento después de ser creados
                </p>
            </x-card>

            <!-- Team Stats Card -->
            <x-card class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-purple-200 dark:border-purple-800">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Estadísticas del Equipo</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg">
                        <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $equipo->users->count() }}/5
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Miembros</div>
                    </div>

                    <div class="text-center p-4 bg-white dark:bg-gray-800 rounded-lg">
                        <div class="text-3xl font-bold text-indigo-600 dark:text-indigo-400">
                            @if($equipo->posicion)
                                #{{ $equipo->posicion }}
                            @else
                                -
                            @endif
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Posición</div>
                    </div>
                </div>
            </x-card>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                <a href="{{ route('equipos.show', $equipo) }}">
                    <x-secondary-button type="button" class="w-full sm:w-auto">
                        Cancelar
                    </x-secondary-button>
                </a>
                <div class="flex flex-col sm:flex-row gap-4">
                    @can('eliminar equipos')
                    <form method="POST" action="{{ route('equipos.destroy', $equipo) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este equipo? Esta acción no se puede deshacer y se eliminarán todos los miembros.');" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit" class="w-full">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Eliminar Equipo
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
