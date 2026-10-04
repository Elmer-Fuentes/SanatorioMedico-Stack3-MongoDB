<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SucursalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:150',
            'direccion' => 'required|string|max:250',
            'telefono' => 'required|string|max:20',
            'correo' => 'required|email|max:150',
            'ciudad' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'fecha_apertura' => 'required|date',
            'tipo_sucursal' => 'required|string|max:50',
            'estado' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'email' => 'El campo :attribute debe contener un correo válido.',
            'date' => 'El campo :attribute debe contener una fecha válida.',
            'boolean' => 'El campo :attribute debe ser verdadero o falso.',
            'max' => 'El campo :attribute excede la longitud permitida.',
        ];
    }
}
