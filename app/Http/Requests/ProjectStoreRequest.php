<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled in the controller with team context
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'github_url' => 'nullable|url|max:255',
            'team_id' => 'required|exists:teams,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del proyecto es obligatorio.',
            'descripcion.required' => 'La descripción del proyecto es obligatoria.',
            'github_url.url' => 'La URL de GitHub debe ser válida.',
            'team_id.required' => 'Debes seleccionar un equipo.',
            'team_id.exists' => 'El equipo seleccionado no existe.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del proyecto',
            'descripcion' => 'descripción',
            'github_url' => 'URL de GitHub',
            'team_id' => 'equipo',
        ];
    }
}
