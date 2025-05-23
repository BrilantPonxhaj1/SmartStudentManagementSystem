<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                   => $this->id,
            'university_id'        => $this->university_id,
            'department_id'        => $this->department_id,
            'student_profile_id'   => $this->student_profile_id,
            'professor_profile_id' => $this->professor_profile_id,
            'appointment_time'     => $this->appointment_time,
            'location'             => $this->location,
            'purpose'              => $this->purpose,
            'status'               => $this->status,
            'requested_by'         => $this->requested_by,
            'notes'                => $this->notes,

            'professor_profile' => $this->whenLoaded('professorProfile', function () {
                return [
                    'id' => $this->professorProfile->id,
                    'user' => $this->professorProfile->user
                        ? [
                            'first_name' => $this->professorProfile->user->first_name,
                            'last_name' => $this->professorProfile->user->last_name
                        ]
                        : null
                ];
            }),
        ];
    }
}
