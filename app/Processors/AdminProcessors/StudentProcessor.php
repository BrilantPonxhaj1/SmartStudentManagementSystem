<?php
namespace App\Processors\AdminProcessors;

use App\Processors\BaseProcessor;
use App\Repositories\StudentRepository;
use Illuminate\Database\DatabaseManager;
use Throwable;

class StudentProcessor extends BaseProcessor
{
    public function __construct(StudentRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db); // $repo is-a BaseRepository, so this is valid
    }

    // List all students
    public function list()
    {
        return $this->repo->all();
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

    // Example: Get students by status
    public function getByStatus(string $status)
    {
        return $this->repo->getByStatus($status);
    }
}
