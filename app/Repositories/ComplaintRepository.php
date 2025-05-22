<?php

namespace App\Repositories;

use App\Models\Complaint;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ComplaintRepository extends BaseRepository
{
    public function __construct(Complaint $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): Complaint
    {
        return $this->model->create($data);
    }

    public function allComplaints(): Collection
    {
        return $this->model
            ->with(['university', 'department', 'user', 'resolver'])
            ->get();
    }

    public function getAllByUserId(int $userId): Collection
    {
        return $this->model
            ->with(['university', 'department'])
            ->where('user_id', $userId)
            ->get();
    }

    public function getAllExceptClosed(): Collection
    {
        return $this->model
            ->with(['university', 'department', 'user', 'resolver'])
            ->where('status', '!=', 'closed')
            ->get();
    }
    public function find(int $id): ?Model
    {
        return $this->model
            ->with(['university', 'department', 'user', 'resolver'])
            ->find($id);
    }
    public function update(int $id, array $data): Complaint
    {
        $complaint = $this->model->findOrFail($id);

        if (isset($data['status']) && $data['status'] === 'closed') {
            $user = auth()->user();
            if ($user && $user->getType() === 'superadmin') {
                $data['resolved_by'] = $user->id;
                $data['resolved_at'] = now();
            }
        }

        $complaint->update($data);
        return $complaint;
    }

}
