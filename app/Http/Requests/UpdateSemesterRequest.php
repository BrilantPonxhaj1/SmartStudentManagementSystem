<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'superadmin';
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required','string','max:255'],
            'start_date'           => ['required','date'],
            'end_date'             => ['required','date','after_or_equal:start_date'],
            'registration_deadline'=> ['nullable','date','before_or_equal:start_date'],
            'description'          => ['nullable','string'],
        ];
    }
}
