<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Estadísticas y Ranking - {{ $event->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Ranking -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                        Ranking de Proyectos
                    </h3>

                    @if($projects->count() > 0)
                    <div class="space-y-4">
                        @foreach($projects as $index => $project)
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-full
                                @if($index === 0) bg-yellow-400 text-yellow-900
                                @elseif($index === 1) bg-gray-300 text-gray-900
                                @elseif($index === 2) bg-orange-400 text-orange-900
                                @else bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300
                                @endif font-bold text-lg">
                                {{ $index + 1 }}
                            </div>

                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $project->nombre }}
                                    </a>
                                </h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Equipo: {{ $project->team->nombre }}
                                </p>
                            </div>

                            <div class="text-right">
                                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ number_format($project->overall_average, 2) }}
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Promedio</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 dark:text-gray-400">No hay proyectos calificados aún.</p>
                    @endif
                </div>
            </div>

            <!-- Detailed Ratings -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                        Detalles por Requisito
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Proyecto</th>
                                    @foreach($event->requirements as $req)
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ $req->name }}</th>
                                    @endforeach
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Promedio</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($projects as $project)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $project->nombre }}
                                    </td>
                                    @foreach($event->requirements as $req)
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ number_format($project->requirements->where('id', $req->id)->first()?->pivot?->rating ?? 0, 2) }}
                                    </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-indigo-600 dark:text-indigo-400">
                                        {{ number_format($project->overall_average, 2) }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
