@extends('layouts.app')

@section('content')

<div class="min-h-screen py-8 px-2 flex flex-col items-center" style="background: #ede9f3;">
    <div class="w-full max-w-4xl">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
            <div style="background: #7c6992; color: white;" class="rounded-lg px-6 py-2 text-lg font-semibold shadow">
                @hasrole('Super Admin')
                    Super Administrador
                @elsehasrole('Administrador')
                    Administrador
                @else
                    Participante
                @endhasrole
            </div>
        </a>

        @hasrole('Super Admin')
        <a href="{{ route('equipos.index') }}" style="text-decoration: none; width: 100%;">
            <div style="background: linear-gradient(180deg,#bdbdbd,#e0e0e0); border-radius: 20px; padding: 2.5rem 0; font-size: 2.5rem; color: white; font-weight: bold; box-shadow: 0 2px 8px #ccc; text-align: center; width: 100%; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                Equipos
                <span style="margin-left: 1rem; font-size:2.5rem;">&#9651;</span>
            </div>
        </a>
        @endhasrole

        @hasrole('Participante')
        <a href="{{ route('equipos.misEquipos') }}" style="text-decoration: none; width: 100%;">
            <div style="background: linear-gradient(180deg,#bdbdbd,#e0e0e0); border-radius: 20px; padding: 2.5rem 0; font-size: 2.5rem; color: white; font-weight: bold; box-shadow: 0 2px 8px #ccc; text-align: center; width: 100%; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                Mis equipos
                <span style="margin-left: 1rem; font-size:2.5rem;">&#9651;</span>
            </div>
        </a>
        @endhasrole

        @hasrole('Administrador')
        <a href="{{ route('eventos.create') }}" style="text-decoration: none; width: 100%;">
            <div style="background: linear-gradient(180deg,#bdbdbd,#e0e0e0); border-radius: 20px; padding: 2.5rem 0; font-size: 2.5rem; color: white; font-weight: bold; box-shadow: 0 2px 8px #ccc; text-align: center; width: 100%; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                Crear Evento
                <span style="margin-left: 1rem; font-size:2.5rem;">&#9651;</span>
            </div>
        </a>
        @endhasrole

        @hasrole('Administrador')
        <a href="{{ route('eventos.misEventos') }}" style="text-decoration: none; width: 100%;">
            <div style="background: linear-gradient(180deg,#bdbdbd,#e0e0e0); border-radius: 20px; padding: 2.5rem 0; font-size: 2.5rem; color: white; font-weight: bold; box-shadow: 0 2px 8px #ccc; text-align: center; width: 100%; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                Mis eventos
                <span style="margin-left: 1rem; font-size:2.5rem;">&#9651;</span>
            </div>
        </a>
        @endhasrole
        
    </div>
    <div style="position: fixed; bottom: 2rem; right: 2rem;">
        <a href="#" style="background: white; border-radius: 8px; padding: 0.5rem 1rem; border: 2px solid #6c5b7b; font-size: 2rem; color: #6c5b7b; text-decoration: none;">+</a>
    </div>
</div>
@endsection
