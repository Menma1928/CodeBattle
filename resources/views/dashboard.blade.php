@extends('layouts.app')

@section('content')
<div style="background: #ede9f3; min-height: 100vh; padding: 2rem; display: flex; flex-direction: column; align-items: center;">
    <div style="background: #6c5b7b; color: white; padding: 1rem; border-radius: 10px; margin-bottom: 2rem; width: 100%; max-width: 600px;">
        <h2 style="margin: 0;">
            @hasrole('Super Admin')
                Super Administrador
            @elsehasrole('Administrador')
                Administrador
            @else
                Participante
            @endhasrole</h2>
    </div>
    <div style="display: flex; flex-direction: column; gap: 2rem; align-items: flex-start; width: 100%; max-width: 1200px;">

        <!-- botones -->

        <a href="{{ route('eventos.index') }}" style="text-decoration: none; width: 100%;">
            <div style="background: linear-gradient(180deg,#bdbdbd,#e0e0e0); border-radius: 20px; padding: 2.5rem 0; font-size: 2.5rem; color: white; font-weight: bold; box-shadow: 0 2px 8px #ccc; text-align: center; width: 100%; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                Eventos
                <span style="margin-left: 1rem; font-size:2.5rem;">&#9651;</span>
            </div>
        </a>

        @hasrole('Super Admin')
        <a href="{{ route('eventos.index') }}" style="text-decoration: none; width: 100%;">
            <div style="background: linear-gradient(180deg,#bdbdbd,#e0e0e0); border-radius: 20px; padding: 2.5rem 0; font-size: 2.5rem; color: white; font-weight: bold; box-shadow: 0 2px 8px #ccc; text-align: center; width: 100%; display: flex; align-items: center; justify-content: center; gap: 1rem;">
                Equipos
                <span style="margin-left: 1rem; font-size:2.5rem;">&#9651;</span>
            </div>
        </a>
        @endhasrole

        @hasrole('participante')
        <a href="{{ route('eventos.index') }}" style="text-decoration: none; width: 100%;">
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
        <a href="{{ route('eventos.index') }}" style="text-decoration: none; width: 100%;">
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
