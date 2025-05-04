<?php

namespace App\Http\Requests;

use App\Models\Professor;
use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class UpdateStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'superadmin';
    }


    public function rules(): array
    {
        $studentId = $this->route('id');
        $student = Student::find($studentId);
        $userId = $student?->user_id;
        return[
            'first_name'             => ['required','string','max:255'],
            'last_name'              => ['required','string','max:255'],
            'email'                  => [
                'required','email','max:255',
                Rule::unique('users','email')->ignore($userId),
            ],
            'password'               => ['sometimes','string','min:8'],
            'type'                   => ['sometimes','in:student,professor,superadmin'],

            'university_id'          => ['required','exists:universities,id'],
            'department_id'          => ['required','exists:departments,id'],
            'program'                => ['required','string','max:255'],

            'student_number'         => [
                'required','string','max:50',
                Rule::unique('student_profiles','student_number')
                    ->ignore($userId),
            ],
            'year_of_study'          => ['required','integer','min:1'],
            'enrollment_year'        => ['required','integer','min:2000'],

        ];
    }
}
