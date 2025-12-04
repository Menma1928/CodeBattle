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
            @endhasrole</h2>
    </div>
    <div style="width: 100%; max-width: 1200px; background: linear-gradient(180deg,#bdbdbd,#e0e0e0); border-radius: 20px; padding: 2.5rem 0; font-size: 2.5rem; color: white; font-weight: bold; box-shadow: 0 2px 8px #ccc; text-align: center; display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 2rem;">
        {{ $title ?? 'Eventos' }}
        <span style="margin-left: 1rem; font-size:2.5rem;">&#9651;</span>
    </div>
    
    <!-- Lista de eventos -->
    <div style="width: 100%; max-width: 1200px;">
        @forelse($events as $event)
        <div style="background: white; border-radius: 10px; padding: 1.5rem; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 1.5rem;">
            <!-- Logo del evento -->
            <a href="{{ route('eventos.show', $event) }}" style="text-decoration: none; flex-shrink: 0;">
                @if($event->url_imagen)
                    <img src="{{ $event->url_imagen }}" alt="{{ $event->nombre }}" style="width: 80px; height: 80px; border-radius: 10px; object-fit: cover; cursor: pointer;">
                @else
                    <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: bold; cursor: pointer;">
                        {{ substr($event->nombre, 0, 1) }}
                    </div>
                @endif
            </a>
            
            <!-- Información del evento -->
            <div style="flex: 1; cursor: pointer;" onclick="window.location='{{ route('eventos.show', $event) }}'">
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.5rem; font-weight: bold; color: #333;">
                    {{ $event->nombre }}
                </h3>
                <p style="margin: 0 0 0.5rem 0; color: #666; font-size: 0.9rem;">
                    <strong>Estado:</strong> <span style="background: {{ $event->estado === 'activo' ? '#28a745' : ($event->estado === 'finalizado' ? '#dc3545' : '#ffc107') }}; color: white; padding: 0.25rem 0.75rem; border-radius: 12px; font-size: 0.8rem;">{{ ucfirst($event->estado) }}</span>
                </p>
                <p style="margin: 0 0 0.25rem 0; color: #666; font-size: 0.9rem;">
                    <strong>Inicio:</strong> {{ \Carbon\Carbon::parse($event->fecha_inicio)->format('d/m/Y H:i') }}
                    @if($event->fecha_fin)
                        | <strong>Fin:</strong> {{ \Carbon\Carbon::parse($event->fecha_fin)->format('d/m/Y H:i') }}
                    @endif
                </p>
                @if($event->direccion)
                <p style="margin: 0 0 0.25rem 0; color: #666; font-size: 0.9rem;">
                    <strong>Ubicación:</strong> {{ $event->direccion }}
                </p>
                @endif
                <p style="margin: 0.5rem 0 0 0; color: #666; font-size: 0.9rem;">
                    {{ Str::limit($event->descripcion, 100) ?? 'Sin descripción disponible.' }}
                </p>
            </div>
            
            <!-- Botones de acción -->
            <div style="display: flex; gap: 1rem; flex-shrink: 0;">
                @can('editar eventos')
                <a href="{{ route('eventos.edit', $event) }}" style="text-decoration: none;">
                    <button style="background: #6c5b7b; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                        Editar
                    </button>
                </a>
                @endcan
                @can('eliminar eventos')
                <form method="POST" action="{{ route('eventos.destroy', $event) }}" style="margin: 0;" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este evento?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background: #dc3545; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                        Eliminar
                    </button>
                </form>
                @endcan
                <button onclick="window.location='{{ route('eventos.show', $event) }}'" style="background: white; border: 2px solid #333; padding: 0.75rem 1.5rem; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 1rem;">
                    Ingresar
                </button>
            </div>
        </div>
        @empty
        <div style="background: white; border-radius: 10px; padding: 3rem; text-align: center; color: #666;">
            <p style="font-size: 1.2rem; margin: 0;">No hay eventos disponibles</p>
        </div>
        @endforelse
    </div>
    {{ $events->links() }}
</div>
@endsection