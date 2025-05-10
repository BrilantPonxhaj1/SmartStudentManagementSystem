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

//    public function create(array $data): CourseOffering
//    {
//        return $this->model->create($data);
//    }
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

     /**
     * Check if a section is already used for this subject+semester.
     */
    public function sectionExists(array $data, ?int $excludeId = null): bool
    {
        $q = $this->model->newQuery()
            ->where('subject_id', $data['subject_id'])
            ->where('semester_id', $data['semester_id'])
            ->where('section', $data['section']);

        if ($excludeId) {
            $q->where('id', '!=', $excludeId);
        }

        return $q->exists();
    }



    /**
     * Check if the professor already has an offering with the same schedule in this semester.
     */
    public function professorHasScheduleConflict(array $data, ?int $excludeId = null): bool
    {
        $q = $this->model->newQuery()
            ->where('professor_profile_id', $data['professor_profile_id'])
            ->where('semester_id', $data['semester_id'])
            ->where('schedule', $data['schedule']);

        if ($excludeId) {
            $q->where('id', '!=', $excludeId);
        }

        return $q->exists();
    }
    public function enrolledCount(int $offeringId): int {
        return $this->model
            ->newQuery()
            ->where('id', $offeringId)
            ->withCount('enrollments')
            ->first()
            ->enrollments_count;
    }
}
