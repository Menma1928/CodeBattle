<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('project'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $project = $this->route('project');
        $project->load('team.event');
        $eventIsActive = $project->team->event->estado === 'activo';

        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
        ];

        // Only allow github_url update if event is active
        if ($eventIsActive) {
            $rules['github_url'] = 'nullable|url|max:255';
        }

        return $rules;
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
        ];
    }
}
