<?php

namespace App\Processors\AdminProcessors;


use App\Processors\BaseProcessor;
use App\Repositories\CourseOfferingRepository;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Collection;
class CourseOfferingProcessor extends BaseProcessor
{
    public function __construct(CourseOfferingRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function listBySemester(int $semesterId)
    {
        return $this->repo->findBySemester($semesterId);
    }

    /**
     * Create a new course offering. Overriding the base method to enforce business logic to check if that section already exists.
     * Also checks if the professor has schedule conflicts.
     *
     * @param array $data
     * @throws Exception
     */
    public function create(array $data)
    {
        if (!empty($data['section']) && $this->repo->sectionExists($data)) {
            throw new Exception("Section already exists");
        }

        if (!empty($data['schedule']) && $this->repo->professorHasScheduleConflict($data)) {
            throw new Exception('Schedule conflict: Professor is already assigned another offering at that time.');
        }

        return $this->db->transaction(fn() => $this->repo->create($data));
    }

    /**
     * Updates a course offering. Overriding the base method to enforce business logic to check if that section already exists.
     * And also check if the new capacity is greater than the current enrolled count.
     *
     * @param int $offeringId
     * @param array $data
     * @return mixed
     * @throws \Throwable
     * @throws Exception
     */

    public function update(int $id, array $data)
    {
        if (!empty($data['section']) && $this->repo->sectionExists($data, $id)) {
            throw new Exception("Section already exists");
        }

        if (!empty($data['schedule']) && $this->repo->professorHasScheduleConflict($data, $id)) {
            throw new Exception('Schedule conflict: Professor is already assigned another offering at that time.');
        }

        if (isset($data['capacity'])) {
            $current = $this->repo->enrolledCount($id);
            if ($data['capacity'] < $current) {
                throw new Exception("Capacity must be greater than or equal to $current");
            }
        }

        return $this->db->transaction(fn() => $this->repo->update($id, $data)
        );
    }


    /**
     * Fetches course-offerings of that perticular professor
     * @param int $professorId
     * @return Collection
     */
    public function getCoursesOfProfessor(int $professorId): Collection
    {
        return $this->repo->getCoursesOfProfessor($professorId);
    }

}
