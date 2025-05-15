<?php

namespace App\Repositories;

use App\Models\Assignment;

class AssignmentRepository extends BaseRepository
{
    public function __construct(Assignment $assignment)
    {
        parent::__construct($assignment);
    }

    public function allWithRelations() {
        return $this->model
            ->with([
                'university',
                'department',
                'courseOffering',
            ])->get();
    }
    public function findWithRelations(int $id) {
        return $this->model
            ->with([
                'university',
                'department',
                'courseOffering',
            ])->findOrFail($id);
    }

}
