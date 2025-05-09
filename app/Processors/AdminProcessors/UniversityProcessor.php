<?php

namespace App\Processors\AdminProcessors;

use App\Models\University;
use App\Processors\BaseProcessor;
use App\Repositories\UniversityRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UniversityProcessor extends BaseProcessor
{
    public function __construct(UniversityRepository $repo, DatabaseManager $db) {
        parent::__construct($repo, $db);
    }

    /**
     * Return name+id for all universities
     */
    public function listForSelect()
    {
        return $this->repo->listForSelect();
    }

    public function get(int $id): ?Model
    {
        return $this->repo->find($id);
    }

    public function create(array $data): University
    {
        return $this->db->transaction(function () use ($data) {
            return $this->repo->create($data);
        });
    }

    /**
     * @throws \Throwable
     */
    public function update(int $id, array $data): University
    {
        return $this->db->transaction(function () use ($id, $data) {
            return $this->repo->update($id, $data);
        });
    }

    /**
     * @throws \Throwable
     */
    public function delete(int $id): void
    {
        $this->db->transaction(function () use ($id) {
            $this->repo->delete($id);
        });
    }
}
