<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class StoreDepartmentRequest extends FormRequest
{
    /**
     * Only superadmins may create departments.
     */
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'superadmin';
    }

    public function rules(): array
    {
        return [
            'name'          => ['required','string','max:255'],
            'code'          => ['required','string','max:50','unique:departments,code'],
            'description'   => ['nullable','string'],
            'university_id' => ['required','integer','exists:universities,id'],
            'head_id'       => ['nullable','integer','exists:professor_profiles,id'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Failed',
            'errors'  => $validator->errors()->toArray(),
        ], 422));
    }
}
