<?php

namespace App\Http\Requests;

use App\Enums\AssignmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAssignmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'university_id'     => ['sometimes', 'required', 'integer', 'exists:universities,id'],
            'department_id'     => ['sometimes', 'required', 'integer', 'exists:departments,id'],
            'course_offering_id'=> ['sometimes', 'required', 'integer', 'exists:course_offerings,id'],
            'title'             => 'sometimes|required|string|max:255',
            'description'       => 'sometimes|nullable|string',
            'due_date'          => 'sometimes|required|date',
            'max_score'         => 'sometimes|required|integer|min:0',
            'assignment_type'   => [
                'sometimes',
                'required',
                Rule::in(AssignmentType::cases())],
        ];
    }
}
