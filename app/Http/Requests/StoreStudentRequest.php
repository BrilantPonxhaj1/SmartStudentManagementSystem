<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Allow all by default. Add your logic for authorization as needed.
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],
            'password'              => ['required','string','min:8'],
            'university_id'         => ['required','exists:universities,id'],
            'department_id'         => ['required','exists:departments,id'],
            'student_number'        => ['required','string','unique:student_profiles,student_number'],
            'program'               => ['required','string','max:255'],
            'year_of_study'         => ['required','integer','min:1'],
            'enrollment_year'       => ['required','integer','min:2000'],
        ];
    }
}
