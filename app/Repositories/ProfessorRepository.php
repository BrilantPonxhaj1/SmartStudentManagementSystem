<?php

namespace App\Repositories;

use App\Models\Professor;

class ProfessorRepository extends BaseRepository
{
    public function __construct(Professor $model)
    {
        parent::__construct($model);
    }

    // Add ProfessorProfile-specific queries here if needed
}
