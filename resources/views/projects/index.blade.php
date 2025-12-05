<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Proyectos
            </h2>
        </div>
    </x-slot>

    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Search Form -->
            <x-card class="mb-6">
                <form method="GET" action="{{ route('projects.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <x-text-input
                            name="search"
                            type="text"
                            class="w-full"
                            placeholder="Buscar proyectos por nombre, descripción, equipo o evento..."
                            :value="request('search')"
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
                        <a href="{{ route('projects.index') }}">
                            <x-secondary-button type="button">
                                Limpiar
                            </x-secondary-button>
                        </a>
                        @endif
                    </div>
                </form>
            </x-card>

            <!-- Projects Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($projects as $project)
                <x-card hover="true" class="flex flex-col h-full group">
                    <div class="flex-1">
                        <a href="{{ route('projects.show', $project) }}" class="block">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-purple-600 transition-colors">
                                {{ $project->nombre }}
                            </h3>
                        </a>

                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                            {{ $project->descripcion }}
                        </p>

                        <div class="space-y-2 text-sm mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">
                                    <a href="{{ route('equipos.show', $project->team) }}" class="text-purple-600 dark:text-purple-400 hover:underline font-semibold">
                                        {{ $project->team->nombre }}
                                    </a>
                                </span>
                            </div>

                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">
                                    <a href="{{ route('eventos.show', $project->team->event) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">
                                        {{ $project->team->event->nombre }}
                                    </a>
                                </span>
                            </div>

                            @if($project->github_url)
                            <div class="pt-2">
                                <a href="{{ $project->github_url }}" target="_blank" class="inline-flex items-center gap-2 text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                    <span class="text-sm font-medium">Ver en GitHub</span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('projects.show', $project) }}">
                            <x-primary-button class="w-full justify-center">
                                Ver Detalles
                            </x-primary-button>
                        </a>
                    </div>
                </x-card>
                @empty
                <div class="col-span-full">
                    <x-card>
                        <x-empty-state
                            title="No hay proyectos"
                            message="No se encontraron proyectos que coincidan con tu búsqueda."
                        />
                    </x-card>
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
