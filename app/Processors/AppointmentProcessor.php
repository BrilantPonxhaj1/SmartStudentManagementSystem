<?php

namespace App\Processors;

use App\Repositories\AppointmentRepository;
use Illuminate\Database\DatabaseManager;
use App\Processors\BaseProcessor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class AppointmentProcessor extends BaseProcessor
{
    public function __construct(AppointmentRepository $repo, DatabaseManager $db)
    {
        parent::__construct($repo, $db);
    }

    public function appointmentsByProfessor(int $professorId): \Illuminate\Support\Collection
    {
        return $this->repo->getAppointmentsByProfessor($professorId);
    }

    public function getStudentAppointments(int $studentId): \Illuminate\Support\Collection
    {
        return $this->repo->getStudentAppointments($studentId);
    }

    public function get(int $id): ?Model
    {
        return $this->repo->find($id);
    }

    public function create(array $data): Model
    {
        return $this->db->transaction(function () use ($data) {
            return $this->repo->create($data);
        });
    }

    public function update(int $id, array $data): Model
    {
        return $this->db->transaction(function () use ($id, $data) {
            return $this->repo->update($id, $data);
        });
    }
    public function deleteForStudent(int $id): void
    {
        $this->db->transaction(function () use ($id) {
           $this->repo->deleteForStudent($id);
        });
    }
    public function delete(int $id): void
    {
        $this->db->transaction(function () use ($id) {
            $this->repo->delete($id);
        });
    }
}
