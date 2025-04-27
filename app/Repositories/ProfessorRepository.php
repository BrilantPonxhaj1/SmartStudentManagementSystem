<?php

namespace App\Repositories;

use App\Models\Professor;
use Illuminate\Database\Eloquent\Model;

class ProfessorRepository extends BaseRepository
{
    public function __construct(Professor $professor)
    {
        parent::__construct($professor);
    }
}
