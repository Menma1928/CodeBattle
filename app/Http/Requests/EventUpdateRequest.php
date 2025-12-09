<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('evento'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $evento = $this->route('evento');
        
        // La fecha de inicio solo es requerida y editable si el evento está pendiente
        $fechaInicioRules = 'nullable|date|after_or_equal:today';
        if ($evento && $evento->estado === 'pendiente') {
            $fechaInicioRules = 'required|date|after_or_equal:today';
        }
        
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'fecha_inicio' => $fechaInicioRules,
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'direccion' => 'required|string|max:255',
            // 'estado' se actualiza automáticamente según las fechas, no desde el formulario
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:204800', // 200 MB = 204800 KB
            'reglas' => 'nullable|array',
            'reglas.*' => 'nullable|string|max:500',
            'requisitos' => 'nullable|array',
            'requisitos.*' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del evento es obligatorio.',
            'descripcion.required' => 'La descripción del evento es obligatoria.',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'direccion.required' => 'La dirección del evento es obligatoria.',
        ];
    }
}
