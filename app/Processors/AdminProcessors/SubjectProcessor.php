<?php

namespace App\Processors\AdminProcessors;

use App\Repositories\SubjectRepository;
use Illuminate\Database\DatabaseManager;
use App\Processors\BaseProcessor;

class SubjectProcessor extends BaseProcessor
{
    public function __construct(SubjectRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function listWithRelations()
    {
        return $this->repo->allWithRelations();
    }

    public function get(int $id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        return $this->db->transaction(function () use ($data) {
            return $this->repo->create($data);
        });
    }
    /**
     * @throws \Throwable
     */
    public function update(int $id, array $data)
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
