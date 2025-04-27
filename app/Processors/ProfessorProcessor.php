<?php
namespace App\Processors;

use App\Processors\BaseProcessor;
use App\Repositories\ProfessorRepository;
use Illuminate\Database\DatabaseManager;

class ProfessorProcessor extends BaseProcessor
{
    public function __construct(ProfessorRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    // Any extra business logic for professors here
}
