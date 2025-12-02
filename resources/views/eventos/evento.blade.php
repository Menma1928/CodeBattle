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
                    <p style="color: #666; margin: 1rem 0 0 0; line-height: 1.6;">
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
    </div>
</div>
@endsection
