@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-amber-500 via-yellow-500 to-amber-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('eventos.index') }}" class="text-white hover:text-amber-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-white flex items-center gap-3">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ $evento->nombre }}
                    </h1>
                    <p class="text-amber-100 mt-1">Evento Finalizado - Resultados Oficiales</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-6 py-3 rounded-lg">
                    <p class="text-white text-sm font-semibold">Estado</p>
                    <p class="text-amber-100 text-xl font-bold">FINALIZADO</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- Event Information -->
            <x-card class="mb-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Información del Evento</h2>
                        <div class="space-y-2 text-gray-700 dark:text-gray-300">
                            <p><strong>Fecha de inicio:</strong> {{ $evento->fecha_inicio->format('d/m/Y H:i') }}</p>
                            <p><strong>Fecha de fin:</strong> {{ $evento->fecha_fin->format('d/m/Y H:i') }}</p>
                            @if($evento->direccion)
                            <p><strong>Ubicación:</strong> {{ $evento->direccion }}</p>
                            @endif
                        </div>
                    </div>
                    @if($evento->descripcion)
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Descripción</h2>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $evento->descripcion }}</p>
                    </div>
                    @endif
                </div>
            </x-card>

            <!-- Winners Podium -->
            @if($teams->count() >= 3)
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6 text-center">
                    🏆 Podio de Ganadores 🏆
                </h2>

                <div class="grid md:grid-cols-3 gap-6 items-end">
                    <!-- 2nd Place -->
                    @if($teams->count() >= 2)
                    <div class="order-2 md:order-1">
                        <x-card class="bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 border-4 border-gray-400">
                            <div class="text-center">
                                <div class="text-6xl mb-3">🥈</div>
                                <div class="text-5xl font-bold text-gray-600 dark:text-gray-300 mb-2">2°</div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $teams[1]->nombre }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Líder: {{ $teams[1]->leader_name }}</p>
                                <div class="bg-white dark:bg-gray-900 rounded-lg p-3">
                                    <p class="text-3xl font-bold text-gray-700 dark:text-gray-300">{{ $teams[1]->average_rating }}</p>
                                    <p class="text-xs text-gray-500">Calificación</p>
                                </div>
                            </div>
                        </x-card>
                    </div>
                    @endif

                    <!-- 1st Place -->
                    <div class="order-1 md:order-2">
                        <x-card class="bg-gradient-to-br from-yellow-100 to-amber-200 dark:from-yellow-800 dark:to-amber-900 border-4 border-yellow-500 transform md:scale-110">
                            <div class="text-center">
                                <div class="text-7xl mb-3 animate-bounce">🏆</div>
                                <div class="text-6xl font-bold text-yellow-600 dark:text-yellow-300 mb-2">1°</div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">{{ $teams[0]->nombre }}</h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">Líder: {{ $teams[0]->leader_name }}</p>
                                <div class="bg-white dark:bg-gray-900 rounded-lg p-4">
                                    <p class="text-4xl font-bold text-yellow-600 dark:text-yellow-400">{{ $teams[0]->average_rating }}</p>
                                    <p class="text-xs text-gray-500">Calificación</p>
                                </div>
                            </div>
                        </x-card>
                    </div>

                    <!-- 3rd Place -->
                    @if($teams->count() >= 3)
                    <div class="order-3">
                        <x-card class="bg-gradient-to-br from-orange-100 to-orange-200 dark:from-orange-800 dark:to-orange-900 border-4 border-orange-500">
                            <div class="text-center">
                                <div class="text-6xl mb-3">🥉</div>
                                <div class="text-5xl font-bold text-orange-600 dark:text-orange-300 mb-2">3°</div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $teams[2]->nombre }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Líder: {{ $teams[2]->leader_name }}</p>
                                <div class="bg-white dark:bg-gray-900 rounded-lg p-3">
                                    <p class="text-3xl font-bold text-orange-700 dark:text-orange-300">{{ $teams[2]->average_rating }}</p>
                                    <p class="text-xs text-gray-500">Calificación</p>
                                </div>
                            </div>
                        </x-card>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Complete Rankings Table -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    📊 Clasificación Completa
                </h2>

                <x-card :padding="false">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Posición</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Equipo</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Líder</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Calificación</th>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($teams as $team)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            @if($team->posicion == 1)
                                            <span class="text-3xl">🥇</span>
                                            @elseif($team->posicion == 2)
                                            <span class="text-3xl">🥈</span>
                                            @elseif($team->posicion == 3)
                                            <span class="text-3xl">🥉</span>
                                            @else
                                            <span class="text-2xl font-bold text-gray-600 dark:text-gray-400">{{ $team->posicion }}°</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $team->nombre }}</div>
                                        @if($team->descripcion)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($team->descripcion, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $team->leader_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400 mr-2">{{ $team->average_rating }}</span>
                                            <span class="text-sm text-gray-500">/ 10</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('equipos.show', $team) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 font-medium">
                                            Ver Equipo →
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="text-gray-500 dark:text-gray-400">
                                            <p class="text-lg font-medium">No hay equipos clasificados</p>
                                            <p class="text-sm mt-2">El administrador aún no ha asignado posiciones.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-card>
            </div>

            <!-- Admin/Jury Actions -->
            @if($user_is_admin || $user_is_jury)
            <div class="flex justify-center gap-4 mb-6">
                @if($user_is_admin)
                <a href="{{ route('eventos.dashboard', $evento) }}">
                    <x-primary-button class="px-8">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Panel de Administración
                    </x-primary-button>
                </a>
                @endif

                @if($user_is_jury)
                <a href="{{ route('jury.event.statistics', $evento) }}">
                    <x-secondary-button class="px-8">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Ver Estadísticas
                    </x-secondary-button>
                </a>
                @endif
            </div>
            @endif

            <!-- Certificate Download Button -->
            @if($user_team)
            <div class="flex justify-center">
                <a href="{{ route('eventos.certificate', $evento) }}">
                    <x-primary-button class="px-8">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Descargar Constancia de Participación
                    </x-primary-button>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
