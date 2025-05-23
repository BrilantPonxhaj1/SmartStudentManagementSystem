<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'student';
    }

    public function rules(): array
    {
        return [
            'university_id'         => ['required', 'integer', 'exists:universities,id'],
            'department_id'         => ['required', 'integer', 'exists:departments,id'],
            'student_profile_id'    => ['required', 'integer', 'exists:student_profiles,id'],
            'professor_profile_id'  => ['required', 'integer', 'exists:professor_profiles,id'],
            'appointment_time'      => ['required', 'date'],
            'location'              => ['required', 'string', 'max:255'],
            'purpose'               => ['required', 'string'],
            'status'                => ['required', 'string', 'in:scheduled,completed,canceled'],
            'requested_by'          => ['required', 'integer', 'exists:users,id'],
            'notes'                 => ['nullable', 'string'],
        ];
    }
}
