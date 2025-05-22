<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'superadmin';
    }

    public function rules(): array
    {
        return [
            'status'            => ['required', 'in:open,in_review,resolved,closed'],
            'resolution_details'=> ['nullable', 'string']
        ];
    }
}
