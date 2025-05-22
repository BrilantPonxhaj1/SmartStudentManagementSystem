<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                  => $this->id,
            'university_id'       => $this->university_id,
            'department_id'       => $this->department_id,
            'user_id'             => $this->user_id,
            'title'               => $this->title,
            'description'         => $this->description,
            'category'            => $this->category,
            'status'              => $this->status,
            'resolution_details'  => $this->resolution_details,
            'resolved_by'         => $this->resolved_by,
            'resolved_at'         => $this->resolved_at,
        ];
    }
}
