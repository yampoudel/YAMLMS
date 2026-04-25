<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\validation\Rule;

class UpdateLessonRequest extends FormRequest
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
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['string', Rule::in(['Active', 'Disabled'])],
            'type' => ['string', Rule::in(['Default', 'Survey', 'Quiz'])],
            'course_id' => [
                'required',
                Rule::exists('lms_courses', 'id')->where(function ($query) {
                    if (! auth()->user()->isAdmin()) {
                        $query->where('created_by', auth()->id());
                    }
                }),
            ],
            'content' => ['required', 'string'],
        ];
    }
}
