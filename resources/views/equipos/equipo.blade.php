@extends('layouts.app')

@section('content')
<div style="background: white; min-height: 100vh;">
    <!-- Header morado -->
    <div style="background: #6c5b7b; padding: 1rem; display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('equipos.index') }}" style="color: white; text-decoration: none; font-size: 1.5rem;">
            &#8592;
        </a>
        <span style="color: white; font-size: 1.2rem; font-weight: bold;">{{ $equipo->nombre ?? 'Nombre del Equipo' }}</span>
    </div>

    <div style="padding: 2rem; max-width: 900px; margin: 0 auto;">
        <!-- Información del equipo -->
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <div style="display: flex; gap: 2rem; margin-bottom: 2rem;">
                <!-- Banner del equipo -->
                <div style="flex-shrink: 0;">
                    @if($equipo->url_banner)
                        <img src="{{ $equipo->url_banner }}" alt="{{ $equipo->nombre }}" style="width: 150px; height: 150px; border-radius: 10px; object-fit: cover;">
                    @else
                        <div style="width: 150px; height: 150px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 4rem; font-weight: bold;">
                            {{ substr($equipo->nombre ?? 'E', 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Detalles del equipo -->
                <div style="flex: 1;">
                    <h1 style="font-size: 2rem; font-weight: bold; margin: 0 0 0.5rem 0; color: #333;">
                        {{ $equipo->nombre }}
                    </h1>
                    
                    <p style="margin: 0 0 0.5rem 0; color: #666;">
                        <strong>Evento:</strong> 
                        <a href="{{ route('eventos.show', $equipo->event) }}" style="color: #6c5b7b; text-decoration: none; font-weight: bold;">
                            {{ $equipo->event->nombre }}
                        </a>
                    </p>
                    
                    @if($equipo->posicion)
                    <p style="margin: 0 0 1rem 0; color: #666;">
                        <strong>Posición:</strong> <span style="background: #ffc107; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem;">#{{ $equipo->posicion }}</span>
                    </p>
                    @endif
                    
                    <p style="color: #666; margin: 1rem 0 0 0; line-height: 1.6;">
                        <strong>Descripción:</strong><br>
                        {{ $equipo->descripcion ?? 'Sin descripción disponible.' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Sección de miembros del equipo -->
        <div style="margin-top: 2rem;">
            <h2 style="font-size: 1.8rem; font-weight: bold; color: #333; margin-bottom: 1.5rem;">
                Miembros del Equipo ({{ $equipo->users->count() }}/5)
            </h2>
            
            <div style="background: white; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                @forelse($equipo->users as $index => $member)
                <div style="padding: 1.5rem; display: flex; align-items: center; gap: 1.5rem; {{ $index > 0 ? 'border-top: 1px solid #e0e0e0;' : '' }}">
                    <!-- Avatar -->
                    <div style="flex-shrink: 0; width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.5rem; font-weight: bold;">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                    
                    <!-- Información del miembro -->
                    <div style="flex: 1;">
                        <h3 style="font-size: 1.1rem; font-weight: bold; color: #333; margin: 0 0 0.25rem 0;">
                            {{ $member->name }}
                            @if($member->id == auth()->id())
                            <span style="color: #6c5b7b;">(Tú)</span>
                            @endif
                        </h3>
                        <p style="color: #666; font-size: 0.9rem; margin: 0;">
                            {{ $member->email }}
                        </p>
                    </div>
                    
                    <!-- Rol y acciones -->
                    <div style="flex-shrink: 0; display: flex; align-items: center; gap: 1rem;">
                        @if(auth()->user()->hasRole('Super Admin') || $is_leader)
                        <!-- Selector de rol -->
                        <select style="background: #6c5b7b; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: bold; border: none; cursor: pointer;">
                            <option value="miembro" {{ ($member->pivot->rol ?? 'Miembro') == 'Miembro' ? 'selected' : '' }}>Miembro</option>
                            <option value="lider" {{ ($member->pivot->rol ?? '') == 'Líder' ? 'selected' : '' }}>Líder</option>
                            <option value="desarrollador" {{ ($member->pivot->rol ?? '') == 'Desarrollador' ? 'selected' : '' }}>Desarrollador</option>
                            <option value="disenador" {{ ($member->pivot->rol ?? '') == 'Diseñador' ? 'selected' : '' }}>Diseñador</option>
                        </select>
                        
                        <!-- Botón eliminar -->
                        <form method="POST" action="{{ route('equipos.removeMember', ['equipo' => $equipo, 'user' => $member]) }}" style="margin: 0;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar a este miembro del equipo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 0.5rem 0.75rem; border-radius: 5px; cursor: pointer; font-size: 0.9rem;" title="Eliminar miembro">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                    <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                            </button>
                        </form>
                        @else
                        <!-- Solo mostrar rol sin edición -->
                        <span style="background: #6c5b7b; color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: bold;">
                            {{ $member->pivot->rol ?? 'Miembro' }}
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                <div style="padding: 3rem; text-align: center; color: #999;">
                    <p style="font-size: 1.1rem; margin: 0;">No hay miembros en este equipo aún.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Botones de acción del equipo -->
        <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center;">
            @if(auth()->user()->hasRole('Super Admin') || $is_leader)
            <a href="{{ route('equipos.edit', $equipo) }}" style="text-decoration: none;">
                <button style="background: #6c5b7b; color: white; border: none; padding: 0.75rem 2rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                    Editar Equipo
                </button>
            </a>
            
            <form method="POST" action="{{ route('equipos.destroy', $equipo) }}" style="margin: 0;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este equipo?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 0.75rem 2rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                    Eliminar Equipo
                </button>
            </form>
            @elseif($is_member && !$is_leader)
            <!-- Botón para abandonar el equipo -->
            <form method="POST" action="{{ route('equipos.leave', $equipo) }}" style="margin: 0;" onsubmit="return confirm('¿Estás seguro de que deseas abandonar este equipo?');">
                @csrf
                @method('DELETE')
                <button type="submit" style="background: #ffc107; color: white; border: none; padding: 0.75rem 2rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                    Abandonar Equipo
                </button>
            </form>
            @elseif(!$is_member && !auth()->user()->hasRole('Super Admin'))
            <button onclick="alert('Funcionalidad en desarrollo')" style="background: #28a745; color: white; border: none; padding: 0.75rem 2rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                Solicitar Unirme
            </button>
            @endif
        </div>
    </div>
</div>
@endsection
