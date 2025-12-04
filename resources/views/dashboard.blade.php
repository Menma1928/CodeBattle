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
        </div>
        <!-- Aquí puedes renderizar el contenido principal del dashboard si es necesario -->
        <div style="width: 100%; background: linear-gradient(180deg,#bdbdbd,#e0e0e0); border-radius: 20px; padding: 2.5rem 0; font-size: 1.3rem; color: white; font-weight: bold; box-shadow: 0 2px 8px #ccc; text-align: center; display: flex; align-items: center; justify-content: center; margin-bottom: 2rem;">
            <span style="width: 100%; color: white; font-size: 1.1rem; font-weight: normal;">Selecciona una opción en la barra superior para continuar.</span>
        </div>
    </div>
</div>
@endsection
