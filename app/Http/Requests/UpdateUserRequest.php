<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Get user object from route
        $user = $this->route('user');

        return [
            // Validate incoming data for update
            'role' => ['required', Rule::in(['Admin', 'Learner', 'Teacher'])],
            'login' => ['required', Rule::unique('lms_users', 'login')->ignore($user->id, 'id')],
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', Rule::unique('lms_users', 'email')->ignore($user->id, 'id')],
            'status' => ['required', Rule::in(['Active', 'Disabled'])],
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'required|string|digits:4',
            'suburb' => 'required|string|max:255',
        ];
    }
}
