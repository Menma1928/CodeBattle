@extends('layouts.app')

@section('content')
<div style="background: #ede9f3; min-height: 100vh; padding: 2rem; display: flex; flex-direction: column; align-items: center;">
    <div style="background: #6c5b7b; color: white; padding: 1rem; border-radius: 10px; margin-bottom: 2rem; width: 100%; max-width: 1200px;">
        <h2 style="margin: 0;">
            @hasrole('Super Admin')
                Super Administrador
            @elsehasrole('Administrador')
                Administrador
            @else
                Participante
            @endhasrole
        </h2>
    </div>
    <div style="width: 100%; max-width: 1200px; background: linear-gradient(180deg,#bdbdbd,#e0e0e0); border-radius: 20px; padding: 2.5rem 0; font-size: 2.5rem; color: white; font-weight: bold; box-shadow: 0 2px 8px #ccc; text-align: center; display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
        {{ $title ?? 'Equipos' }}
        <span style="margin-left: 1rem; font-size:2.5rem;">&#9651;</span>
    </div>
    
    <!-- Lista de equipos -->
    <div style="width: 100%; max-width: 1200px;">
        @forelse($teams as $team)
        <div style="background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 1.5rem;">
            <!-- Banner del equipo -->
            <div style="flex-shrink: 0;">
                @if($team->url_banner)
                    <img src="{{ $team->url_banner }}" alt="{{ $team->nombre }}" style="width: 80px; height: 80px; border-radius: 10px; object-fit: cover; cursor: pointer;">
                @else
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold; cursor: pointer;">
                        {{ substr($team->nombre, 0, 1) }}
                    </div>
                @endif
            </div>
            
            <!-- Información del equipo -->
            <div style="flex: 1;">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.5rem; font-weight: bold; color: #333;">
                    {{ $team->nombre }}
                </h3>
                <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.9rem;">
                    <strong>Evento:</strong> 
                    <a href="{{ route('eventos.show', $team->event) }}" style="color: #6c5b7b; text-decoration: none; font-weight: bold;">
                        {{ $team->event->nombre }}
                    </a>
                </p>
                @if($team->posicion)
                <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.9rem;">
                    <strong>Posición:</strong> <span style="background: #ffc107; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem;">#{{ $team->posicion }}</span>
                </p>
                @endif
                <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.9rem;">
                    {{ Str::limit($team->descripcion, 120) ?? 'Sin descripción disponible.' }}
                </p>
            </div>
            
            <!-- Botones de acción -->
            <div style="display: flex; gap: 1rem; flex-shrink: 0;">
                @can('editar equipos')
                <a href="{{ route('equipos.edit', $team) }}" style="text-decoration: none;">
                    <button style="background: #6c5b7b; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                        Editar
                    </button>
                </a>
                @endcan
                
                @can('eliminar equipos')
                <form method="POST" action="{{ route('equipos.destroy', $team) }}" style="margin: 0;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este equipo?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #dc3545; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                        Eliminar
                    </button>
                </form>
                @endcan
                
                @can('unirse equipos')
                @if($title != 'Mis Equipos')
                <button onclick="alert('Funcionalidad en desarrollo')" style="background: #28a745; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                    Solicitar Unirme
                </button>
                @endif
                @endcan
            </div>
        </div>
        @empty
        <div style="background: white; border-radius: 10px; padding: 3rem; text-align: center; color: #666;">
            <p style="font-size: 1.2rem; margin: 0;">No hay equipos disponibles</p>
        </div>
        @endforelse
    </div>
    {{ $teams->links() }}
</div>
@endsection
