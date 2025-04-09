<?php

namespace App\Http\Requests;

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
        $id = $this->route('id');
        return [
            'first_name' => ['required','string','max:255'],
            'last_name'  => ['required','string','max:255'],
            'email'      => [
                'required',
                'email',
                Rule::unique('users','email')->ignore($id),
            ],
            'phone'      => ['required','string','max:20'],
            'password'   => ['nullable','string','min:8'],
            'role'       => ['required','string','max:255'],
        ];
    }
}
