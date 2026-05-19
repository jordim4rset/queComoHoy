<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|string',
            'password' => 'required|string',
            'remember' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo o usuario es obligatorio.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }
}
