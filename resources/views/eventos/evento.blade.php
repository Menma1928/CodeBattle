@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Header with Back Button -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('eventos.index') }}" class="text-white hover:text-purple-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-white">{{ $evento->nombre }}</h1>
            </div>
        </div>
    </div>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Event Information Card -->
            <x-card class="mb-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Event Logo/Image -->
                    <div class="flex-shrink-0">
                        @if($evento->url_imagen)
                            <img src="{{ $evento->url_imagen }}" alt="{{ $evento->nombre }}" class="w-32 h-32 rounded-xl object-cover shadow-md">
                        @else
                            <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center text-white text-5xl font-bold shadow-md">
                                {{ substr($evento->nombre ?? 'E', 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- Event Details -->
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                                    {{ $evento->nombre }}
                                </h2>
                                @php
                                    $badgeType = $evento->estado === 'activo' ? 'success' : ($evento->estado === 'finalizado' ? 'error' : 'warning');
                                @endphp
                                <x-badge :type="$badgeType" size="lg">
                                    {{ ucfirst($evento->estado) }}
                                </x-badge>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de inicio</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $evento->fecha_inicio->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>

                            @if($evento->fecha_fin)
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de fin</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $evento->fecha_fin->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @endif

                            @if($evento->direccion)
                            <div class="flex items-start gap-3 sm:col-span-2">
                                <div class="flex-shrink-0 w-10 h-10 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ubicación</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">{{ $evento->direccion }}</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        @if($evento->descripcion)
                        <div class="mt-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Descripción</p>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $evento->descripcion }}</p>
                        </div>
                        @endif

                        @if($user_is_admin)
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a href="{{ route('eventos.manageJuries', $evento) }}">
                                <x-secondary-button>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    Gestionar Jurados ({{ $evento->juries->count() }}/3)
                                </x-secondary-button>
                            </a>
                            <a href="{{ route('eventos.dashboard', $evento) }}">
                                <x-secondary-button>
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    Panel de Administración
                                </x-secondary-button>
                            </a>
                        </div>
                        @endif
                        
                        @if($user_team == null && !$user_is_admin && $evento->estado !== 'finalizado' && auth()->user()->hasrole('Participante'))
                        <div class="mt-4 p-4 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-lg border border-purple-200 dark:border-purple-800">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-purple-900 dark:text-purple-100 mb-1">¿No tienes equipo?</p>
                                    <p class="text-sm text-purple-700 dark:text-purple-300">Crea tu equipo para participar en este evento</p>
                                </div>
                                <a href="{{ route('equipos.create', ['event_id' => $evento->id]) }}">
                                    <x-primary-button>
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Crear Equipo
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>
                        
                        @endif
                    </div>
                </div>
            </x-card>

            <!-- User's Team Section -->
            @if($user_team != null)
            <x-card class="mb-6 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-purple-200 dark:border-purple-800">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Tu Equipo para Este Evento</h2>
                    <a href="{{ route('equipos.show', $user_team) }}">
                        <x-primary-button>
                            Ver Equipo Completo
                        </x-primary-button>
                    </a>
                </div>

                <!-- Team Name and Description -->
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-purple-700 dark:text-purple-300 mb-2">{{ $user_team->nombre }}</h3>
                    @if($user_team->descripcion)
                    <p class="text-gray-600 dark:text-gray-400">{{ $user_team->descripcion }}</p>
                    @endif
                </div>

                <!-- Team Members List -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Integrantes ({{ $user_team->users->count() }}/5)
                    </h4>

                    <div class="space-y-3">
                        @foreach($user_team->users as $member)
                        <div class="flex items-center gap-4 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <x-avatar :name="$member->name" size="lg" />

                            <div class="flex-1">
                                <div class="font-semibold text-gray-900 dark:text-white">
                                    {{ $member->name }}
                                    @if($member->id == auth()->id())
                                    <span class="text-purple-600 dark:text-purple-400">(Tú)</span>
                                    @endif
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ $member->email }}</div>
                            </div>

                            <x-badge type="purple">
                                {{ ucfirst($member->pivot->rol ?? 'Miembro') }}
                            </x-badge>
                        </div>
                        @endforeach
                    </div>
                </div>
            </x-card>
            @endif

            <!-- Registered Teams Section -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Equipos Registrados</h2>

                <div class="space-y-4">
                    @forelse($teams as $team)
                    <x-card hover="true" class="group">
                        <div class="flex flex-col md:flex-row gap-6">
                            <!-- Team Banner -->
                            <a href="{{ route('equipos.show', $team) }}" class="flex-shrink-0">
                                @if($team->url_banner)
                                    <img src="{{ $team->url_banner }}" alt="{{ $team->nombre }}" class="w-28 h-28 rounded-xl object-cover group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <div class="w-28 h-28 bg-gradient-to-br from-pink-500 to-purple-500 rounded-xl flex items-center justify-center text-white text-4xl font-bold group-hover:scale-105 transition-transform duration-200">
                                        {{ substr($team->nombre, 0, 1) }}
                                    </div>
                                @endif
                            </a>

                            <!-- Team Information -->
                            <div class="flex-1 min-w-0">
                                <a href="{{ route('equipos.show', $team) }}" class="block group-hover:text-purple-600 transition-colors">
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                        {{ $team->nombre }}
                                    </h3>
                                </a>

                                @if($team->posicion)
                                <div class="mb-2">
                                    <x-badge type="warning" size="lg">
                                        Posición #{{ $team->posicion }}
                                    </x-badge>
                                </div>
                                @endif

                                <p class="text-gray-700 dark:text-gray-300 line-clamp-2">
                                    {{ $team->descripcion ?? 'Sin descripción disponible.' }}
                                </p>

                                <!-- Action Buttons (Mobile) -->
                                <div class="flex md:hidden gap-2 mt-4 flex-wrap">
                                    @can('editar equipos')
                                    <a href="{{ route('equipos.edit', $team) }}">
                                        <x-secondary-button>
                                            Editar
                                        </x-secondary-button>
                                    </a>
                                    @endcan

                                    @can('unirse equipos')
                                    @if(!auth()->user()->hasRole('Super Admin'))
                                    <button onclick="alert('Funcionalidad en desarrollo')" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                                        Solicitar Unirme
                                    </button>
                                    @endif
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

                                    <a href="{{ route('equipos.show', $team) }}">
                                        <x-primary-button>
                                            Ver Detalles
                                        </x-primary-button>
                                    </a>
                                </div>
                            </div>

                            <!-- Action Buttons (Desktop) -->
                            <div class="hidden md:flex flex-col gap-2 flex-shrink-0">
                                @can('editar equipos')
                                <a href="{{ route('equipos.edit', $team) }}">
                                    <x-secondary-button>
                                        Editar Equipo
                                    </x-secondary-button>
                                </a>
                                @endcan

                                @can('unirse equipos')
                                @if(!auth()->user()->hasRole('Super Admin'))
                                <button onclick="alert('Funcionalidad en desarrollo')" class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 transition">
                                    Solicitar Unirme
                                </button>
                                @endif
                                @endcan

                                @can('eliminar equipos')
                                <form method="POST" action="{{ route('equipos.destroy', $team) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este equipo?');">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button type="submit" class="w-full">
                                        Eliminar Equipo
                                    </x-danger-button>
                                </form>
                                @endcan

                                <a href="{{ route('equipos.show', $team) }}">
                                    <x-primary-button class="w-full">
                                        Ver Detalles
                                    </x-primary-button>
                                </a>
                            </div>
                        </div>
                    </x-card>
                    @empty
                    <x-card>
                        <x-empty-state
                            title="No hay equipos registrados"
                            message="Aún no hay equipos inscritos en este evento."
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
    </div>
</div>
@endsection
