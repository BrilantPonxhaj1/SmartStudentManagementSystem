<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray($request): array {
    return [
        'id' => $this->id,
        'student' => [
            'id' => $this->studentProfile->id,
            'name' => $this->studentProfile->user->first_name . ' ' . $this->studentProfile->user->last_name,
        ],
        'course_offering' => [
            'id' => $this->courseOffering->id,
            'subject' => [
                'code' => $this->courseOffering->subject->code,
                'name' => $this->courseOffering->subject->name,
            ],
            'semester' => $this->courseOffering->semester->name,
            'professor' => [
                'id' => $this->courseOffering->professorProfile?->user?->id,
                'name' => $this->courseOffering->professorProfile?->user?->first_name . ' ' . $this->courseOffering?->professorProfile?->user?->last_name,
            ],
        ],
        'status' => $this->status,
        'final_grade' => $this->final_grade,
    ];

    }
}
