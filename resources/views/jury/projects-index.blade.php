<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Calificar Proyectos - {{ $event->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-4">
                <p class="text-indigo-800 dark:text-indigo-200">
                    📋 Como jurado, debes calificar cada requisito de cada proyecto con una puntuación del 1 al 10.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects as $project)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            {{ $project->nombre }}
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            Equipo: {{ $project->team->nombre }}
                        </p>

                        @if($project->jury_has_rated)
                        <div class="mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                ✓ Calificado
                            </span>
                        </div>
                        @endif

                        <div class="flex gap-2">
                            <a href="{{ route('jury.rate.project', [$event, $project]) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                {{ $project->jury_has_rated ? 'Editar Calificación' : 'Calificar' }}
                            </a>
                            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">
                                Ver
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No hay proyectos para calificar en este evento.</p>
                </div>
                @endforelse
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('jury.event.statistics', $event) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                    Ver Estadísticas
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
