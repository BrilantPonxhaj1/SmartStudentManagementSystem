<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // If you want to restrict who can update, add logic here.
        // For now, allow all (change to fit your app’s security model).
        return true;
    }

    public function rules(): array
    {
        // Replace these fields with those in your Student model as appropriate.
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name'  => ['sometimes', 'string', 'max:255'],
            'email'      => [
                'sometimes',
                'email',
                'max:255',
                'unique:students,email,' . $this->route('id'),
            ],
            'status'     => ['sometimes', 'in:active,inactive'],
        ];
    }
}
