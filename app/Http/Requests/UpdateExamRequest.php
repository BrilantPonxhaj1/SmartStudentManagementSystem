<?php

namespace App\Http\Requests;


use App\Models\CourseOffering;
use App\Models\Department;
use Illuminate\Foundation\Http\FormRequest;

class UpdateExamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->getType() === 'teacher';
    }

    public function rules(): array
    {
        return [
            'title'              => ['required', 'string', 'max:255'],
            'exam_type'          => ['required', 'string', 'max:100'],
            'date'               => ['required', 'date'],
            'duration'           => ['required', 'integer', 'min:1'],
            'max_score'          => ['required', 'integer', 'min:1', 'max:100'],
            'weight'             => ['required', 'numeric', 'between:0,100'],
            'description'        => ['nullable', 'string'],
        ];
    }

}
