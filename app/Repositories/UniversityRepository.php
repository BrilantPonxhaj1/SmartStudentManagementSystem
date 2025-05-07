<?php

namespace App\Repositories;

use App\Models\University;

class UniversityRepository extends BaseRepository
{
    public function __construct(University $university)
    {
        parent::__construct($university);
    }

    /*
     *
     * @return \Illuminate\Support\Collection<int, University>
     */
    public function listForSelect() {
        return $this->newQuery()
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

}
