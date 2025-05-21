<?php

namespace App\Repositories;

use App\Models\Grade;

class GradeRepository extends BaseRepository
{
    public function  __construct(Grade $grade)
    {
        parent::__construct($grade);

    }
    public function list()
    {
        return $this->model->with(['studentProfile', 'exam', 'assignment'])->get();
    }

}
