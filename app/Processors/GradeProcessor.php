<?php

namespace App\Processors;

use App\Repositories\GradeRepository;
use Illuminate\Database\DatabaseManager;

class GradeProcessor extends BaseProcessor
{
    public function __construct(GradeRepository $repo,DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function list()
    {
        return $this->repo->list();
    }
}
