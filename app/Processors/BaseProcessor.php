<?php
namespace App\Processors;

use App\Repositories\BaseRepository;
use Illuminate\Database\DatabaseManager;
use Throwable;

abstract class BaseProcessor
{
    protected BaseRepository $repo;
    protected DatabaseManager $db;

    public function __construct(BaseRepository $repo, DatabaseManager $db)
    {
        $this->repo = $repo;
        $this->db   = $db;
    }

    // List all entities
    public function list()
    {
        return $this->repo->all();
    }

    // Get a single entity by ID
    public function get(int $id)
    {
        return $this->repo->find($id);
    }

    // Create a new entity

    /**
     * @throws Throwable
     */
    public function create(array $data)
    {
        return $this->db->transaction(function () use ($data) {
            return $this->repo->create($data);
        });
    }

    // Update an entity
    public function update(int $id, array $data)
    {
        return $this->db->transaction(function () use ($id, $data) {
            return $this->repo->update($id, $data);
        });
    }

    // Delete an entity
    public function delete(int $id)
    {
        return $this->db->transaction(function () use ($id) {
            return $this->repo->delete($id);
        });
    }
}
