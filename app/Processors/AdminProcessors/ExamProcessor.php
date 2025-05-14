<?php

namespace App\Processors\AdminProcessors;

use App\Processors\BaseProcessor;
use App\Repositories\ExamRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ExamProcessor extends BaseProcessor
{
    public function __construct(ExamRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function allExams(): Collection
    {
        return $this->repo->allExams();
    }

    public function get(int $id): ?Model
    {
        return $this->repo->find($id);
    }

    public function create(array $data): Model
    {
        return $this->db->transaction(fn() => $this->repo->create($data));
    }

    /**
     * @throws \Throwable
     */
    public function update(int $id, array $data): Model
    {
        return $this->db->transaction(fn() => $this->repo->update($id, $data));
    }

    /**
     * @throws \Throwable
     */
    public function delete(int $id): void
    {
        $this->db->transaction(fn() => $this->repo->delete($id));
    }
}
