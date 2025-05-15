<?php

namespace App\Http\Requests;

use App\Enums\AssignmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssignmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'university_id'     => ['required', 'integer', 'exists:universities,id'],
            'department_id'     => ['required', 'integer', 'exists:departments,id'],
            'course_offering_id'=> ['required', 'integer', 'exists:course_offerings,id'],
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'due_date'          => 'required|date',
            'max_score'         => 'required|integer|min:0',
            'assignment_type'   => ['required', Rule::in(AssignmentType::cases())],
        ];
    }
}
