@extends('layouts.app')

@section('content')
<div style="background: #ede9f3; min-height: 100vh; padding: 2rem;">
    <div style="background: #6c5b7b; color: white; padding: 1rem; border-radius: 10px; margin-bottom: 2rem; max-width: 900px;">
        <h2 style="margin: 0;">Crear Evento</h2>
    </div>
    <form style="background: linear-gradient(180deg,#bdbdf3,#bdbdbd); border-radius: 20px; padding: 2rem; max-width: 700px; margin: auto; box-shadow: 0 2px 8px #ccc;">
        <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1rem;">
            <img src="{{ asset('icons/logo-placeholder.png') }}" alt="Logo" style="width:80px;height:80px;object-fit:contain;opacity:0.5;">
            <div>
                <label style="font-weight:bold;">Logo</label><br>
                <button type="button" style="background:#ede9f3;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;box-shadow:0 1px 4px #ccc;">Subir imagen</button>
            </div>
        </div>
        <div style="margin-bottom: 1rem;"><input type="text" name="nombre" placeholder="Nombre" style="width:100%;padding:0.75rem;border-radius:8px;border:none;background:#ede9f3;font-size:1.1rem;"></div>
        <div style="margin-bottom: 1rem;"><input type="text" name="categoria" placeholder="Categoría" style="width:100%;padding:0.75rem;border-radius:8px;border:none;background:#ede9f3;font-size:1.1rem;"></div>
        <div style="margin-bottom: 1rem;"><input type="text" name="institucion" placeholder="Institución" style="width:100%;padding:0.75rem;border-radius:8px;border:none;background:#ede9f3;font-size:1.1rem;"></div>
        <div style="margin-bottom: 1rem;display:flex;gap:1rem;align-items:center;">
            <button type="button" style="display:flex;align-items:center;gap:0.5rem;background:#ede9f3;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;box-shadow:0 1px 4px #ccc;">
                <img src="{{ asset('icons/integrantes.png') }}" alt="Integrantes" style="width:32px;height:32px;">
                Integrantes
            </button>
            <button type="button" style="display:flex;align-items:center;gap:0.5rem;background:#ede9f3;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;box-shadow:0 1px 4px #ccc;">
                <img src="{{ asset('icons/fecha.png') }}" alt="Fecha" style="width:32px;height:32px;">
                Fecha
            </button>
        </div>
        <div style="margin-bottom: 1rem;"><textarea name="descripcion" placeholder="Descripción" style="width:100%;padding:0.75rem;border-radius:8px;border:none;background:#ede9f3;font-size:1.1rem;"></textarea></div>
        <div style="display:flex;gap:1rem;justify-content:flex-end;">
            <button type="submit" style="background:#fff;border:none;padding:0.5rem 1.5rem;border-radius:8px;box-shadow:0 1px 4px #ccc;cursor:pointer;">Crear</button>
            <button type="reset" style="background:#fff;border:none;padding:0.5rem 1.5rem;border-radius:8px;box-shadow:0 1px 4px #ccc;cursor:pointer;">Limpiar</button>
        </div>
    </form>
</div>
@endsection
