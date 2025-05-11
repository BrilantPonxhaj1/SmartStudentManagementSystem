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
            'professorat' => [
                'id' => $this->professor_profile_id,
                'name' => $this->professorProfile->user->first_name . ' ' . $this->professorProfile->user->last_name,
            ],
        ];
    }

}
