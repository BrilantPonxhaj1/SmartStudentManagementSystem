<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseOfferingRequest extends  FormRequest
{
    public function rules(): array
    {
        return [
            'university_id' => 'sometimes|required|exists:universities,id',
            'department_id' => 'sometimes|required|exists:departments,id',
            'subject_id' => 'sometimes|required|exists:subjects,id',
            'professor_profile_id' => 'sometimes|required|exists:professor_profiles,id',
            'semester_id' => 'sometimes|required|exists:semesters,id',
            'section' => 'nullable|string|max:5',
            'schedule' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1'
        ];
    }
}
