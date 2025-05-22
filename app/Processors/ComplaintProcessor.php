<?php

namespace App\Processors;

use App\Processors\BaseProcessor;
use App\Repositories\ComplaintRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ComplaintProcessor extends BaseProcessor
{
    public function __construct(ComplaintRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function allComplaints(): Collection
    {
        return $this->repo->allComplaints();
    }

    public function getAllByUserId(int $userId): Collection
    {
        return $this->repo->getAllByUserId($userId);
    }

    public function getAllExceptClosed(): Collection
    {
        return $this->repo->getAllExceptClosed();
    }

    public function get(int $id): ?Model
    {
        return $this->repo->find($id);
    }

    public function create(array $data): Model
    {
        return $this->db->transaction(fn() => $this->repo->create($data));
    }

    public function update(int $id, array $data): Model
    {
        return $this->db->transaction(fn() => $this->repo->update($id, $data));
    }

}
