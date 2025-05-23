<?php

namespace App\Repositories;

use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class AppointmentRepository extends BaseRepository
{
    public function __construct(Appointment $model)
    {
        parent::__construct($model);
    }

    public function create(array $data): Appointment
    {
        return $this->model->create($data);
    }

    public function getAppointmentsByProfessor(int $professorId): \Illuminate\Support\Collection
    {
        return $this->model
            ->with(['university', 'department', 'studentProfile', 'professorProfile', 'requestedBy'])
            ->where('professor_profile_id', $professorId)
            ->get();
    }

    public function getStudentAppointments(int $studentId): \Illuminate\Support\Collection
    {
        return $this->model
            ->with(['university', 'department', 'studentProfile', 'professorProfile.user', 'requestedBy'])
            ->where('student_profile_id', $studentId)
            ->get();
    }

    public function find(int $id): ?Model
    {
        return $this->model
            ->with(['university', 'department', 'studentProfile', 'professorProfile', 'requestedBy'])
            ->find($id);
    }

    public function update(int $id, array $data): Appointment
    {
        $appointment = $this->model->findOrFail($id);
        $appointment->update($data);
        return $appointment;
    }

    public function deleteForStudent(int $id): void
    {
        $appointment = $this->model->findOrFail($id);

        $minutesUntil = Carbon::now()->diffInMinutes(Carbon::parse($appointment->appointment_time), false);

        if ($minutesUntil >= 60) {
            $appointment->delete();
        } else {
            throw new \Exception('Appointments can only be cancelled at least 1 hour in advance.');
        }
    }
    public function delete(int $id): void
    {
        $appointment = $this->find($id);
        if ($appointment) {
            $appointment->delete();
        }
    }
}
