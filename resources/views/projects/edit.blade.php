<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Proyecto - {{ $project->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('projects.update', $project) }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <x-input-label for="nombre" value="Nombre del Proyecto" />
                            <x-text-input id="nombre" name="nombre" type="text" class="mt-1 block w-full" :value="old('nombre', $project->nombre)" required autofocus />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="descripcion" value="Descripción" />
                            <textarea id="descripcion" name="descripcion" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" required>{{ old('descripcion', $project->descripcion) }}</textarea>
                            <x-input-error :messages="$errors->get('descripcion')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="github_url" value="URL de GitHub" />
                            <x-text-input id="github_url" name="github_url" type="url" class="mt-1 block w-full" :value="old('github_url', $project->github_url)" placeholder="https://github.com/usuario/repositorio" {{ !$eventIsActive ? 'readonly' : '' }} />
                            <x-input-error :messages="$errors->get('github_url')" class="mt-2" />
                            @if(!$eventIsActive)
                            <p class="mt-2 text-sm text-yellow-600 dark:text-yellow-400">
                                ⚠️ El evento no está activo. No puedes modificar la URL de GitHub.
                            </p>
                            @endif
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-6">
                            <a href="{{ route('projects.show', $project) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                Cancelar
                            </a>
                            <x-primary-button>
                                Actualizar Proyecto
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
