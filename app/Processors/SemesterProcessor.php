<?php

namespace App\Processors;

use App\Repositories\SemesterRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;
use Throwable;
use DomainException;

class SemesterProcessor extends BaseProcessor
{
    public function __construct(SemesterRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    /**
     * Create a new semester.
     *
     * @param array $data
     * @throws Throwable
     */
    public function create(array $data)
    {
        // --> 1
        if ($this->repo->checkSemesterDateOverlapping($data)) {
            throw new DomainException('Semester dates overlap with existing semesters.');
        }

        //--> 2
        return $this->db->transaction(fn() => $this->repo->create($data));
    }

    /**
     * Update a semester by ID.
     *
     * @param array $data
     * @param int $id
     * @throws Throwable
     */
    public function update(int $id, array $data)
    {
        if ($this->repo->checkOverlapOnUpdate($id, $data)) {
            throw ValidationException::withMessages([
                'start_date' => 'The given dates overlap an existing semester.'
            ]);
        }

        return $this->db->transaction(fn() => $this->repo->update($id, $data));
    }

    /**
     * Delete a semester by ID.
     *
     * @param int $id
     * @return mixed
     */
    public function delete(int $id): void
    {
        $this->db->transaction(fn() => $this->repo->delete($id));

    }


}
