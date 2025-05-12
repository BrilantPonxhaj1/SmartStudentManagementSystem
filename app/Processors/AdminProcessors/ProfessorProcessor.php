<?php

namespace App\Processors\AdminProcessors;


use App\Models\Professor;
use App\Processors\BaseProcessor;
use App\Repositories\ProfessorRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\User;
use Throwable;
use Illuminate\Support\Facades\Auth;


class ProfessorProcessor extends BaseProcessor
{
    public function __construct(ProfessorRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }
    /**
     * List all professors with their user profiles.
     *
     * @return Collection
     */
    public function listWithUser()
    {
        return $this->repo->allWithUser();
    }

    // Get a single student by ID
    public function get(int $id)
    {
        return $this->repo->find($id);
    }

    // Create a new student (with transaction)
    public function create(array $data)
    {
        return $this->db->transaction(function () use ($data) {
            return $this->repo->create($data);
        });
    }

    // Update a student (with transaction)

    /**
     * @throws Throwable
     */
    public function update(int $id, array $data)
    {
        return $this->db->transaction(function () use ($id, $data) {
            return $this->repo->update($id, $data);
        });
    }

    // Delete a student (with transaction)

    /**
     * @throws Throwable
     */
    public function delete(int $id): void
    {
        $this->db->transaction(function () use ($id) {
            $this->repo->delete($id);
        });
    }

    /**
     * Return all professors belonging to $departmentId.
     */
    public function professorsByDepartment(int $departmentId): Collection
    {
        return $this->repo->getByDepartment($departmentId);
    }
}
