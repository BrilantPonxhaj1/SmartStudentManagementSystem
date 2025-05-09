<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UniversityResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name'        => $this->name,
            'code'        => $this->code,
            'address'     => $this->address,
        ];
    }
}
