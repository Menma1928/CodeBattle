<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gestionar Jurados - {{ $evento->nombre }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Jurados asignados -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">
                        Jurados Asignados ({{ $evento->juries->count() }}/3)
                    </h3>

                    @if($evento->juries->count() > 0)
                    <div class="space-y-3">
                        @foreach($evento->juries as $jury)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr($jury->name, 0, 1)) }}
                                    </div>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $jury->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $jury->email }}</p>
                                </div>
                            </div>
                            <form action="{{ route('eventos.removeJury', [$evento, $jury]) }}" method="POST" onsubmit="return confirm('¿Estás seguro de remover a este jurado?')">
                                @csrf
                                @method('DELETE')
                                <x-danger-button type="submit">
                                    Remover
                                </x-danger-button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-gray-500 dark:text-gray-400">No hay jurados asignados aún.</p>
                    @endif
                </div>
            </div>

            <!-- Asignar nuevo jurado -->
            @if($evento->juries->count() < 3)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                        Asignar Nuevo Jurado
                    </h3>

                    @if($availableUsers->count() > 0)
                    <form action="{{ route('eventos.assignJury', $evento) }}" method="POST">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="user_id" value="Seleccionar Usuario" />
                            <select id="user_id" name="user_id" required class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">
                                <option value="">-- Selecciona un usuario --</option>
                                @foreach($availableUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                        </div>

                        <x-primary-button>
                            Asignar Jurado
                        </x-primary-button>
                    </form>
                    @else
                    <p class="text-gray-500 dark:text-gray-400">No hay usuarios disponibles para asignar como jurados.</p>
                    @endif
                </div>
            </div>
            @else
            <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <p class="text-yellow-800 dark:text-yellow-200">
                    ⚠️ Ya se alcanzó el máximo de 3 jurados para este evento.
                </p>
            </div>
            @endif

            <div class="flex justify-end">
                <a href="{{ route('eventos.show', $evento) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    Volver al Evento
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
