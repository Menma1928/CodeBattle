@extends('layouts.app')

@section('content')
<div style="background: white; min-height: 100vh;">
    <!-- Header morado -->
    <div style="background: #6c5b7b; padding: 1rem; display: flex; align-items: center; gap: 1rem;">
        <a href="{{ route('eventos.index') }}" style="color: white; text-decoration: none; font-size: 1.5rem;">
            &#8592;
        </a>
        <span style="color: white; font-size: 1.2rem; font-weight: bold;">{{ $evento->nombre ?? 'Nombre del Evento' }}</span>
    </div>

    <div style="padding: 2rem; max-width: 800px; margin: 0 auto;">
        <!-- Información del evento -->
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <div style="display: flex; gap: 2rem; margin-bottom: 2rem;">
                <!-- Logo del evento -->
                <div style="flex-shrink: 0;">
                    @if($evento->url_imagen)
                        <img src="{{ $evento->url_imagen }}" alt="{{ $evento->nombre }}" style="width: 120px; height: 120px; border-radius: 10px; object-fit: cover;">
                    @else
                        <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                            {{ substr($evento->nombre ?? 'E', 0, 1) }}
                        </div>
                    @endif
                </div>

                <!-- Detalles del evento -->
                <div style="flex: 1;">
                    <h1 style="font-size: 2rem; font-weight: bold; margin: 0 0 0.5rem 0; color: #333;">
                        {{ $evento->nombre ?? 'Hackatec' }}
                    </h1>
                    <p style="margin: 0 0 1rem 0; color: #666;">
                        <strong>Estado:</strong> <span style="background: {{ $evento->estado === 'activo' ? '#28a745' : ($evento->estado === 'finalizado' ? '#dc3545' : '#ffc107') }}; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.85rem;">{{ ucfirst($evento->estado) }}</span>
                    </p>
                    <p style="color: #666; margin: 0 0 0.5rem 0;">
                        <strong> Fecha de inicio:</strong> {{ \Carbon\Carbon::parse($evento->fecha_inicio)->format('d/m/Y H:i') }}
                    </p>
                    @if($evento->fecha_fin)
                    <p style="color: #666; margin: 0 0 0.5rem 0;">
                        <strong>Fecha de fin:</strong> {{ \Carbon\Carbon::parse($evento->fecha_fin)->format('d/m/Y H:i') }}
                    </p>
                    @endif
                    @if($evento->direccion)
                    <p style="color: #666; margin: 0 0 0.5rem 0;">
                        <strong> Ubicación:</strong> {{ $evento->direccion }}
                    </p>
                    @endif
                    <p style="color: #666; margin: 1rem 0 0 0; line-height: 1.6;">
                        <strong>Descripción:</strong><br>
                        {{ $evento->descripcion ?? 'El HackaTecNM es uno de los eventos más esperados por la comunidad tecnológica del Tecnológico Nacional de México, donde el talento estudiantil se enfrenta al reto de desarrollar soluciones innovadoras en un corto período de tiempo.' }}
                    </p>
                </div>

                <!-- Botón Ingresar -->
                <div style="flex-shrink: 0; display: flex; align-items: flex-start;">
                    <button style="background: white; border: 2px solid #333; padding: 0.5rem 2rem; border-radius: 5px; font-weight: bold; cursor: pointer;">
                        Ingresar
                    </button>
                </div>
            </div>
        </div>

        <!-- Sección de tu equipo -->
        @if($user_team != null)
        <div style="background: white; border-radius: 10px; padding: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.8rem; font-weight: bold; color: #333; margin: 0;">Tu Equipo para Este Evento</h2>
                <a href="{{ route('equipos.show', $user_team) }}" style="text-decoration: none;">
                    <button style="background: #6c5b7b; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                        Ver Equipo
                    </button>
                </a>
            </div>
            
            <!-- Nombre del equipo -->
            <div style="margin-bottom: 1.5rem;">
                <h3 style="font-size: 1.5rem; font-weight: bold; color: #6c5b7b; margin: 0;">{{ $user_team->nombre }}</h3>
                @if($user_team->descripcion)
                <p style="color: #666; margin: 0.5rem 0 0 0;">{{ $user_team->descripcion }}</p>
                @endif
            </div>

            <!-- Lista de integrantes -->
            <div style="background: #f8f9fa; border-radius: 10px; padding: 1rem;">
                <h4 style="font-size: 1.2rem; font-weight: bold; color: #333; margin: 0 0 1rem 0;">Integrantes ({{ $user_team->users->count() }}/5)</h4>
                
                @foreach($user_team->users as $index => $member)
                <div style="padding: 1rem; display: flex; align-items: center; gap: 1rem; {{ $index > 0 ? 'border-top: 1px solid #e0e0e0;' : '' }}">
                    <!-- Avatar -->
                    <div style="flex-shrink: 0; width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; font-weight: bold;">
                        {{ substr($member->name, 0, 1) }}
                    </div>
                    
                    <!-- Información del miembro -->
                    <div style="flex: 1;">
                        <div style="font-weight: bold; font-size: 1rem; color: #333;">
                            {{ $member->name }}
                            @if($member->id == auth()->id())
                            <span style="color: #6c5b7b;">(Tú)</span>
                            @endif
                        </div>
                        <div style="color: #666; font-size: 0.85rem;">{{ $member->email }}</div>
                    </div>
                    
                    <!-- Rol -->
                    <div style="flex-shrink: 0;">
                        <span style="background: #6c5b7b; color: white; padding: 0.4rem 0.9rem; border-radius: 15px; font-size: 0.85rem; font-weight: bold;">
                            {{ $member->pivot->role ?? 'Miembro' }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Sección de equipos registrados -->
        <div style="margin-top: 2rem;">
            <h2 style="font-size: 1.8rem; font-weight: bold; color: #333; margin-bottom: 1.5rem;">Equipos Registrados</h2>
            
            @forelse($teams as $team)
            <div style="background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
                    <!-- Banner del equipo (clickeable) -->
                    <a href="{{ route('equipos.show', $team) }}" style="flex-shrink: 0; text-decoration: none;">
                        @if($team->url_banner)
                            <img src="{{ $team->url_banner }}" alt="{{ $team->nombre }}" style="width: 120px; height: 120px; border-radius: 10px; object-fit: cover; cursor: pointer;">
                        @else
                            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold; cursor: pointer;">
                                {{ substr($team->nombre, 0, 1) }}
                            </div>
                        @endif
                    </a>

                    <!-- Información del equipo (clickeable) -->
                    <a href="{{ route('equipos.show', $team) }}" style="flex: 1; text-decoration: none; color: inherit;">
                        <div>
                            <h3 style="font-size: 1.5rem; font-weight: bold; color: #333; margin: 0 0 0.5rem 0;">
                                {{ $team->nombre }}
                            </h3>
                            @if($team->posicion)
                                <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.9rem;">
                                    <strong>Posición:</strong> #{{ $team->posicion }}
                                </p>
                            @endif
                            <p style="margin: 0; color: #666; line-height: 1.6;">
                                {{ $team->descripcion ?? 'Sin descripción disponible.' }}
                            </p>
                        </div>
                    </a>

                    <!-- Botones de acción -->
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; flex-shrink: 0;">
                        @can('editar equipos')
                        <button style="background: #6c5b7b; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">
                            Editar Equipo
                        </button>
                        @endcan
                        
                        @can('unirse equipos')
                        @if(!auth()->user()->hasRole('Super Admin'))
                        <button style="background: #28a745; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">
                            Solicitar Unirme
                        </button>
                        @endif
                        @endcan
                        
                        @can('eliminar equipos')
                        <form method="POST" action="{{ route('equipos.destroy', $team) }}" style="margin: 0;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este equipo?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 0.9rem; width: 100%;">
                                Eliminar Equipo
                            </button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
            @empty
            <div style="background: white; border-radius: 10px; padding: 3rem; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <p style="font-size: 1.2rem; color: #666; margin: 0;">No hay equipos registrados en este evento</p>
            </div>
            @endforelse
        </div>
        {{ $teams->links() }}
    </div>
</div>
@endsection
