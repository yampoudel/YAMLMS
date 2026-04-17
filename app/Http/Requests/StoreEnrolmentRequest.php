<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnrolmentRequest extends FormRequest
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
            // Validate incoming data for enrolment
            'course_id' => [
                'required',
                'exists:lms_courses,id',
                // checke $cours_id + $user_id is already available in enrolment table
                Rule::unique('lms_enrolments')->where(function ($query) use ($user) {
                    return $query->where('user_id', $user->id);
                }),
            ],
        ];
    }

    // Custom error message
    public function messages(): array
    {
        return [
            'course_id.unique' => 'This student is already enrolled in this course',
        ];
    }
}
