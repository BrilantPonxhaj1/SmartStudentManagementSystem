<?php

namespace App\Processors\AdminProcessors;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Student;
use App\Models\User;
use Throwable;

class StudentProcessor
{
    /**
     *
     * @param array $data Validated input data
     * @return User
     * @throws Throwable
     */
    public function create(array $data): User {
        return DB::transaction(function () use  ($data) {
            $user =  User::query()->create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => bcrypt($data['password']),
                'type'       => $data['type'] ?? 'student',
            ]);
            Student::query()->create([
                'user_id' => $user->id,
                'role'    => $data['role'],
                'phone'   => $data['phone'],
            ]);
            return $user;
        });
    }

    /**
     *
     * @return Collection
     */
    public function list(): Collection
    {
        return Student::with('user')
            ->get()
            ->map(function (Student $student) {
                return [
                    'id' => $student->user_id,
                    'first_name' => $student->user->first_name,
                    'last_name' => $student->user->last_name,
                    'email' => $student->user->email,
                    'phone' => $student->phone,
                    'role' => $student->role,
                ];
            });
    }

    /**
     * @param int $id
     * @return void
     * @throws Throwable
     *
     */

    public function delete(int $id): void
    {
        DB::transaction(function () use ($id) {
            $student = Student::with('user')->findOrFail($id);
            $student->delete();
            $student->user->delete();
        });
    }
    /**
     *
     * Update an existing student (both user and students rows).
     *
     * @param int $id
     * @param array $data
     * @return User
     * @throws Throwable
     *
     */

    public function update(int $id, array $data): User {
        return DB::transaction(function () use ($id, $data) {
            // Fetch
            $student = Student::with('user')->findOrFail($id);

            // Updatei te tabela user
            $user = $student->user;
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];

            if(!empty($data['password'])) {
                $user->password = bcrypt($data['password']);
            }
            $user->save();

            // Updatei te tabela studens
            $student->role = $data['role'] ?? 'student';
            $student->phone = $data['phone'];
            $student->save();

            return $user;
        },5); // tenton 5 here nqs ka deadlock te transaksioni.
    }
}
