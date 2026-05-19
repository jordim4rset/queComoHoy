<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:30',
            'description' => 'required|string',
            'time' => 'nullable|numeric|min:0',
            'tags' => 'nullable|string',
            'image' => 'nullable|image',
            'video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 30 caracteres.',
            'description.required' => 'La descripción es obligatoria.',
            'time.numeric' => 'El tiempo debe ser un número.',
            'time.min' => 'El tiempo no puede ser negativo.',
            'image.image' => 'El archivo debe ser una imagen válida.',
            'video.mimes' => 'El vídeo debe ser de tipo mp4, mov, avi o webm.',
            'video.max' => 'El vídeo no puede pesar más de 50 MB.',
        ];
    }
}
