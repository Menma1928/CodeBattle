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
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|string|in:pendiente,activo,en_calificacion,finalizado',
            'url_imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:204800', // 200 MB = 204800 KB
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
            'fecha_fin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
            'direccion.required' => 'La dirección del evento es obligatoria.',
            'estado.in' => 'El estado debe ser: pendiente, activo o finalizado.',
        ];
    }
}
