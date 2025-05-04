<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'student_number'   => $this->student_number,
            'program'          => $this->program,
            // …
            'university_id'    => $this->university_id,
            'department_id'    => $this->department_id,
            'first_name'       => $this->user->first_name,
            'last_name'        => $this->user->last_name,
            'year_of_study'    => $this->year_of_study,
            'enrollment_year'  => $this->enrollment_year,
            'email'            => $this->user->email,
            'university'       => $this->university->name,
            'department'       => $this->department->name,
        ];
    }

}
