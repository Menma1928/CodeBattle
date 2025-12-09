@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('eventos.show', $evento) }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <x-page-title>Editar Evento</x-page-title>
            </div>
            <p class="text-gray-600 dark:text-gray-400 ml-10">Actualiza la información del evento: {{ $evento->nombre }}</p>
        </div>

        <form method="POST" action="{{ route('eventos.update', $evento) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PATCH')

            <!-- Basic Information Card -->
            <x-card>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Información Básica</h3>

                <div class="space-y-6">
                    <!-- Nombre -->
                    <div>
                        <x-input-label for="nombre" value="Nombre del Evento *" />
                        <x-text-input
                            id="nombre"
                            name="nombre"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('nombre', $evento->nombre)"
                            required
                            autofocus
                            placeholder="Ej: CodeBattle 2025"
                        />
                        <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                    </div>

                    <!-- Descripción -->
                    <div>
                        <x-input-label for="descripcion" value="Descripción *" />
                        <textarea
                            id="descripcion"
                            name="descripcion"
                            rows="4"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-lg shadow-sm"
                            required
                            placeholder="Describe el evento, objetivos y detalles importantes..."
                        >{{ old('descripcion', $evento->descripcion) }}</textarea>
                        <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                    </div>

                    <!-- Imagen del Evento -->
                    <div>
                        <x-input-label for="image" value="Cambiar Imagen del Evento" />
                        <input
                            id="image"
                            name="image"
                            type="file"
                            accept="image/jpeg,image/jpg,image/png"
                            class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900 dark:file:text-purple-300 dark:hover:file:bg-purple-800 border border-gray-300 dark:border-gray-700 rounded-lg cursor-pointer dark:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-purple-500"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Opcional: Sube una nueva imagen (JPG, JPEG, PNG - Máx. 200 MB) - Deja en blanco para mantener la actual
                        </p>
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />

                        @if($evento->url_imagen)
                        <div class="mt-3">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Imagen actual:</p>
                            <img src="{{ asset('storage/' . $evento->url_imagen) }}" alt="{{ $evento->nombre }}" class="w-32 h-32 rounded-lg object-cover border-2 border-purple-200 dark:border-purple-700 shadow-md">
                        </div>
                        @endif
                    </div>
                </div>
            </x-card>

            <!-- Date and Location Card -->
            <x-card>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Fecha y Ubicación</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Fecha Inicio -->
                    <div>
                        <x-input-label for="fecha_inicio" value="Fecha de Inicio *" />
                        <x-text-input
                            id="fecha_inicio"
                            name="fecha_inicio"
                            type="datetime-local"
                            class="mt-1 block w-full"
                            :value="old('fecha_inicio', $evento->fecha_inicio ? $evento->fecha_inicio->format('Y-m-d\TH:i') : '')"
                            min="{{ now()->format('Y-m-d\TH:i') }}"
                            required
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">No se pueden usar fechas anteriores a hoy</p>
                        <x-input-error :messages="$errors->get('fecha_inicio')" class="mt-2" />
                    </div>

                    <!-- Fecha Fin -->
                    <div>
                        <x-input-label for="fecha_fin" value="Fecha de Fin" />
                        <x-text-input
                            id="fecha_fin"
                            name="fecha_fin"
                            type="datetime-local"
                            class="mt-1 block w-full"
                            :value="old('fecha_fin', $evento->fecha_fin ? $evento->fecha_fin->format('Y-m-d\TH:i') : '')"
                            min="{{ now()->format('Y-m-d\TH:i') }}"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Opcional - No se pueden usar fechas anteriores a hoy</p>
                        <x-input-error :messages="$errors->get('fecha_fin')" class="mt-2" />
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <x-input-label for="direccion" value="Dirección / Ubicación" />
                        <x-text-input
                            id="direccion"
                            name="direccion"
                            type="text"
                            class="mt-1 block w-full"
                            :value="old('direccion', $evento->direccion)"
                            placeholder="Ej: Auditorio Principal, Campus Central"
                        />
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Opcional: Ubicación física o enlace virtual</p>
                        <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
                    </div>

                    <!-- Estado (Read-only) -->
                    <div class="md:col-span-2">
                        <x-input-label for="estado" value="Estado del Evento" />
                        <div class="mt-1 p-4 bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                @php
                                    $estadoActual = $evento->estado;
                                    $badgeType = $estadoActual === 'activo' ? 'success' : ($estadoActual === 'finalizado' ? 'error' : ($estadoActual === 'en_calificacion' ? 'purple' : 'warning'));
                                    $estadoTexto = [
                                        'pendiente' => 'Pendiente',
                                        'activo' => 'Activo',
                                        'en_calificacion' => 'En Calificación',
                                        'finalizado' => 'Finalizado'
                                    ][$estadoActual] ?? ucfirst($estadoActual);
                                @endphp
                                <x-badge :type="$badgeType" size="lg">
                                    {{ $estadoTexto }}
                                </x-badge>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        <strong>Estado actual del evento</strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="text-sm text-blue-800 dark:text-blue-200">
                                    <p class="font-semibold mb-1">El estado se actualiza automáticamente</p>
                                    <p>El estado del evento se actualiza según las fechas configuradas. No es necesario cambiarlo manualmente.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </x-card>

            <!-- Reglas Card -->
            <x-card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Reglas del Evento</h3>
                    <button
                        type="button"
                        onclick="addRegla()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold text-sm transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Regla
                    </button>
                </div>

                <div id="reglas-container" class="space-y-3">
                    @php
                        $reglas = old('reglas', $evento->reglas ?? []);
                        $reglas = is_array($reglas) ? $reglas : [];
                    @endphp

                    @if(count($reglas) > 0)
                        @foreach($reglas as $regla)
                        <div class="flex gap-2">
                            <x-text-input
                                type="text"
                                name="reglas[]"
                                class="flex-1"
                                value="{{ $regla }}"
                                placeholder="Escribe una regla del evento..."
                            />
                            <button
                                type="button"
                                onclick="this.parentElement.remove()"
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex-shrink-0"
                                title="Eliminar regla"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="flex gap-2">
                            <x-text-input
                                type="text"
                                name="reglas[]"
                                class="flex-1"
                                placeholder="Escribe una regla del evento..."
                            />
                        </div>
                    @endif
                </div>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Opcional: Define las reglas que los participantes deben seguir</p>
            </x-card>

            <!-- Requisitos Card -->
            <x-card>
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Requisitos del Evento</h3>
                    <button
                        type="button"
                        onclick="addRequisito()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-semibold text-sm transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Agregar Requisito
                    </button>
                </div>

                <div id="requisitos-container" class="space-y-3">
                    @php
                        $requisitos = old('requisitos', $evento->requisitos ?? []);
                        $requisitos = is_array($requisitos) ? $requisitos : [];
                    @endphp

                    @if(count($requisitos) > 0)
                        @foreach($requisitos as $requisito)
                        <div class="flex gap-2">
                            <x-text-input
                                type="text"
                                name="requisitos[]"
                                class="flex-1"
                                value="{{ $requisito }}"
                                placeholder="Escribe un requisito para participar..."
                            />
                            <button
                                type="button"
                                onclick="this.parentElement.remove()"
                                class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex-shrink-0"
                                title="Eliminar requisito"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <div class="flex gap-2">
                            <x-text-input
                                type="text"
                                name="requisitos[]"
                                class="flex-1"
                                placeholder="Escribe un requisito para participar..."
                            />
                        </div>
                    @endif
                </div>
                <p class="mt-3 text-sm text-gray-500 dark:text-gray-400">Opcional: Define los requisitos que deben cumplir los equipos</p>
            </x-card>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-between">
                <a href="{{ route('eventos.show', $evento) }}">
                    <x-secondary-button type="button" class="w-full sm:w-auto">
                        Cancelar
                    </x-secondary-button>
                </a>
                <div class="flex flex-col sm:flex-row gap-4">
                    @can('eliminar eventos')
                    <button type="button" onclick="document.getElementById('delete-event-form').submit();" class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 w-full">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Eliminar Evento
                    </button>
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

        <!-- Delete Form (outside main form to avoid nesting) -->
        @can('eliminar eventos')
        <form id="delete-event-form" method="POST" action="{{ route('eventos.destroy', $evento) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento? Esta acción no se puede deshacer.');" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
        @endcan
    </div>
</div>
@endsection

@push('scripts')
<script>
function addRegla() {
    const container = document.getElementById('reglas-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input
            type="text"
            name="reglas[]"
            placeholder="Escribe una regla del evento..."
            class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-lg shadow-sm"
        />
        <button
            type="button"
            onclick="this.parentElement.remove()"
            class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex-shrink-0"
            title="Eliminar regla"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    container.appendChild(div);
}

function addRequisito() {
    const container = document.getElementById('requisitos-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `
        <input
            type="text"
            name="requisitos[]"
            placeholder="Escribe un requisito para participar..."
            class="flex-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 dark:focus:border-purple-600 focus:ring-purple-500 dark:focus:ring-purple-600 rounded-lg shadow-sm"
        />
        <button
            type="button"
            onclick="this.parentElement.remove()"
            class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors flex-shrink-0"
            title="Eliminar requisito"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    container.appendChild(div);
}
</script>
@endpush
