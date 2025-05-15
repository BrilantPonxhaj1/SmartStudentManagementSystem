<?php

namespace App\Processors;

use App\Repositories\AssignmentRepository;
use Illuminate\Database\DatabaseManager;

class AssignmentProcessor extends BaseProcessor
{
    public function __construct( AssignmentRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function listWithRelations() {
        return $this->repo->allWithRelations();
    }
    public function get(int $id) {
        return $this->repo->findWithRelations($id);
    }


}
