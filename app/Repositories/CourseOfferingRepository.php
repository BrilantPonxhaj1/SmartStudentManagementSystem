<?php

namespace App\Repositories;

use App\Models\CourseOffering;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class CourseOfferingRepository extends BaseRepository
{
    public function __construct(CourseOffering $courseOffering)
    {
        parent::__construct($courseOffering);
    }

    public function create(array $data): CourseOffering
    {
        return $this->model->create($data);
    }
    public function findBySemester(int $semesterId)
    {
        $user    = auth()->user();
        $profile = $user->type === 'student'
            ? $user->studentProfile
            : $user->professorProfile;

        // 1) Start a query with *no* global scopes
        $q = $this->model
            ->newQueryWithoutScopes()
            ->where('semester_id', $semesterId);

        // 2) Manually scope to the profile’s tenant IDs
        if ($profile) {
            $q->where('university_id', $profile->university_id)
                ->where('department_id', $profile->department_id);
        }

        // 3) Eager‐load relations
        return $q->with(['subject','semester','enrollments','professors'])
            ->get();
    }
}
