<?php

namespace App\Processors\AdminProcessors;

use App\Repositories\SubjectRepository;
use Illuminate\Database\DatabaseManager;
use App\Processors\BaseProcessor;
use Illuminate\Database\Eloquent\Model;

class SubjectProcessor extends BaseProcessor
{
    public function __construct(SubjectRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function allSubjects() : \Illuminate\Support\Collection
    {
        return $this->repo->allSubjects();
    }

    public function get(int $id) : ?Model
    {
        return $this->repo->find($id);
    }

    public function create(array $data) : Model
    {
        return $this->db->transaction(function () use ($data) {
            return $this->repo->create($data);
        });
    }
    /**
     * @throws \Throwable
     */
    public function update(int $id, array $data) : Model
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
