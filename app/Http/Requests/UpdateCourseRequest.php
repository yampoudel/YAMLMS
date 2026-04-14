<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //Get the course update from the route
        $course = $this->route('course');

        // This uses the 'update' rule from your CoursePolicy automatically!
        return $this->user()->can('update', $course);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //Validate incoming data for update
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ];
    }
}
