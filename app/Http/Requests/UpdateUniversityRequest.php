<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUniversityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'superadmin';
    }

    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:255'],
            'code'    => ['required', 'string', 'max:50', 'unique:universities,code,' . $this->route('id')],
            'address' => ['required', 'string', 'max:255'],
        ];
    }
}
