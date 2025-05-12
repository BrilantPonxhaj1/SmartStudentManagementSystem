<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseOfferingResource extends JsonResource
{
    public function toArray($request): array
    {
        $studentId = $request->user()->studentProfile->id;

        $myEnrollment = $this->enrollments->first(fn($e) => $e->student_profile_id === $studentId && $e->status === 'active');

        $activeCount = $this->enrollments
            ->where('status', 'active')
            ->count();

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
            'professors'      => $this->professors->map(fn($prof) => [
                'id'   => $prof->id,
                'name' => $prof->user->first_name . ' ' . $prof->user->last_name,
            ]),
            'semester' => $this->semester->name,
            'section' => $this->section,
            'schedule' => $this->schedule,
            'capacity' => $this->capacity,
            'enrolled_count' => $activeCount,
            'enrolled'        => (bool) $myEnrollment,
            'enrollment_id'   => $myEnrollment?->id,
            'professorat' => [
                'id' => $this->professor_profile_id,
                'name' => $this->professorProfile->user->first_name . ' ' . $this->professorProfile->user->last_name,
            ],
        ];
    }

}
