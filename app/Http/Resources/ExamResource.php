<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                => $this->id,
            'title'             => $this->title,
            'exam_type'         => $this->exam_type,
            'date'              => $this->date,
            'duration'          => $this->duration,
            'max_score'         => $this->max_score,
            'weight'            => $this->weight,
            'description'       => $this->description,
            'university_id'     => $this->university_id,
            'department_id'     => $this->department_id,
            'course_offering_id'=> $this->course_offering_id,
        ];
    }
}
