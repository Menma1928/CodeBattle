@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <x-page-title>{{ $title ?? 'Eventos' }}</x-page-title>
                <x-badge type="purple" class="mt-2">
                    @hasrole('Super Admin')
                        Super Administrador
                    @elsehasrole('Administrador')
                        Administrador
                    @else
                        Participante
                    @endhasrole
                </x-badge>
            </div>
        </div>

        <!-- Search and Filters -->
        <x-card class="mb-6">
            <form method="GET" action="{{ route('eventos.index') }}" class="space-y-4">
                <!-- Search Bar -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <x-text-input
                            name="search"
                            type="text"
                            class="w-full"
                            placeholder="Buscar eventos por nombre, descripción o ubicación..."
                            value="{{ request('search') }}"
                        />
                    </div>
                    <div class="flex gap-2">
                        <x-primary-button type="submit">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Buscar
                        </x-primary-button>
                        @if(request('search') || request('estado'))
                        <a href="{{ route('eventos.index') }}">
                            <x-secondary-button type="button">
                                Limpiar
                            </x-secondary-button>
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Status Filters -->
                <div class="flex flex-wrap gap-2">
                    <button type="submit" name="estado" value="todos" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request('estado', 'todos') == 'todos' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Todos
                    </button>
                    <button type="submit" name="estado" value="activo" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request('estado') == 'activo' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Activos
                    </button>
                    <button type="submit" name="estado" value="finalizado" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request('estado') == 'finalizado' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Finalizados
                    </button>
                    <button type="submit" name="estado" value="proximo" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ request('estado') == 'proximo' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Próximos
                    </button>
                </div>
            </form>
        </x-card>

        <!-- Events List -->
        <div class="space-y-4">
            @forelse($events as $event)
            <x-card hover="true" class="group">
                <div class="flex flex-col sm:flex-row gap-6">
                    <!-- Event Logo/Image -->
                    <a href="{{ route('eventos.show', $event) }}" class="flex-shrink-0">
                        @if($event->url_imagen)
                            <img src="{{ $event->url_imagen }}" alt="{{ $event->nombre }}" class="w-24 h-24 rounded-xl object-cover group-hover:scale-105 transition-transform duration-200">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center text-white text-3xl font-bold group-hover:scale-105 transition-transform duration-200">
                                {{ substr($event->nombre, 0, 1) }}
                            </div>
                        @endif
                    </a>

                    <!-- Event Information -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="flex-1">
                                <a href="{{ route('eventos.show', $event) }}" class="block group-hover:text-purple-600 transition-colors">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $event->nombre }}
                                    </h3>
                                </a>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    @php
                                        $badgeType = $event->estado === 'activo' ? 'success' : ($event->estado === 'finalizado' ? 'error' : 'warning');
                                    @endphp
                                    <x-badge :type="$badgeType">
                                        {{ ucfirst($event->estado) }}
                                    </x-badge>
                                </div>
                            </div>

                            <!-- Action Buttons (Desktop) -->
                            <div class="hidden sm:flex gap-2 flex-shrink-0">
                                @can('editar eventos')
                                <a href="{{ route('eventos.edit', $event) }}">
                                    <x-secondary-button>
                                        Editar
                                    </x-secondary-button>
                                </a>
                                @endcan
                                @can('eliminar eventos')
                                <form method="POST" action="{{ route('eventos.destroy', $event) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit">
                                        Eliminar
                                    </x-danger-button>
                                </form>
                                @endcan
                                <a href="{{ route('eventos.show', $event) }}">
                                    <x-primary-button>
                                        Ver Detalles
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>

                        <div class="space-y-1 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>
                                    <strong>Inicio:</strong> {{ $event->fecha_inicio->format('d/m/Y H:i') }}
                                    @if($event->fecha_fin)
                                        | <strong>Fin:</strong> {{ $event->fecha_fin->format('d/m/Y H:i') }}
                                    @endif
                                </span>
                            </div>

                            @if($event->direccion)
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span><strong>Ubicación:</strong> {{ $event->direccion }}</span>
                            </div>
                            @endif

                            <p class="mt-2 text-gray-700 dark:text-gray-300 line-clamp-2">
                                {{ $event->descripcion ?? 'Sin descripción disponible.' }}
                            </p>
                        </div>

                        <!-- Action Buttons (Mobile) -->
                        <div class="flex sm:hidden gap-2 mt-4 flex-wrap">
                            @can('editar eventos')
                            <a href="{{ route('eventos.edit', $event) }}">
                                <x-secondary-button class="w-full">
                                    Editar
                                </x-secondary-button>
                            </a>
                            @endcan
                            @can('eliminar eventos')
                            <form method="POST" action="{{ route('eventos.destroy', $event) }}" class="flex-1" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit" class="w-full">
                                    Eliminar
                                </x-danger-button>
                            </form>
                            @endcan
                            <a href="{{ route('eventos.show', $event) }}" class="flex-1">
                                <x-primary-button class="w-full">
                                    Ver Detalles
                                </x-primary-button>
                            </a>
                        </div>
                    </div>
                </div>
            </x-card>
            @empty
            <x-card>
                <x-empty-state
                    title="No hay eventos disponibles"
                    message="No se encontraron eventos que coincidan con tu búsqueda. Intenta con otros filtros."
                />
            </x-card>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $events->links() }}
        </div>
    </div>
</div>
@endsection
