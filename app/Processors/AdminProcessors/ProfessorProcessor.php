<?php

namespace App\Processors\AdminProcessors;


use App\Models\Professor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\User;
use Throwable;

class ProfessorProcessor
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
                'type'       => $data['type'] ?? 'teacher',
            ]);
            Professor::query()->create([
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
        return Professor::with('user')
            ->get()
            ->map(function (Professor $prof) {
                return [
                    'id' => $prof->user_id,
                    'first_name' => $prof->user->first_name,
                    'last_name' => $prof->user->last_name,
                    'email' => $prof->user->email,
                    'phone' => $prof->phone,
                    'role' => $prof->role,
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
           $prof = Professor::with('user')->findOrFail($id);
           $prof->delete();
           $prof->user->delete();
        });
    }

    /**
     *
     * Update an existing professor (both user and professor rows).
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
            $prof = Professor::with('user')->findOrFail($id);

            // Updatei te tabela user
            $user = $prof->user;
            $user->first_name = $data['first_name'];
            $user->last_name = $data['last_name'];
            $user->email = $data['email'];

            if(!empty($data['password'])) {
                $user->password = bcrypt($data['password']);
            }
            $user->save();

            // Updatei te tabela professors
            $prof->role = $data['role'];
            $prof->phone = $data['phone'];
            $prof->save();

            return $user;
        },5); // tenton 5 here nqs ka deadlock te transaksioni.
    }

}
