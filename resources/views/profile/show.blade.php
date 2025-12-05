<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Perfil de {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Información del usuario -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Información del Usuario
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Perfil público de {{ $user->name }}
                        </p>
                    </header>

                    <div class="mt-6 space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Email</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                        </div>

                        @if($user->direccion)
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Dirección</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->direccion }}</p>
                        </div>
                        @endif

                        @if($user->bio)
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Biografía</p>
                            <p class="mt-1 text-gray-900 dark:text-gray-100">{{ $user->bio }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Historial de equipos -->
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Historial de Equipos
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Equipos en los que {{ $user->name }} ha participado
                    </p>
                </header>

                @if($teams->count() > 0)
                <div class="mt-6 space-y-4">
                    @foreach($teams as $team)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    <a href="{{ route('equipos.show', $team) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                        {{ $team->nombre }}
                                    </a>
                                </h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                    Evento: <a href="{{ route('eventos.show', $team->event) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">{{ $team->event->nombre }}</a>
                                </p>
                            </div>
                            <div class="text-right">
                                @if($team->posicion)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    Posición #{{ $team->posicion }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="mt-3 flex items-center text-sm">
                            <span class="text-gray-700 dark:text-gray-300 font-medium">Rol:</span>
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($team->user_role === 'lider') bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200
                                @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                @endif">
                                {{ ucfirst($team->user_role) }}
                            </span>
                        </div>

                        @if($team->descripcion)
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            {{ Str::limit($team->descripcion, 150) }}
                        </p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <p class="mt-6 text-sm text-gray-600 dark:text-gray-400">
                    Este usuario no ha participado en ningún equipo aún.
                </p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
