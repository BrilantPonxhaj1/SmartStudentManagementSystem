<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'teacher';
    }

    public function rules(): array
    {
        return [
            'appointment_time'      => ['required', 'date'],
            'location'              => ['required', 'string', 'max:255'],
            'purpose'               => ['required', 'string'],
            'status'                => ['required', 'string', 'max:50'],
            'requested_by'          => ['required', 'integer', 'exists:users,id'],
            'notes'                 => ['nullable', 'string'],
        ];
    }
}
