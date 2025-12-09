@extends('layouts.app')

@section('content')
<div class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Bienvenido, <span class="bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">{{ Auth::user()->name }}</span>
            </h1>
            <div class="flex items-center space-x-2">
                <x-badge type="purple">
                    @hasrole('Super Admin')
                        Super Administrador
                    @elsehasrole('Administrador')
                        Administrador
                    @else
                        Participante
                    @endhasrole
                </x-badge>
            </div>
        </div>

        <!-- Notifications Section -->
        @if($pendingRequests->count() > 0 || $myPendingRequests->count() > 0)
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Notificaciones</h2>
            
            <!-- Solicitudes recibidas (soy líder) -->
            @if($pendingRequests->count() > 0)
            <x-card class="mb-4 border-l-4 border-purple-500">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Solicitudes para unirse a tu equipo</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $pendingRequests->count() }} {{ $pendingRequests->count() == 1 ? 'solicitud pendiente' : 'solicitudes pendientes' }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($pendingRequests as $request)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex items-center gap-4 flex-1">
                            <x-avatar :name="$request->user->name" size="lg" />
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $request->user->name }}</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Quiere unirse a <span class="font-medium text-purple-600 dark:text-purple-400">{{ $request->team->nombre }}</span>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                    Evento: {{ $request->team->event->nombre }} • {{ $request->created_at->diffForHumans() }}
                                </p>
                                @if($request->message)
                                <p class="text-sm text-gray-700 dark:text-gray-300 mt-2 italic">"{{ $request->message }}"</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('joinRequests.accept', $request) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Aceptar
                                </button>
                            </form>
                            <form method="POST" action="{{ route('joinRequests.reject', $request) }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Rechazar
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            </x-card>
            @endif

            <!-- Solicitudes enviadas (yo solicité unirme) -->
            @if($myPendingRequests->count() > 0)
            <x-card class="border-l-4 border-indigo-500">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tus solicitudes pendientes</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $myPendingRequests->count() }} {{ $myPendingRequests->count() == 1 ? 'solicitud enviada' : 'solicitudes enviadas' }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($myPendingRequests as $request)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $request->team->nombre }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Evento: {{ $request->team->event->nombre }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                Enviada {{ $request->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <form method="POST" action="{{ route('joinRequests.cancel', $request) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Cancelar
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </x-card>
            @endif
        </div>
        @endif

        <!-- Quick Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @hasrole('Super Admin')
            <!-- Super Admin Cards -->
            <a href="{{ route('equipos.index') }}" class="group">
                <x-card hover="true" class="h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Equipos</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Ver y gestionar todos los equipos registrados</p>
                </x-card>
            </a>

            <a href="{{ route('eventos.index') }}" class="group">
                <x-card hover="true" class="h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Eventos</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Explorar y administrar eventos del sistema</p>
                </x-card>
            </a>
            @endhasrole

            @hasrole('Participante')
            <!-- Participante Cards -->
            <a href="{{ route('equipos.misEquipos') }}" class="group">
                <x-card hover="true" class="h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Mis Equipos</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Ver y gestionar tus equipos actuales</p>
                </x-card>
            </a>

            <a href="{{ route('eventos.index') }}" class="group">
                <x-card hover="true" class="h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Explorar Eventos</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Descubre eventos disponibles para participar</p>
                </x-card>
            </a>
            @endhasrole

            @hasrole('Administrador')
            <!-- Administrador Cards -->
            <a href="{{ route('eventos.create') }}" class="group">
                <x-card hover="true" class="h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Crear Evento</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Organiza un nuevo evento de programación</p>
                </x-card>
            </a>

            <a href="{{ route('eventos.misEventos') }}" class="group">
                <x-card hover="true" class="h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Mis Eventos</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Administra los eventos que has creado</p>
                </x-card>
            </a>

            <a href="{{ route('eventos.index') }}" class="group">
                <x-card hover="true" class="h-full">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Todos los Eventos</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Explora todos los eventos del sistema</p>
                </x-card>
            </a>
            @endhasrole
        </div>

        <!-- Help Section -->
        <x-card class="mt-8 bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 border-purple-200 dark:border-purple-800">
            <div class="flex items-start space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">¿Necesitas ayuda?</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        Si tienes alguna pregunta o necesitas asistencia, no dudes en contactar al equipo de soporte.
                    </p>
                </div>
            </div>
        </x-card>
    </div>
</div>
@endsection
