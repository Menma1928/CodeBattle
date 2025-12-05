@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('equipos.index') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <x-page-title>Crear Nuevo Equipo</x-page-title>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-10">Forma un equipo para participar en un evento</p>
        </div>

        <form method="POST" action="{{ route('equipos.store') }}" class="space-y-6">
            @csrf

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
                            :value="old('nombre')"
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
                        >{{ old('descripcion') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Máximo 1000 caracteres</p>
                        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                    </div>

                    <!-- URL Banner -->
                    <div>
                        <x-input-label for="url_banner" value="URL del Banner del Equipo" />
                        <x-text-input
                            id="url_banner"
                            name="url_banner"
                            type="url"
                            class="mt-1 block w-full"
                            :value="old('url_banner')"
                            maxlength="255"
                            placeholder="https://ejemplo.com/banner.jpg"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Opcional: URL de la imagen/logo de tu equipo</p>
                        <x-input-error :messages="$errors->get('url_banner')" class="mt-2" />
                    </div>
                </div>
            </x-card>

            <!-- Event Selection Card -->
            <x-card>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Seleccionar Evento</h3>

                <div>
                    <x-input-label for="event_id" value="Evento para Participar *" />
                    <select
                        id="event_id"
                        name="event_id"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-lg shadow-sm"
                        required
                    >
                        <option value="">Seleccione un evento</option>
                        @php
                            $events = \App\Models\Event::where('estado', '!=', 'finalizado')
                                ->orderBy('fecha_inicio', 'desc')
                                ->get();
                        @endphp
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->nombre }} - {{ $event->fecha_inicio->format('d/m/Y') }}
                                @if($event->estado === 'activo')
                                    (Activo)
                                @elseif($event->estado === 'pendiente')
                                    (Próximamente)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Selecciona el evento en el que deseas participar con este equipo</p>
                    <x-input-error :messages="$errors->get('event_id')" class="mt-2" />

                    @if($events->isEmpty())
                    <div class="mt-3 p-4 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800 dark:text-amber-200">No hay eventos disponibles</p>
                                <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">Actualmente no hay eventos activos o próximos. Contacta al administrador para más información.</p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Info Card -->
            <x-card class="bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-2">Información importante</h4>
                        <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1 list-disc list-inside">
                            <li>Serás automáticamente el líder del equipo al crearlo</li>
                            <li>Los equipos pueden tener hasta 5 miembros</li>
                            <li>Solo puedes estar en un equipo por evento</li>
                            <li>Podrás invitar a otros miembros después de crear el equipo</li>
                        </ul>
                    </div>
                </div>
            </x-card>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('equipos.index') }}">
                    <x-secondary-button type="button" class="w-full sm:w-auto">
                        Cancelar
                    </x-secondary-button>
                </a>
                <x-primary-button type="submit" class="w-full sm:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Crear Equipo
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection
