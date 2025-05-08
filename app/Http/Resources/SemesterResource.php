<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SemesterResource extends JsonResource
{
    public function toArray (Request $request)
    {
        return [
            'id'                     => $this->id,
            'university_id'          => $this->university_id,
            'name'                   => $this->name,
            'start_date'             => $this->start_date->toDateString(),
            'end_date'               => $this->end_date->toDateString(),
            'registration_deadline'  => $this->registration_deadline?->toDateString(),
            'description'            => $this->description,
            'created_at'             => $this->created_at->toDateTimeString(),
            'updated_at'             => $this->updated_at->toDateTimeString(),
        ];
    }
}
