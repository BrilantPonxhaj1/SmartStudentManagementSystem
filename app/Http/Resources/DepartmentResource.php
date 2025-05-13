<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'code'        => $this->code,
            'description' => $this->description,

            'university' => [
                'id'   => $this->university->id,
                'name' => $this->university->name,
            ],

            'head' => $this->head
                ? [
                    'id'    => $this->head->id,
                    'name'  => $this->head->user->name,
                    'email' => $this->head->user->email,
                ]
                : null,
        ];
    }
}
