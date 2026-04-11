<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //Only admin add the user
        return auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //validate incoming user data
            'role' => ['required', Rule::in(['Admin', 'Learner', 'Teacher'])],
            'login' => 'required|string|unique:lms_users,login|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|unique:lms_users,login',
            'password' => ['required', Password::min(8)],
            'status' => ['required', Rule::in('Active', 'Disabled')],
            'birth_date' => 'required|date',
            'phone' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'required|string|digits:4',
            'suburb'   => 'required|string|max:255',
        ];
    }
}
