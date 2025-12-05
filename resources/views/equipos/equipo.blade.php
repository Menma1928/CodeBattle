@extends('layouts.app')

@section('content')
<div class="min-h-screen">
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
                            <img src="{{ $equipo->url_banner }}" alt="{{ $equipo->nombre }}" class="w-40 h-40 rounded-xl object-cover shadow-md">
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

            <!-- Team Members Section -->
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                    Miembros del Equipo ({{ $equipo->users->count() }}/5)
                </h2>

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

                @elseif(!$is_member && auth()->user()->hasRole('Participante') && $equipo->users->count() < 5)
                    <button onclick="alert('Funcionalidad en desarrollo')" class="inline-flex items-center px-8 py-2 bg-emerald-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Solicitar Unirme
                    </button>
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
@endsection
