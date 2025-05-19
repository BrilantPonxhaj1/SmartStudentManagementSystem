<?php

namespace App\Repositories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ExamRepository extends BaseRepository
{
    public function __construct(Exam $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): Exam
    {
        return $this->model->create($data);
    }

    public function allExamsForProfessor(int $professorId): Collection
    {
        return $this->model
            ->whereHas('courseOffering', fn($q) =>
            $q->where('professor_profile_id', $professorId)
            )
            ->with(['university', 'department', 'courseOffering'])
            ->get();
    }

    public function find(int $id): ?Model
    {
        return $this->model
            ->with(['university', 'department', 'courseOffering'])
            ->find($id);
    }

    public function findOwnedByProfessor(int $id, int $professorId): ?Model
    {
        return $this->model
            ->where('id', $id)
            ->whereHas('courseOffering', fn($q) =>
            $q->where('professor_profile_id', $professorId)
            )
            ->with(['university', 'department', 'courseOffering'])
            ->first();
    }

    public function update(int $id, array $data): Exam
    {
        $exam = $this->model->findOrFail($id);
        $exam->update($data);
        return $exam;
    }

    public function delete(int $id): void
    {
        $exam = $this->find($id);
        if ($exam) {
            $exam->delete();
        }
    }
}
