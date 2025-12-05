<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Proyectos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search Form -->
            <div class="mb-6">
                <form method="GET" action="{{ route('projects.index') }}" class="flex gap-4">
                    <div class="flex-1">
                        <x-text-input
                            name="search"
                            type="text"
                            class="w-full"
                            placeholder="Buscar proyectos..."
                            :value="request('search')"
                        />
                    </div>
                    <x-primary-button type="submit">Buscar</x-primary-button>
                    @if(request('search'))
                    <a href="{{ route('projects.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">
                        Limpiar
                    </a>
                    @endif
                </form>
            </div>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects as $project)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            <a href="{{ route('projects.show', $project) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                {{ $project->nombre }}
                            </a>
                        </h3>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            {{ Str::limit($project->descripcion, 100) }}
                        </p>

                        <div class="space-y-2 text-sm">
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Equipo:</span>
                                <a href="{{ route('equipos.show', $project->team) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $project->team->nombre }}
                                </a>
                            </p>

                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Evento:</span>
                                <a href="{{ route('eventos.show', $project->team->event) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $project->team->event->nombre }}
                                </a>
                            </p>

                            @if($project->github_url)
                            <p class="text-gray-700 dark:text-gray-300">
                                <a href="{{ $project->github_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                    Ver en GitHub
                                </a>
                            </p>
                            @endif
                        </div>

                        <div class="mt-4">
                            <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium">
                                Ver detalles →
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 dark:text-gray-400">No se encontraron proyectos.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $projects->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
