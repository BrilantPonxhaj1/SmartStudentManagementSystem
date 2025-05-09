<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSubjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'superadmin';
    }

    public function rules(): array
    {
        return [
            'university_id' => ['required', 'integer', 'exists:universities,id'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'code'          => ['required', 'string', 'max:50'],
            'name'          => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string'],
            'credits'       => ['required', 'integer', 'min:0'],
            'type'          => ['required', 'string', 'max:100'],
        ];
    }
}
