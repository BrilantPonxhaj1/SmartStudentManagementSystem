<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseOfferingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'university_id' => 'required|exists:universities,id',
            'department_id' => 'required|exists:departments,id',
            'subject_id' => 'required|exists:subjects,id',
            'professor_profile_id' => 'required|exists:professor_profiles,id',
            'semester_id' => 'required|exists:semesters,id',
            'section' => 'nullable|string|max:5',
            'schedule' => 'nullable|string|max:255',
            'capacity' => 'nullable|integer|min:1'
        ];
    }
}
