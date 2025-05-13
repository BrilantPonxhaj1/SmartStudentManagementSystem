<?php

namespace App\Http\Requests;

use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;

class UpdateDepartmentRequest extends FormRequest
{
    /**
     * Only superadmins may update departments.
     */
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'superadmin';
    }

    public function rules(): array
    {
        $deptId = $this->route('id');
        return [
            'name'          => ['required','string','max:255'],
            'code'          => [
                'required','string','max:50',
                Rule::unique('departments','code')->ignore($deptId),
            ],
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
