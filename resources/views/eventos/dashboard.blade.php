@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('eventos.show', $evento) }}" class="text-white hover:text-indigo-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-white">Panel de Administración</h1>
                    <p class="text-indigo-200 mt-1">{{ $evento->nombre }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                    <p class="text-white text-sm">Estado</p>
                    <p class="text-indigo-100 font-bold">{{ strtoupper($evento->estado) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <!-- Statistics Overview -->
            <div class="grid md:grid-cols-4 gap-6 mb-8">
                <x-card>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Equipos</p>
                        <p class="text-4xl font-bold text-purple-600 dark:text-purple-400">{{ $teams->count() }}</p>
                    </div>
                </x-card>

                <x-card>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Jurados</p>
                        <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">{{ $evento->juries->count() }}</p>
                    </div>
                </x-card>

                <x-card>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Requisitos</p>
                        <p class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $evento->requirements->count() }}</p>
                    </div>
                </x-card>

                <x-card>
                    <div class="text-center">
                        <p class="text-gray-500 dark:text-gray-400 text-sm">Proyectos Completos</p>
                        <p class="text-4xl font-bold text-emerald-600 dark:text-emerald-400">{{ $teams->where('all_rated', true)->count() }}</p>
                    </div>
                </x-card>
            </div>

            <!-- Action Buttons -->
            <div class="mb-8 flex flex-wrap justify-center gap-4">
                <a href="{{ route('eventos.show', $evento) }}">
                    <x-secondary-button class="px-8">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Volver al Evento
                    </x-secondary-button>
                </a>

                <a href="{{ route('eventos.manageJuries', $evento) }}">
                    <x-secondary-button class="px-8">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Gestionar Jurados
                    </x-secondary-button>
                </a>

                <!-- Reportes Dropdown -->
                <div class="relative inline-block text-left" x-data="{ open: false }">
                    <button @click="open = !open" type="button" class="inline-flex items-center px-8 py-2 bg-gradient-to-r from-green-600 to-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:from-green-700 hover:to-emerald-700 active:from-green-900 active:to-emerald-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Descargar Reporte
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10">
                        <div class="py-1">
                            <a href="{{ route('eventos.report.pdf', $evento) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Reporte PDF
                            </a>
                            <a href="{{ route('eventos.report.excel', $evento) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Reporte Excel
                            </a>
                            <a href="{{ route('eventos.certificates.all', $evento) }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>
                                </svg>
                                Constancias (ZIP)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teams and Ratings Table -->
            <form method="POST" action="{{ route('eventos.assignPositions', $evento) }}">
                @csrf
                <x-card :padding="false" class="mb-8">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Calificaciones y Posiciones</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Asigna las posiciones finales basándote en las calificaciones promedio.</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Equipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Líder</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cal. Promedio</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Estado</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Posición</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($teams as $index => $team)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $team->nombre }}</div>
                                        @if($team->descripcion)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($team->descripcion, 40) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-900 dark:text-white">{{ $team->leader_name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $team->average_rating }}</span>
                                            <span class="text-sm text-gray-500 ml-1">/ 10</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($team->all_rated)
                                        <x-badge type="success">Completo</x-badge>
                                        @else
                                        <x-badge type="warning">Pendiente</x-badge>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <input
                                            type="number"
                                            name="positions[{{ $team->id }}]"
                                            value="{{ $team->posicion ?? ($index + 1) }}"
                                            min="1"
                                            class="w-20 px-3 py-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-white rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                        >
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <a href="{{ route('equipos.show', $team) }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 text-sm font-medium">
                                                Ver →
                                            </a>
                                            @if($team->project)
                                            <a href="{{ route('projects.show', $team->project) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 text-sm font-medium">
                                                Proyecto →
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        No hay equipos en este evento.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($teams->count() > 0)
                    <div class="p-6 bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex justify-end gap-4">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Guardar Posiciones
                            </button>
                        </div>
                    </div>
                    @endif
                </x-card>
            </form>

            <!-- Detailed Ratings by Jury -->
            @if($teams->count() > 0 && $evento->requirements->count() > 0)
            <x-card :padding="false">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Calificaciones Detalladas por Jurado</h2>
                </div>

                <div class="p-6">
                    @foreach($teams as $team)
                    @if($team->project)
                    <div class="mb-8 last:mb-0">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">{{ $team->nombre }}</h3>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-800">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-gray-700 dark:text-gray-300">Requisito</th>
                                        @foreach($evento->juries as $jury)
                                        <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-300">{{ $jury->name }}</th>
                                        @endforeach
                                        <th class="px-4 py-2 text-center text-gray-700 dark:text-gray-300 font-bold">Promedio</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($evento->requirements as $requirement)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                        <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $requirement->name }}</td>
                                        @foreach($evento->juries as $jury)
                                        @php
                                            $rating = $team->project->juryRatings
                                                ->where('requirement_id', $requirement->id)
                                                ->where('jury_id', $jury->id)
                                                ->first();
                                        @endphp
                                        <td class="px-4 py-3 text-center">
                                            @if($rating)
                                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $rating->rating >= 8 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($rating->rating >= 6 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }} font-bold">
                                                {{ $rating->rating }}
                                            </span>
                                            @else
                                            <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        @endforeach
                                        <td class="px-4 py-3 text-center">
                                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 font-bold">
                                                {{ $team->project->getRequirementAverage($requirement->id) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </x-card>
            @endif
        </div>
    </div>
</div>
@endsection
