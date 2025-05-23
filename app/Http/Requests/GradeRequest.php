<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'professor';
    }

    public function rules(): array
    {
        return [
            'university_id' => 'required|exists:universities,id',
            'department_id' => 'required|exists:departments,id',
            'student_profile_id' => 'required|exists:students,id',
            'exam_id' => 'nullable|exists:exams,id',
            'assignment_id' => 'nullable|exists:assignments,id',
            'score' => 'required|numeric|min:0|max:100',
            'grade_letter' => 'nullable|string|max:5',
            'remarks' => 'nullable|string|max:1000',
        ];
    }
}
{

}
