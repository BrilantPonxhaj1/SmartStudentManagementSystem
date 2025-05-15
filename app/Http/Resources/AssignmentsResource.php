<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentsResource extends JsonResource
{
    public function toArray($request) {
        return [
            'id'                => $this->id,
            'university_id'     => $this->university_id,
            'university_name'   => $this->university?->name,
            'department_id'     => $this->department_id,
            'department_name'   => $this->department?->name,
            'course_offering_id'=> $this->course_offering_id,
            'course_offering'   => [
                'subject_id'  => $this->courseOffering?->subject_id,
                'subject_name'=> $this->courseOffering?->subject?->name,
                'section'     => $this->courseOffering?->section,
                'schedule'    => $this->courseOffering?->schedule,
            ],
            'title'             => $this->title,
            'description'       => $this->description,
            'due_date'          => $this->due_date,
            'max_score'         => $this->max_score,
            'assignment_type'   => $this->assignment_type,

            'professor' => new ProfessorResource($this->whenLoaded('professor')),
            'course' => new CourseOfferingResource($this->whenLoaded('course')),
        ];
    }
}
