<?php

namespace App\Processors\AdminProcessors;

use App\Processors\BaseProcessor;
use App\Repositories\DepartmentRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;


class DepartmentProcessor extends BaseProcessor
{
    public function __construct(DepartmentRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function list()
    {
        return $this->repo->allWithRelations();
    }

    /**
     * Override get() to include head & university.
     */
    public function get(int $id)
    {
        return $this->repo->findWithRelations($id);
    }

    /**
     * Expose the university-scoped list.
     */
    public function listByUniversity(int $universityId)
    {
        return $this->repo->listByUniversityId($universityId);
    }

}
