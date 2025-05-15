<?php

namespace App\Http\Requests;

use App\Models\CourseOffering;
use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'superadmin';
    }

    public function rules(): array
    {
        return [
            'university_id'     => ['required', 'integer', 'exists:universities,id'],
            'department_id'     => ['required', 'integer', 'exists:departments,id'],
            'course_offering_id'=> ['required', 'integer', 'exists:course_offerings,id'],
            'title'             => ['required', 'string', 'max:255'],
            'exam_type'         => ['required', 'string', 'max:100'],
            'date'              => ['required', 'date'],
            'duration'          => ['required', 'integer', 'min:0'],
            'max_score'         => ['required', 'integer', 'min:0'],
            'weight'            => ['required', 'numeric', 'between:0,100'],
            'description'       => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $universityId = $this->input('university_id');
            $departmentId = $this->input('department_id');
            $courseOfferingId = $this->input('course_offering_id');

            $department = Department::find($departmentId);
            if (!$department || $department->university_id !== $universityId) {
                $validator->errors()->add('department_id', 'The selected department does not belong to the given university.');
            }

            $offering = CourseOffering::find($courseOfferingId);
            if (!$offering || $offering->university_id !== $universityId || $offering->department_id !== $departmentId) {
                $validator->errors()->add('course_offering_id', 'The selected course offering does not match the university and department.');
            }
        });
    }
}

