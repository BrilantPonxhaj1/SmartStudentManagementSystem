<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseOfferingResource extends JsonResource
{
    public function toArray($request): array
    {
        $studentId = optional($request->user()->studentProfile)->id;


        return [
            'id' => $this->id,
            'subject' => [
                'code' => $this->subject->code,
                'name' => $this->subject->name,
            ],
            'professors' => $this->professors->map(fn($prof) => [
                'id' => $prof->id,
                'name' => $prof->user->first_name . ' ' . $prof->user->last_name,
            ]),
            'semester' => $this->semester->name,
            'section' => $this->section,
            'schedule' => $this->schedule,
            'capacity' => $this->capacity,
            'enrolled_count' => $this->enrollments->count(),
            'enrolled' => $this->enrollments
                ->contains('student_profile_id', $studentId),
        ];
    }

}
