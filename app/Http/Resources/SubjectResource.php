<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubjectResource extends JsonResource
{
    public function toArray($request): array
    {
        $university = $this->whenLoaded('university');
        $department = $this->whenLoaded('department');

        return [
            'id'          => $this->id,
            'code'        => $this->code,
            'name'        => $this->name,
            'description' => $this->description,
            'credits'     => $this->credits,
            'type'        => $this->type,

            'university' => [
                'id'   => $university?->id,
                'name' => $university?->name,
            ],

            'department' => [
                'id'   => $department?->id,
                'name' => $department?->name,
            ],
        ];
    }
}
