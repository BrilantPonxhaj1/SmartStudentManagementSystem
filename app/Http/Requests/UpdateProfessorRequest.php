<?php

namespace App\Http\Requests;

use App\Models\Professor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class UpdateProfessorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only superadmins can update professors.
        return $this->user()?->getType() === 'superadmin';
    }


    public function rules(): array
    {
        $professorId = $this->route('id');
        $professor = Professor::find($professorId);
        $userId = $professor?->user_id;

        return [
            'first_name' => ['required','string','max:255'],
            'last_name'  => ['required','string','max:255'],
            'email'      => [
                'required',
                'email',
                Rule::unique('users','email')->ignore($userId),
            ],
            'university_id' => ['required', 'integer', 'exists:universities,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'password'       => ['nullable', 'string', 'min:6'],
            'specialization' => ['required', 'string', 'max:255'],
            'academic_role'  => ['required', 'string', 'max:255'],
        ];
    }
}
