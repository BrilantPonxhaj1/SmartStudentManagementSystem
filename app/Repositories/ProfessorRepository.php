<?php

namespace App\Repositories;

use App\Models\Professor;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class ProfessorRepository extends BaseRepository
{
    public function __construct(Professor $professor)
    {
        parent::__construct($professor);
    }

    /*
     * Create a new professor profile
     *
     * @param array $data
     * @return Professor
     */

    public function create(array $data): Professor {
        $nextEmployeeNumber = (string) ($this->model->max('employee_number') + 1);
        //user table part
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'type'       => 'teacher',
        ]);


        //professor table part
        $profile = $this->model->create([
            'user_id'         => $user->id,
            'university_id'   => $data['university_id'],
            'department_id'   => $data['department_id'],
            'employee_number' => $nextEmployeeNumber,
            'specialization'  => $data['specialization'],
            'academic_role'   => $data['academic_role'],
        ]);

        return $profile;
    }


    /**
     * Retrieve all professors along with their respective data in users table
     *
     * @return \Illuminate\Support\Collection
     *
     */
    public function allWithUser(): \Illuminate\Support\Collection
    {
        return $this->model
            ->with('user')
            ->get();
    }

    /**
     * Retrieve a professor by ID along with their respective data in users table
     *
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->model
            ->with('user')
            ->find($id);
    }

    /**
     * Delete a professor profile AND its associated user.
     *
     * @param  int  $id
     * The primary key of the professor_profile to delete.
     */
    public function delete(int $id): void {
        $profile = $this->find($id);
        $user = $profile->user;
        parent::delete($id);

        if ($user) {
            $user->delete();
        }
    }

    /**
     *
     *
     */
    public function update(int $id, array $data): Professor
    {
        $profile = $this->model->with('user')->find($id);

        // user part
        $user = $profile->user;
        $user->first_name = $data['first_name'];
        $user->last_name = $data['last_name'];
        $user->email = $data['email'];
        if (!empty($data['password'])) {
            $user->password = bcrypt($data['password']);
        }
        $user->save();

        // professor part
        $profile->university_id = $data['university_id'];
        $profile->department_id = $data['department_id'];
        $profile->specialization = $data['specialization'];
        $profile->academic_role = $data['academic_role'];
        $profile->save();

        return $profile;

    }

    /**
     * Fetch all professors (with user) for a given department.
     */
    public function getByDepartment(int $departmentId): Collection
    {
        return $this->model
            ->with('user')
            ->where('department_id', $departmentId)
            ->get();
    }
    /**
     * Fetch professor (from users->professors).
     */
    public function getProfessorWithUser(int $userId): ?Professor
    {
        return $this->model
            ->with('user')
            ->where('user_id', $userId)
            ->first();
    }
}
