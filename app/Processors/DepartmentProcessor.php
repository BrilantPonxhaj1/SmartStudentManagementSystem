<?php

namespace App\Processors;

use App\Repositories\DepartmentRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;


class DepartmentProcessor extends BaseProcessor
{
    public function __construct(DepartmentRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    /**
     * Return id + name for departments in a university.
     */
    public function listByUniversityId(int $uni): Collection
    {
        return $this->repo->listByUniversityId($uni);
    }
    //List for select krejt universitetet listByUniId osht veq departmentet baaz te id s universitetit
}
