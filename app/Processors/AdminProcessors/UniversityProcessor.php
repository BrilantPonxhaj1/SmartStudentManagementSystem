<?php

namespace App\Processors\AdminProcessors;

use App\Processors\BaseProcessor;
use App\Repositories\UniversityRepository;
use Illuminate\Database\DatabaseManager;

class UniversityProcessor extends BaseProcessor
{
    public function __construct(UniversityRepository $uniRepo, DatabaseManager $db) {
        parent::__construct($uniRepo, $db);
    }


    /**
     * Return name+id for all universities
     */
    public function listForSelect()
    {
        return $this->repo->listForSelect();
    }
}
