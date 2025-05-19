<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        $userType = $this->user()?->getType();
        return in_array($userType, ['teacher', 'student']);
    }

    public function rules(): array
    {
        return [
            'university_id'     => ['required', 'integer', 'exists:universities,id'],
            'department_id'     => ['nullable', 'integer', 'exists:departments,id'],
            'user_id'           => ['required', 'integer', 'exists:users,id'],
            'title'             => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'category'          => ['nullable', 'string', 'max:100'],
            'status'            => ['required', 'in:open,in_review,resolved,closed'],
            'resolution_details'=> ['nullable', 'string'],
            'resolved_by'       => ['nullable', 'integer', 'exists:users,id'],
            'resolved_at'       => ['nullable', 'date'],
        ];
    }
}
