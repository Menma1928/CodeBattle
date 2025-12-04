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
        </div>
        <div style="margin-bottom: 1rem;"><textarea name="descripcion" placeholder="Descripción" style="width:100%;padding:0.75rem;border-radius:8px;border:none;background:#ede9f3;font-size:1.1rem;min-height:100px;"></textarea></div>
        
        <!-- Sección de Reglas -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: #6c5b7b; margin-bottom: 1rem;">Reglas del Evento</h3>
            <div id="reglas-container">
                <div style="margin-bottom: 0.5rem;">
                    <input type="text" name="reglas[]" placeholder="Escribe una regla" style="width:100%;padding:0.75rem;border-radius:8px;border:none;background:#ede9f3;font-size:1rem;">
                </div>
            </div>
            <button type="button" onclick="addRegla()" style="background:#6c5b7b;color:white;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;margin-top:0.5rem;">+ Agregar Regla</button>
        </div>
        
        <!-- Sección de Requisitos -->
        <div style="margin-bottom: 2rem;">
            <h3 style="color: #6c5b7b; margin-bottom: 1rem;">Requisitos del Evento</h3>
            <div id="requisitos-container">
                <div style="margin-bottom: 0.5rem;">
                    <input type="text" name="requisitos[]" placeholder="Escribe un requisito" style="width:100%;padding:0.75rem;border-radius:8px;border:none;background:#ede9f3;font-size:1rem;">
                </div>
            </div>
            <button type="button" onclick="addRequisito()" style="background:#6c5b7b;color:white;border:none;padding:0.5rem 1rem;border-radius:8px;cursor:pointer;margin-top:0.5rem;">+ Agregar Requisito</button>
        </div>
        
        <div style="display:flex;gap:1rem;justify-content:flex-end;">
            <button type="submit" style="background:#fff;border:none;padding:0.5rem 1.5rem;border-radius:8px;box-shadow:0 1px 4px #ccc;cursor:pointer;">Crear</button>
            <button type="reset" style="background:#fff;border:none;padding:0.5rem 1.5rem;border-radius:8px;box-shadow:0 1px 4px #ccc;cursor:pointer;">Limpiar</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function addRegla() {
    const container = document.getElementById('reglas-container');
    const div = document.createElement('div');
    div.style.marginBottom = '0.5rem';
    div.innerHTML = `
        <input type="text" name="reglas[]" placeholder="Escribe una regla" style="width:100%;padding:0.75rem;border-radius:8px;border:none;background:#ede9f3;font-size:1rem;">
        <button type="button" onclick="this.parentElement.remove()" style="background:#ff6b6b;color:white;border:none;padding:0.25rem 0.5rem;border-radius:4px;cursor:pointer;margin-left:0.5rem;">×</button>
    `;
    container.appendChild(div);
}

function addRequisito() {
    const container = document.getElementById('requisitos-container');
    const div = document.createElement('div');
    div.style.marginBottom = '0.5rem';
    div.innerHTML = `
        <input type="text" name="requisitos[]" placeholder="Escribe un requisito" style="width:100%;padding:0.75rem;border-radius:8px;border:none;background:#ede9f3;font-size:1rem;">
        <button type="button" onclick="this.parentElement.remove()" style="background:#ff6b6b;color:white;border:none;padding:0.25rem 0.5rem;border-radius:4px;cursor:pointer;margin-left:0.5rem;">×</button>
    `;
    container.appendChild(div);
}
</script>
@endpush
