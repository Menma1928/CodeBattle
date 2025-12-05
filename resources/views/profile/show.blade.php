@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4">
                <x-avatar :name="$user->name" size="2xl" />
                <div>
                    <x-page-title>{{ $user->name }}</x-page-title>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- User Information -->
                <x-card>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Información del Usuario</h3>

                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Nombre</p>
                            <p class="text-base text-gray-900 dark:text-white font-semibold">{{ $user->name }}</p>
                        </div>

                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Correo Electrónico</p>
                            <p class="text-base text-gray-900 dark:text-white font-semibold">{{ $user->email }}</p>
                        </div>

                        @if($user->direccion)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Dirección</p>
                            <p class="text-base text-gray-900 dark:text-white">{{ $user->direccion }}</p>
                        </div>
                        @endif

                        @if($user->bio)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Biografía</p>
                            <p class="text-base text-gray-900 dark:text-white leading-relaxed">{{ $user->bio }}</p>
                        </div>
                        @endif
                    </div>

                    @if(auth()->id() === $user->id)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('profile.edit') }}">
                            <x-secondary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Editar Perfil
                            </x-secondary-button>
                        </a>
                    </div>
                    @endif
                </x-card>

                <!-- Team History -->
                <x-card>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Historial de Equipos</h3>

                    @if($teams->count() > 0)
                    <div class="space-y-4">
                        @foreach($teams as $team)
                        <div class="p-4 border border-gray-200 dark:border-gray-700 rounded-xl hover:border-purple-300 dark:hover:border-purple-700 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                        <a href="{{ route('equipos.show', $team) }}" class="hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                                            {{ $team->nombre }}
                                        </a>
                                    </h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Evento: <a href="{{ route('eventos.show', $team->event) }}" class="text-purple-600 dark:text-purple-400 hover:underline font-medium">{{ $team->event->nombre }}</a>
                                    </p>
                                </div>

                                <div class="flex flex-wrap items-center gap-2">
                                    <x-badge
                                        :type="$team->user_role === 'lider' ? 'success' : 'purple'"
                                        size="lg"
                                    >
                                        {{ ucfirst($team->user_role) }}
                                    </x-badge>

                                    @if($team->posicion)
                                    <x-badge type="warning" size="lg">
                                        #{{ $team->posicion }}
                                    </x-badge>
                                    @endif
                                </div>
                            </div>

                            @if($team->descripcion)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                {{ $team->descripcion }}
                            </p>
                            @endif
                        </div>
                        @endforeach
                    </div>
                    @else
                    <x-empty-state
                        title="Sin historial de equipos"
                        message="{{ $user->name }} no ha participado en ningún equipo aún."
                    />
                    @endif
                </x-card>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Stats Card -->
                <x-card class="bg-gradient-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-purple-200 dark:border-purple-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Estadísticas</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Equipos</span>
                            </div>
                            <span class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $teams->count() }}</span>
                        </div>

                        @php
                            $liderCount = $teams->where('user_role', 'lider')->count();
                        @endphp
                        @if($liderCount > 0)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Líder de</span>
                            </div>
                            <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $liderCount }}</span>
                        </div>
                        @endif

                        @php
                            $topPositions = $teams->whereNotNull('posicion')->where('posicion', '<=', 3)->count();
                        @endphp
                        @if($topPositions > 0)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Top 3</span>
                            </div>
                            <span class="text-2xl font-bold text-amber-600 dark:text-amber-400">{{ $topPositions }}</span>
                        </div>
                        @endif
                    </div>
                </x-card>

                <!-- Roles Card -->
                @php
                    $roles = $user->getRoleNames();
                @endphp
                @if($roles->isNotEmpty())
                <x-card>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Roles en el Sistema</h3>

                    <div class="space-y-2">
                        @foreach($roles as $role)
                        <x-badge
                            :type="$role === 'Super Admin' ? 'error' : ($role === 'Administrador' ? 'warning' : ($role === 'Jurado' ? 'info' : 'purple'))"
                            size="lg"
                            class="w-full justify-center"
                        >
                            {{ $role }}
                        </x-badge>
                        @endforeach
                    </div>
                </x-card>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
