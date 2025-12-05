<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $project->nombre }}
            </h2>
            @if($isLeader)
            <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white">
                Editar
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Project Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Información del Proyecto</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Descripción</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->descripcion }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Equipo</p>
                            <p class="mt-1">
                                <a href="{{ route('equipos.show', $project->team) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $project->team->nombre }}
                                </a>
                            </p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Evento</p>
                            <p class="mt-1">
                                <a href="{{ route('eventos.show', $project->team->event) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ $project->team->event->nombre }}
                                </a>
                            </p>
                        </div>

                        @if($project->github_url)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Repositorio GitHub</p>
                            <p class="mt-1">
                                <a href="{{ $project->github_url }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline inline-flex items-center gap-1">
                                    {{ $project->github_url }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            </p>
                        </div>
                        @endif

                        @if($project->fecha_subida)
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de creación</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $project->fecha_subida->format('d/m/Y H:i') }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Miembros del Equipo</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($project->team->users as $member)
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $member->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($member->pivot->rol) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
