@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <x-page-title>{{ $title ?? 'Equipos' }}</x-page-title>
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

        <!-- Search Bar -->
        <x-card class="mb-6">
            <form method="GET" action="{{ isset($isMyTeams) && $isMyTeams ? route('equipos.myTeams') : route('equipos.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <x-text-input
                        name="search"
                        type="text"
                        class="w-full"
                        placeholder="Buscar equipos por nombre, descripción o evento..."
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
                    @if(request('search'))
                    <a href="{{ isset($isMyTeams) && $isMyTeams ? route('equipos.myTeams') : route('equipos.index') }}">
                        <x-secondary-button type="button">
                            Limpiar
                        </x-secondary-button>
                    </a>
                    @endif
                </div>
            </form>
        </x-card>

        <!-- Teams List -->
        <div class="space-y-4">
            @forelse($teams as $team)
            <x-card hover="true" class="group">
                <div class="flex flex-col sm:flex-row gap-6">
                    <!-- Team Banner/Logo -->
                    <a href="{{ route('equipos.show', $team) }}" class="flex-shrink-0">
                        @if($team->url_banner)
                            @php
                                $bannerUrl = str_starts_with($team->url_banner, 'http')
                                    ? $team->url_banner
                                    : asset('storage/' . $team->url_banner);
                            @endphp
                            <img src="{{ $bannerUrl }}" alt="{{ $team->nombre }}" class="w-24 h-24 rounded-xl object-cover group-hover:scale-105 transition-transform duration-200">
                        @else
                            <div class="w-24 h-24 bg-gradient-to-br from-pink-500 to-purple-500 rounded-xl flex items-center justify-center text-white text-3xl font-bold group-hover:scale-105 transition-transform duration-200">
                                {{ substr($team->nombre, 0, 1) }}
                            </div>
                        @endif
                    </a>

                    <!-- Team Information -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4 mb-3">
                            <div class="flex-1">
                                <a href="{{ route('equipos.show', $team) }}" class="block group-hover:text-purple-600 transition-colors">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $team->nombre }}
                                    </h3>
                                </a>
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        <strong>Evento:</strong>
                                        <span class="text-purple-600 dark:text-purple-400 font-semibold">{{ $team->event->nombre }}</span>
                                    </span>
                                    @if($team->posicion)
                                        <x-badge type="warning">
                                            #{{ $team->posicion }}
                                        </x-badge>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons (Desktop) -->
                            <div class="hidden sm:flex gap-2 flex-shrink-0">
                                @can('editar equipos')
                                <a href="{{ route('equipos.edit', $team) }}">
                                    <x-secondary-button>
                                        Editar
                                    </x-secondary-button>
                                </a>
                                @endcan
                                @can('eliminar equipos')
                                <form method="POST" action="{{ route('equipos.destroy', $team) }}" class="inline" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este equipo?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit">
                                        Eliminar
                                    </x-danger-button>
                                </form>
                                @endcan
                                @can('unirse equipos')
                                @if($title != 'Mis Equipos' && !auth()->user()->hasRole('Super Admin'))
                                <button onclick="alert('Funcionalidad en desarrollo')" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                                    Solicitar Unirme
                                </button>
                                @endif
                                @endcan
                                <a href="{{ route('equipos.show', $team) }}">
                                    <x-primary-button>
                                        Ver Detalles
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>

                        <p class="text-gray-700 dark:text-gray-300 text-sm line-clamp-2">
                            {{ $team->descripcion ?? 'Sin descripción disponible.' }}
                        </p>

                        <!-- Action Buttons (Mobile) -->
                        <div class="flex sm:hidden gap-2 mt-4 flex-wrap">
                            @can('editar equipos')
                            <a href="{{ route('equipos.edit', $team) }}">
                                <x-secondary-button class="w-full">
                                    Editar
                                </x-secondary-button>
                            </a>
                            @endcan
                            @can('eliminar equipos')
                            <form method="POST" action="{{ route('equipos.destroy', $team) }}" class="flex-1" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este equipo?');">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit" class="w-full">
                                    Eliminar
                                </x-danger-button>
                            </form>
                            @endcan
                            @can('unirse equipos')
                            @if($title != 'Mis Equipos' && !auth()->user()->hasRole('Super Admin'))
                            <button onclick="alert('Funcionalidad en desarrollo')" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                                Solicitar Unirme
                            </button>
                            @endif
                            @endcan
                            <a href="{{ route('equipos.show', $team) }}" class="flex-1">
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
                    title="No hay equipos disponibles"
                    message="No se encontraron equipos que coincidan con tu búsqueda."
                />
            </x-card>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $teams->links() }}
        </div>
    </div>
</div>
@endsection