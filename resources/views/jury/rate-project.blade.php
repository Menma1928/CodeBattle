<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Calificar: {{ $project->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $project->nombre }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->descripcion }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Equipo: {{ $project->team->nombre }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('jury.store.ratings', [$event, $project]) }}">
                @csrf

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">
                            Calificar Requisitos (1-10)
                        </h3>

                        <div class="space-y-6">
                            @foreach($event->requirements as $requirement)
                            <div class="border-b border-gray-200 dark:border-gray-700 pb-6 last:border-0">
                                <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $requirement->name }}
                                </label>
                                @if($requirement->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $requirement->description }}</p>
                                @endif

                                <div class="flex items-center gap-4">
                                    <input
                                        type="range"
                                        name="rating_{{ $requirement->id }}"
                                        id="rating_{{ $requirement->id }}"
                                        min="1"
                                        max="10"
                                        value="{{ $existingRatings->get($requirement->id)?->rating ?? 5 }}"
                                        class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                                        oninput="document.getElementById('value_{{ $requirement->id }}').textContent = this.value"
                                        required
                                    >
                                    <span id="value_{{ $requirement->id }}" class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 w-12 text-center">
                                        {{ $existingRatings->get($requirement->id)?->rating ?? 5 }}
                                    </span>
                                </div>
                                <x-input-error :messages="$errors->get('rating_' . $requirement->id)" class="mt-2" />
                            </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end gap-4 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('jury.event.projects', $event) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                Cancelar
                            </a>
                            <x-primary-button>
                                Guardar Calificaciones
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
