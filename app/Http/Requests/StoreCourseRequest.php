<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth()->user();

        // Only admin/and teacher can add the course
        return $user->isAdmin() || $user->role === 'Teacher';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // validate incoming course data
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'image_path' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'], // Max 2MB of file
        ];
    }
}
