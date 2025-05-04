<?php

namespace App\Processors;

use App\Repositories\UniversityRepository;
use Faker\Provider\Base;
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
