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
}
