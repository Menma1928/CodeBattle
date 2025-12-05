@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <x-page-title>Estadísticas y Ranking</x-page-title>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Evento: <span class="font-semibold text-purple-600 dark:text-purple-400">{{ $event->nombre }}</span></p>
            </div>
            <a href="{{ route('jury.event.projects', $event) }}">
                <x-secondary-button>
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver a Proyectos
                </x-secondary-button>
            </a>
        </div>

        <!-- Ranking -->
        <x-card>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Ranking de Proyectos</h3>

            @if($projects->count() > 0)
            <div class="space-y-4">
                @foreach($projects as $index => $project)
                <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <!-- Position Badge -->
                    <div class="flex-shrink-0">
                        <div class="w-14 h-14 flex items-center justify-center rounded-full font-bold text-lg shadow-md
                            @if($index === 0) bg-gradient-to-br from-yellow-400 to-yellow-500 text-yellow-900
                            @elseif($index === 1) bg-gradient-to-br from-gray-300 to-gray-400 text-gray-900
                            @elseif($index === 2) bg-gradient-to-br from-orange-400 to-orange-500 text-orange-900
                            @else bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 text-gray-700 dark:text-gray-300
                            @endif">
                            @if($index === 0)
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @elseif($index < 3)
                                {{ $index + 1 }}
                            @else
                                {{ $index + 1 }}
                            @endif
                        </div>
                    </div>

                    <!-- Project Info -->
                    <div class="flex-1 min-w-0">
                        <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                            <a href="{{ route('projects.show', $project) }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                                {{ $project->nombre }}
                            </a>
                        </h4>
                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <a href="{{ route('equipos.show', $project->team) }}" class="hover:text-purple-600 dark:hover:text-purple-400 font-medium">{{ $project->team->nombre }}</a>
                            </div>
                        </div>
                    </div>

                    <!-- Score -->
                    <div class="flex-shrink-0 text-right">
                        <div class="text-3xl font-bold bg-gradient-to-br from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                            {{ number_format($project->overall_average, 2) }}
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Promedio</p>
                    </div>

                    <!-- Quick Rate Button -->
                    <a href="{{ route('jury.rate.project', [$event, $project]) }}" class="flex-shrink-0">
                        <x-secondary-button>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </x-secondary-button>
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <x-empty-state
                title="No hay proyectos calificados"
                message="Aún no hay proyectos con calificaciones en este evento."
            />
            @endif
        </x-card>

        <!-- Detailed Ratings Table -->
        @if($projects->count() > 0 && $event->requirements->count() > 0)
        <x-card>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Detalles por Requisito</h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-900/50 z-10">
                                Proyecto
                            </th>
                            @foreach($event->requirements as $req)
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                <div class="flex flex-col items-center">
                                    <span class="mb-1">{{ $req->name }}</span>
                                    @if($req->description)
                                    <span class="text-xs font-normal text-gray-500 dark:text-gray-400 normal-case max-w-xs line-clamp-2">{{ $req->description }}</span>
                                    @endif
                                </div>
                            </th>
                            @endforeach
                            <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider bg-purple-50 dark:bg-purple-900/20">
                                Promedio
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($projects as $project)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800 z-10">
                                <div class="flex items-center gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white truncate max-w-xs">
                                            {{ $project->nombre }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $project->team->nombre }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            @foreach($event->requirements as $req)
                            @php
                                $rating = $project->requirements->where('id', $req->id)->first()?->pivot?->rating ?? 0;
                                $ratingColor = $rating >= 8 ? 'text-emerald-600 dark:text-emerald-400' : ($rating >= 6 ? 'text-yellow-600 dark:text-yellow-400' : ($rating > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500'));
                            @endphp
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="text-sm font-medium {{ $ratingColor }}">
                                    {{ $rating > 0 ? number_format($rating, 1) : '-' }}
                                </span>
                            </td>
                            @endforeach
                            <td class="px-6 py-4 whitespace-nowrap text-center bg-purple-50 dark:bg-purple-900/20">
                                <span class="text-base font-bold text-purple-600 dark:text-purple-400">
                                    {{ number_format($project->overall_average, 2) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Legend -->
            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Leyenda de colores:</p>
                <div class="flex flex-wrap gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-emerald-600"></div>
                        <span class="text-gray-600 dark:text-gray-400">8.0 - 10.0 (Excelente)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-yellow-600"></div>
                        <span class="text-gray-600 dark:text-gray-400">6.0 - 7.9 (Bueno)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-red-600"></div>
                        <span class="text-gray-600 dark:text-gray-400">0.1 - 5.9 (Necesita mejorar)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                        <span class="text-gray-600 dark:text-gray-400">Sin calificar</span>
                    </div>
                </div>
            </div>
        </x-card>
        @endif
    </div>
</div>
@endsection
