<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseOfferingResource extends JsonResource
{
    public function toArray($request) {
        return [
            'id' => $this->id,
            'university' => [
                'id' => $this->university->id,
                'name' => $this->university->name,
            ],
            'department' => [
                'id' => $this->department->id,
                'name' => $this->department->name,
            ],
            'subject' => [
                'code' => $this->subject->code,
                'name' => $this->subject->name,
            ],
            'semester' => $this->semester->name,
            'section' => $this->section,
            'schedule' => $this->schedule,
            'capacity' => $this->capacity,
            'professors' => [
                'id' => $this->professor_profile_id,
                'name' => $this->professorProfile?->user?->first_name . ' ' . $this->professorProfile?->user?->last_name,
            ],
        ];
    }
}
