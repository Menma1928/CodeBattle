@extends('layouts.app')

@section('content')
<div class="min-h-screen">
    <!-- Header with Back Button -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('eventos.show', $evento) }}" class="text-white hover:text-purple-200 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <h1 class="text-2xl font-bold text-white">Gestionar Jurados - {{ $evento->nombre }}</h1>
            </div>
        </div>
    </div>

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">

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
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 transition">
                                    Remover
                                </button>
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
                    <form action="{{ route('eventos.assignJury', $evento) }}" method="POST" id="juryForm">
                        @csrf

                        <div class="mb-4">
                            <label for="user_search" class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Buscar Usuario</label>
                            
                            <!-- Campo de búsqueda -->
                            <div class="relative">
                                <input 
                                    type="text" 
                                    id="user_search" 
                                    placeholder="Buscar por nombre o email..."
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full"
                                    autocomplete="off"
                                >
                                <div class="absolute right-3 top-1/2 -translate-y-1/2">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <!-- Input oculto para el ID del usuario -->
                            <input type="hidden" id="user_id" name="user_id" required>

                            <!-- Lista de resultados -->
                            <div id="search_results" class="mt-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg max-h-60 overflow-y-auto hidden">
                                @foreach($availableUsers as $user)
                                <div class="user-option px-4 py-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Usuario seleccionado -->
                            <div id="selected_user" class="mt-3 hidden">
                                <div class="flex items-center justify-between p-3 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-md">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-indigo-600 rounded-full flex items-center justify-center text-white font-semibold">
                                            <span id="selected_avatar"></span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-gray-100" id="selected_name"></p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400" id="selected_email"></p>
                                        </div>
                                    </div>
                                    <button type="button" id="clear_selection" class="text-red-600 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            @error('user_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 transition">
                            Asignar Jurado
                        </button>
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

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('user_search');
    const searchResults = document.getElementById('search_results');
    const userIdInput = document.getElementById('user_id');
    const selectedUserDiv = document.getElementById('selected_user');
    const selectedName = document.getElementById('selected_name');
    const selectedEmail = document.getElementById('selected_email');
    const selectedAvatar = document.getElementById('selected_avatar');
    const clearButton = document.getElementById('clear_selection');
    const userOptions = document.querySelectorAll('.user-option');

    // Mostrar resultados al hacer clic en el input
    searchInput.addEventListener('focus', function() {
        if (searchInput.value === '') {
            searchResults.classList.remove('hidden');
        }
    });

    // Buscar usuarios mientras se escribe
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        let hasVisibleResults = false;

        userOptions.forEach(option => {
            const name = option.dataset.name.toLowerCase();
            const email = option.dataset.email.toLowerCase();

            if (name.includes(searchTerm) || email.includes(searchTerm)) {
                option.classList.remove('hidden');
                hasVisibleResults = true;
            } else {
                option.classList.add('hidden');
            }
        });

        searchResults.classList.toggle('hidden', !hasVisibleResults && searchTerm !== '');
        if (searchTerm === '') {
            searchResults.classList.remove('hidden');
        }
    });

    // Seleccionar usuario
    userOptions.forEach(option => {
        option.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            const userEmail = this.dataset.email;

            // Establecer valores
            userIdInput.value = userId;
            searchInput.value = userName;
            selectedName.textContent = userName;
            selectedEmail.textContent = userEmail;
            selectedAvatar.textContent = userName.charAt(0).toUpperCase();

            // Mostrar selección y ocultar resultados
            selectedUserDiv.classList.remove('hidden');
            searchResults.classList.add('hidden');
        });
    });

    // Limpiar selección
    clearButton.addEventListener('click', function() {
        userIdInput.value = '';
        searchInput.value = '';
        selectedUserDiv.classList.add('hidden');
        searchResults.classList.remove('hidden');
        
        // Mostrar todas las opciones
        userOptions.forEach(option => {
            option.classList.remove('hidden');
        });
    });

    // Cerrar resultados al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
});
</script>
@endsection
