<?php

namespace App\Repositories;

use App\Models\Semesters;

class SemesterRepository extends BaseRepository
{
    public function __construct(Semesters $semester) {
        parent::__construct($semester);
    }

    public function currentTerm(){
        return $this->model
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
    }
    public function checkSemesterDateOverlapping(array $data): bool
    {
        return $this->model->newQuery()
            ->where('university_id', $data['university_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']]);
            })
            ->exists();
    }
}
