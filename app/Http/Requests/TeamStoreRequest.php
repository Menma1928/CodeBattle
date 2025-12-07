<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Team;

class TeamStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Team::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:1000',
            'url_banner' => 'nullable|image|mimes:jpg,jpeg,png|max:204800', // 200 MB = 204800 KB
            'event_id' => 'required|exists:events,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del equipo es obligatorio.',
            'descripcion.required' => 'La descripción del equipo es obligatoria.',
            'event_id.required' => 'Debes seleccionar un evento.',
            'event_id.exists' => 'El evento seleccionado no existe.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'nombre' => 'nombre del equipo',
            'descripcion' => 'descripción',
            'url_banner' => 'URL del banner',
            'event_id' => 'evento',
        ];
    }
}
