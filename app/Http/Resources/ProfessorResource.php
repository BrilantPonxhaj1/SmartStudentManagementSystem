<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfessorResource extends JsonResource
{

    public function toArray($request): array
    {
        $user = $this->whenLoaded('user');

        return [
            'id'             => $this->id,
            'uid'            => $user->id,                        // from users table
            'first_name'     => $user->first_name,
            'last_name'      => $user->last_name,
            'email'          => $user->email,

            'specialization' => $this->specialization,            // from professor_profiles
            'academic_role'  => $this->academic_role,
        ];
    }

}
