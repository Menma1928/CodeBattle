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

        <!-- Sección de equipos -->
        @hasrole('participante')
        <div style="background: #6c5b7b; border-radius: 10px; padding: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="color: white; font-size: 1.5rem; margin: 0;">Nombre de equipo</h2>
                <button style="background: white; border: none; padding: 0.5rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer;">
                    Cambiar
                </button>
            </div>

            <!-- Líder -->
            <div style="background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; background: #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        +
                    </div>
                    <div>
                        <div style="font-weight: bold; font-size: 1.1rem;">Líder (Tú)</div>
                        <div style="color: #666; font-size: 0.9rem;">Rol - Front</div>
                    </div>
                </div>
                <button style="background: white; border: 2px solid #333; padding: 0.5rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer;">
                    Cambiar rol
                </button>
            </div>

            <!-- Invitar - Slot 1 -->
            <div style="background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; background: #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        +
                    </div>
                    <div>
                        <div style="font-weight: bold; font-size: 1.1rem;">Invitar</div>
                        <div style="color: #666; font-size: 0.9rem;">Rol - por asignar</div>
                    </div>
                </div>
                <button style="background: white; border: 2px solid #333; padding: 0.5rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer;">
                    Cambiar rol
                </button>
            </div>

            <!-- Invitar - Slot 2 -->
            <div style="background: white; border-radius: 10px; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 50px; height: 50px; background: #e0e0e0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                        +
                    </div>
                    <div>
                        <div style="font-weight: bold; font-size: 1.1rem;">Invitar</div>
                        <div style="color: #666; font-size: 0.9rem;">Rol - por asignar</div>
                    </div>
                </div>
                <button style="background: white; border: 2px solid #333; padding: 0.5rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer;">
                    Cambiar rol
                </button>
            </div>
        </div>
        @endhasrole

        <!-- Sección de equipos registrados -->
        <div style="margin-top: 2rem;">
            <h2 style="font-size: 1.8rem; font-weight: bold; color: #333; margin-bottom: 1.5rem;">Equipos Registrados</h2>
            
            @forelse($teams as $team)
            <div style="background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                <div style="display: flex; gap: 1.5rem; align-items: flex-start;">
                    <!-- Banner del equipo -->
                    <div style="flex-shrink: 0;">
                        @if($team->url_banner)
                            <img src="{{ $team->url_banner }}" alt="{{ $team->nombre }}" style="width: 120px; height: 120px; border-radius: 10px; object-fit: cover;">
                        @else
                            <div style="width: 120px; height: 120px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem; font-weight: bold;">
                                {{ substr($team->nombre, 0, 1) }}
                            </div>
                        @endif
                    </div>

                    <!-- Información del equipo -->
                    <div style="flex: 1;">
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

                    <!-- Botones de acción -->
                    <div style="display: flex; flex-direction: column; gap: 0.75rem; flex-shrink: 0;">
                        @can('editar equipos')
                        <button style="background: #6c5b7b; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">
                            Editar Equipo
                        </button>
                        @endcan
                        
                        @can('unirse equipos')
                        <button style="background: #28a745; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 0.9rem;">
                            Solicitar Unirme
                        </button>
                        @endcan
                        
                        @can('eliminar equipos')
                        <form method="POST" action="#" style="margin: 0;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este equipo del evento?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: #dc3545; color: white; border: none; padding: 0.5rem 1.25rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 0.9rem; width: 100%;">
                                Eliminar del Evento
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
    </div>
</div>
@endsection
