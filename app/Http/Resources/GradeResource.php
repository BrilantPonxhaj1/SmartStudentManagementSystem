<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GradeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'student' => [
                'id' => $this->studentProfile->id,
                'name' => $this->studentProfile->user->first_name . ' ' . $this->studentProfile->user->last_name,
            ],
            'exam' => $this->exam ? [
                'id' => $this->exam->id,
                'title' => $this->exam->title ?? 'Exam',
            ] : null,
            'assignment' => $this->assignment ? [
                'id' => $this->assignment->id,
                'title' => $this->assignment->title ?? 'Assignment',
            ] : null,
            'score' => $this->score,
            'grade_letter' => $this->grade_letter,
            'remarks' => $this->remarks,
        ];
    }
}
