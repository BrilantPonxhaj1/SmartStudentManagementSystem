<?php

namespace App\Processors;

use App\Repositories\ExamRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ExamProcessor extends BaseProcessor
{
    public function __construct(ExamRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function allForProfessor(int $professorId): Collection
    {
        return $this->repo->allExamsForProfessor($professorId);
    }

    public function get(int $id): ?Model
    {
        return $this->repo->find($id);
    }

    public function getOwnedByProfessor(int $examId, int $professorId): ?Model
    {
        return $this->repo->findOwnedByProfessor($examId, $professorId);
    }

    public function create(array $data): Model
    {
        return $this->db->transaction(fn() => $this->repo->create($data));
    }

    public function updateExam(int $id, array $data, int $professorId): Model
    {
        $exam = $this->repo->findOwnedByProfessor($id, $professorId);

        if (!$exam) {
            throw new ModelNotFoundException("Exam not found or unauthorized.");
        }

        if ($exam->date <= now()->toDateString()) {
            throw new \Exception("Cannot update an exam that has already occurred.");
        }
        //allowed to be edited 'title', 'exam_type', 'duration', 'max_score', 'weight', 'description'

        return $this->db->transaction(fn() => $this->repo->update($id, $data));
    }

    public function deleteExam(int $id, int $professorId): void
    {
        $exam = $this->repo->findOwnedByProfessor($id, $professorId);

        if (!$exam) {
            throw new ModelNotFoundException("Exam not found or unauthorized.");
        }

        if ($exam->date <= now()->toDateString()) {
            throw new \Exception("Cannot delete an exam that has already occurred.");
        }

        $this->db->transaction(fn() => $this->repo->delete($id));
    }
}
