<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Validator;

class StoreProfessorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only superadmins can create professors.
        return $this->user()?->getType() === 'superadmin';
    }


    public function rules(): array
    {
        return [
            'first_name'       => ['required', 'string', 'max:255'],
            'last_name'        => ['required', 'string', 'max:255'],
            'email'            => ['required', 'email', 'unique:users,email'],
            'password'         => ['required', 'string', 'min:6'],
            'university_id'    => ['required', 'integer', 'exists:universities,id'],
            'department_id'    => ['required', 'integer', 'exists:departments,id'],
            'employee_number'  => ['required', 'string', 'max:255'],
            'specialization'   => ['required', 'string', 'max:255'],
            'academic_role'    => ['required', 'string', 'max:255'],
        ];
    }
    protected function failedValidation(Validator|\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Failed',
            'errors'  => $validator->errors()->toArray(),
        ], 422));
    }
}
