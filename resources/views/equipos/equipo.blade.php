@extends('layouts.app')

@section('content')
<div class="min-h-screen" x-data="teamInvite()">
    <!-- Header with Back Button -->
    <div class="bg-gradient-to-r from-pink-600 to-purple-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('equipos.index') }}" class="text-white hover:text-pink-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-white">{{ $equipo->nombre }}</h1>
            </div>
        </div>
    </div>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Team Information Card -->
            <x-card class="mb-6">
                <div class="flex flex-col md:flex-row gap-6">
                    <!-- Team Banner -->
                    <div class="flex-shrink-0">
                        @if($equipo->url_banner)
                            @php
                                $bannerUrl = str_starts_with($equipo->url_banner, 'http')
                                    ? $equipo->url_banner
                                    : asset('storage/' . $equipo->url_banner);
                            @endphp
                            <img src="{{ $bannerUrl }}" alt="{{ $equipo->nombre }}" class="w-40 h-40 rounded-xl object-cover shadow-md">
                        @else
                            <div class="w-40 h-40 bg-gradient-to-br from-pink-500 to-purple-500 rounded-xl flex items-center justify-center text-white text-6xl font-bold shadow-md">
                                {{ substr($equipo->nombre ?? 'E', 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- Team Details -->
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">
                            {{ $equipo->nombre }}
                        </h2>

                        <div class="space-y-3 mb-4">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">
                                    <strong>Evento:</strong>
                                    <a href="{{ route('eventos.show', $equipo->event) }}" class="text-purple-600 dark:text-purple-400 hover:underline font-semibold">
                                        {{ $equipo->event->nombre }}
                                    </a>
                                </span>
                            </div>

                            @if($equipo->posicion)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                <x-badge type="warning" size="lg">
                                    Posición #{{ $equipo->posicion }}
                                </x-badge>
                            </div>
                            @endif
                        </div>

                        @if($equipo->descripcion)
                        <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Descripción</p>
                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $equipo->descripcion }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </x-card>

            <!-- Project Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    Proyecto del Equipo
                </h2>

                @if($equipo->project)
                    <!-- Project exists - Show details -->
                    <x-card>
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                    {{ $equipo->project->nombre }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400">
                                    {{ $equipo->project->descripcion }}
                                </p>
                            </div>

                            @if($equipo->project->github_url)
                            <div class="flex items-center gap-2 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <svg class="w-5 h-5 text-gray-900 dark:text-white flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                                <a href="{{ $equipo->project->github_url }}" target="_blank" class="text-purple-600 dark:text-purple-400 hover:underline font-medium break-all">
                                    {{ $equipo->project->github_url }}
                                </a>
                            </div>
                            @endif

                            @if($equipo->project->fecha_subida)
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Subido el {{ \Carbon\Carbon::parse($equipo->project->fecha_subida)->format('d/m/Y H:i') }}
                            </p>
                            @endif

                            <!-- Action buttons for leader -->
                            <div class="flex flex-wrap gap-3 pt-4">
                                <a href="{{ route('projects.show', $equipo->project) }}">
                                    <x-secondary-button>
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Ver Detalles
                                    </x-secondary-button>
                                </a>

                                @if($is_leader && $equipo->event->canEditProjects())
                                    <a href="{{ route('projects.edit', $equipo->project) }}">
                                        <x-primary-button>
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Editar Proyecto
                                        </x-primary-button>
                                    </a>
                                @elseif($is_leader)
                                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg">
                                        <p class="text-sm text-amber-800 dark:text-amber-200">
                                            <strong>Nota:</strong> El proyecto no puede editarse porque el evento está en estado "{{ $equipo->event->estado }}".
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </x-card>
                @else
                    <!-- No project - Show create option for leader -->
                    @if($is_leader)
                        <x-card>
                            <div class="text-center py-8">
                                <div class="mb-4">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    No hay proyecto registrado
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    Como líder del equipo, puedes crear el proyecto y añadir el enlace de GitHub
                                </p>

                                @if($equipo->event->canEditProjects())
                                    <a href="{{ route('projects.create', ['team_id' => $equipo->id]) }}">
                                        <x-primary-button>
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Crear Proyecto
                                        </x-primary-button>
                                    </a>
                                @else
                                    <div class="p-3 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-lg inline-block">
                                        <p class="text-sm text-amber-800 dark:text-amber-200">
                                            No se pueden crear proyectos. El evento debe estar en estado "activo" o "en_calificacion".
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </x-card>
                    @else
                        <x-card>
                            <div class="text-center py-8">
                                <x-empty-state
                                    title="No hay proyecto registrado"
                                    message="El líder del equipo aún no ha creado el proyecto."
                                />
                            </div>
                        </x-card>
                    @endif
                @endif
            </div>

            <!-- Pending Join Requests Section (Only for leader) -->
            @if($is_leader && $equipo->pendingJoinRequests->count() > 0)
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                    Solicitudes Pendientes ({{ $equipo->pendingJoinRequests->count() }})
                </h2>

                <x-card :padding="false">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($equipo->pendingJoinRequests as $request)
                        <div class="p-6">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <!-- User Info -->
                                <div class="flex items-center gap-4 flex-1">
                                    <x-avatar :name="$request->user->name" size="lg" />
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $request->user->name }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $request->user->email }}</p>
                                        @if($request->message)
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 italic">"{{ $request->message }}"</p>
                                        @endif
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                            Solicitó el {{ $request->created_at->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex gap-2 w-full sm:w-auto">
                                    <form method="POST" action="{{ route('joinRequests.accept', $request) }}" class="flex-1 sm:flex-none">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold text-sm transition-colors">
                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Aceptar
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('joinRequests.reject', $request) }}" class="flex-1 sm:flex-none">
                                        @csrf
                                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold text-sm transition-colors">
                                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Rechazar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </x-card>
            </div>
            @endif

            <!-- Team Members Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Miembros del Equipo ({{ $equipo->users->count() }}/5)
                    </h2>
                    
                    @if($is_leader && $equipo->users->count() < 5 && $equipo->event->estado === 'pendiente')
                    <button @click="showUserSearch = true" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Invitar Usuario
                    </button>
                    @endif
                </div>

                <!-- User Search Modal -->
                @if($is_leader && $equipo->users->count() < 5 && $equipo->event->estado === 'pendiente')
                <div x-show="showUserSearch" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div x-show="showUserSearch" @click="showUserSearch = false" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                        <div x-show="showUserSearch" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Buscar Usuario</h3>
                                    <button @click="showUserSearch = false" class="text-gray-400 hover:text-gray-500">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="mb-4">
                                    <input type="text" x-model="searchQuery" @input="searchUsers" placeholder="Buscar por nombre o email..." class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
                                </div>

                                <div class="max-h-96 overflow-y-auto">
                                    <div x-show="loading" class="text-center py-4">
                                        <p class="text-gray-500 dark:text-gray-400">Buscando...</p>
                                    </div>

                                    <div x-show="!loading && searchResults.length === 0 && searchQuery.length >= 2" class="text-center py-4">
                                        <p class="text-gray-500 dark:text-gray-400">No se encontraron usuarios</p>
                                    </div>

                                    <div x-show="searchQuery.length < 2 && !loading" class="text-center py-4">
                                        <p class="text-gray-500 dark:text-gray-400">Escribe al menos 2 caracteres para buscar</p>
                                    </div>

                                    <div class="space-y-2">
                                        <template x-for="user in searchResults" :key="user.id">
                                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold">
                                                        <span x-text="user.name.charAt(0).toUpperCase()"></span>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900 dark:text-white" x-text="user.name"></p>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400" x-text="user.email"></p>
                                                    </div>
                                                </div>
                                                <button @click="inviteUser(user.id)" :disabled="inviting" class="inline-flex items-center px-3 py-1 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50">
                                                    Invitar
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <x-card :padding="false">
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($equipo->users as $member)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                <!-- Avatar and Info -->
                                <div class="flex items-center gap-4 flex-1">
                                    <x-avatar :name="$member->name" size="xl" />

                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            {{ $member->name }}
                                            @if($member->id == auth()->id())
                                            <span class="text-purple-600 dark:text-purple-400">(Tú)</span>
                                            @endif
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $member->email }}</p>
                                    </div>
                                </div>

                                <!-- Role Selector and Actions -->
                                <div class="flex items-center gap-3 w-full sm:w-auto">
                                    @if(auth()->user()->hasRole('Super Admin') || $is_leader)
                                        <!-- Role Selector -->
                                        <select
                                            data-member-id="{{ $member->id }}"
                                            data-team-id="{{ $equipo->id }}"
                                            onchange="updateMemberRole(this)"
                                            class="flex-1 sm:flex-none px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-semibold text-sm transition-colors border-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                            {{ $member->pivot->rol === 'lider' ? 'disabled' : '' }}>
                                            <option value="miembro" {{ ($member->pivot->rol ?? 'miembro') == 'miembro' ? 'selected' : '' }}>Miembro</option>
                                            <option value="lider" {{ ($member->pivot->rol ?? '') == 'lider' ? 'selected' : '' }}>Líder</option>
                                            <option value="desarrollador" {{ ($member->pivot->rol ?? '') == 'desarrollador' ? 'selected' : '' }}>Desarrollador</option>
                                            <option value="diseñador" {{ ($member->pivot->rol ?? '') == 'diseñador' ? 'selected' : '' }}>Diseñador</option>
                                        </select>

                                        <!-- Remove Button -->
                                        <form method="POST" action="{{ route('equipos.removeMember', ['equipo' => $equipo, 'user' => $member]) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este miembro del equipo?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                                title="Eliminar miembro"
                                                {{ $member->pivot->rol === 'lider' ? 'disabled' : '' }}>
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <!-- Read-only Role Badge -->
                                        <x-badge type="purple" size="lg">
                                            {{ ucfirst($member->pivot->rol ?? 'miembro') }}
                                        </x-badge>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-12 text-center">
                            <x-empty-state
                                title="No hay miembros"
                                message="Este equipo aún no tiene miembros."
                            />
                        </div>
                        @endforelse
                    </div>
                </x-card>
            </div>

            <!-- Team Action Buttons -->
            <div class="mt-8 flex flex-wrap gap-4 justify-center">
                @if(auth()->user()->hasRole('Super Admin') || $is_leader)
                    <a href="{{ route('equipos.edit', $equipo) }}">
                        <x-secondary-button class="px-8">
                            Editar Equipo
                        </x-secondary-button>
                    </a>

                    <form method="POST" action="{{ route('equipos.destroy', $equipo) }}" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este equipo?');">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit" class="px-8">
                            Eliminar Equipo
                        </x-danger-button>
                    </form>

                @elseif($is_member && !$is_leader)
                    <!-- Leave Team Button -->
                    <form method="POST" action="{{ route('equipos.leave', $equipo) }}" onsubmit="return confirm('¿Estás seguro de que deseas abandonar este equipo?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-8 py-2 bg-amber-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-amber-700 focus:bg-amber-700 active:bg-amber-900 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Abandonar Equipo
                        </button>
                    </form>

                @elseif(!$is_member && $equipo->event->estado === 'pendiente')
                    @if($has_pending_request)
                        <!-- Pending Request Message -->
                        <div class="text-center">
                            <x-badge type="warning" size="lg">
                                Solicitud Pendiente
                            </x-badge>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                Tu solicitud está siendo revisada por el líder del equipo.
                            </p>
                        </div>
                    @elseif($user_team_in_event)
                        <!-- Already in another team -->
                        <div class="text-center">
                            <x-badge type="info" size="lg">
                                Ya estás en un equipo de este evento
                            </x-badge>
                        </div>
                    @elseif($equipo->users->count() >= 5)
                        <!-- Team is full -->
                        <div class="text-center">
                            <x-badge type="danger" size="lg">
                                Equipo Completo (5/5)
                            </x-badge>
                        </div>
                    @else
                        <!-- Join Request Button -->
                        <form method="POST" action="{{ route('equipos.joinRequest', $equipo) }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-8 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                Solicitar Unirme
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function updateMemberRole(selectElement) {
    const memberId = selectElement.getAttribute('data-member-id');
    const teamId = selectElement.getAttribute('data-team-id');
    const newRole = selectElement.value;

    fetch(`/equipos/${teamId}/update-role/${memberId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ rol: newRole })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message with Tailwind styling
            const message = document.createElement('div');
            message.className = 'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            message.textContent = 'Rol actualizado correctamente';
            document.body.appendChild(message);
            setTimeout(() => message.remove(), 3000);
        } else {
            alert(data.error || 'Error al actualizar el rol');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el rol');
        location.reload();
    });
}
</script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('teamInvite', () => ({
        showUserSearch: false,
        searchQuery: '',
        searchResults: [],
        loading: false,
        inviting: false,

        searchUsers() {
            if (this.searchQuery.length < 2) {
                this.searchResults = [];
                return;
            }

            this.loading = true;
            
            fetch(`{{ route('equipos.searchUsers', $equipo) }}?q=${encodeURIComponent(this.searchQuery)}`)
                .then(response => response.json())
                .then(data => {
                    this.searchResults = data.users || [];
                    this.loading = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    this.loading = false;
                });
        },

        inviteUser(userId) {
            if (this.inviting) return;
            
            this.inviting = true;

            fetch(`{{ route('equipos.inviteUser', $equipo) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ user_id: userId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert(data.error || 'Error al invitar usuario');
                    this.inviting = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al invitar usuario');
                this.inviting = false;
            });
        }
    }));
});
</script>
@endsection
