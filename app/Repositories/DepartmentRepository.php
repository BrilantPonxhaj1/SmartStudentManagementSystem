<?php

namespace App\Repositories;


use App\Models\Department;
use Illuminate\Database\Eloquent\Collection;

class DepartmentRepository extends BaseRepository
{
    public function __construct(Department $department)
    {
        parent::__construct($department);
    }

    /**
     * List departments for a given university (id + name only).
     *
     * @param int $universityId
     * @return Collection<int,Department>
     */
    public function listByUniversityId(int $universityId): Collection
    {
        return $this->newQuery()
                    ->where('university_id', $universityId)
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get();
    }
}
