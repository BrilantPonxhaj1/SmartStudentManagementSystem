<?php

namespace App\Repositories;

use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentRepository extends BaseRepository
{
    public function __construct(StudentProfile $student)
    {
        parent::__construct($student);
    }

    // maybe we will use it later
    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Fetch all students, with their user & profile already loaded.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->model
            ->newQuery()
            ->with(['user', 'university', 'department'])
            ->get($columns);
    }

    public function create(array $data): StudentProfile
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'type' => 'student',
            'university_id' => $data['university_id'],
            'department_id' => $data['department_id'],
        ]);

        $profile = $this->model->create([
            'user_id' => $user->id,
            'student_number' => $data['student_number'],
            'program' => $data['program'],
            'year_of_study' => $data['year_of_study'],
            'university_id' => $data['university_id'],
            'department_id' => $data['department_id'],
            'enrollment_year' => $data['enrollment_year'],
        ]);

        return $profile;
    }                                                                                // :contentReference[oaicite:0]{index=0}
}
