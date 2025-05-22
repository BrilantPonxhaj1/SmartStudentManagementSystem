<?php

namespace App\Repositories;

use App\Models\Professor;
use App\Models\Student;
use App\Models\User;
use App\Repositories\Traits\Cacheable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class StudentRepository extends BaseRepository
{
    use Cacheable;
    public function __construct(Student $student)
    {
        parent::__construct($student);
    }

    /**
     * Fetch all students, with their user & profile already loaded.
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->rememberTagged('students', function() use ($columns) {
            return $this->model
                ->newQuery()
                ->with(['user', 'university', 'department'])
                ->get($columns);
        });
    }


    public function create(array $data): Student
    {
        $this->flushTagged('students');

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'type' => 'student',
        ]);

        return $this->model->create([
            'user_id' => $user->id,
            'student_number' => $data['student_number'],
            'program' => $data['program'],
            'year_of_study' => $data['year_of_study'],
            'university_id' => $data['university_id'],
            'department_id' => $data['department_id'],
            'enrollment_year' => $data['enrollment_year'],
        ]);
    }
    public function delete(int $id): void {
        $this->flushTagged('students');

        $profile = $this->find($id);
        $user = $profile->user;
        parent::delete($id);

        if ($user) {
            $user->delete();
        }
    }

    public function update(int $id, array $data): Student
    {
        $this->flushTagged('students');

        return DB::transaction(function() use ($id, $data) {
            $profile = $this->model->with('user')->find($id);

            // 2) Update user fields
            $user = $profile->user;
            $user->first_name    = $data['first_name'];
            $user->last_name     = $data['last_name'];
            $user->email         = $data['email'];
            // only change password if present
            if (!empty($data['password'])) {
                $user->password = bcrypt($data['password']);
            }
            $user->save();

            // 3) Update profile fields
            $profile->student_number  = $data['student_number'];
            $profile->program         = $data['program'];
            $profile->year_of_study   = $data['year_of_study'];
            $profile->enrollment_year = $data['enrollment_year'];
            // if you have status:
            if (isset($data['status'])) {
                $profile->status = $data['status'];
            }
            // tenant keys, if editable
            $profile->university_id   = $data['university_id'];
            $profile->department_id   = $data['department_id'];

            $profile->save();

            // 4) Return the fresh model
            return $profile;
        });
    }
}
