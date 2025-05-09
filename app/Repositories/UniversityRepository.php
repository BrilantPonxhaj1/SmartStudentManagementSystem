<?php

namespace App\Repositories;

use App\Models\University;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class UniversityRepository extends BaseRepository
{
    public function __construct(University $model)
    {
        parent::__construct($model);
    }


    public function listForSelect(): Collection
    {
        return $this->model
            ->with(['departments', 'subjects'])
            ->get();
    }


    /**
     * Create a new university
     *
     * @param array $data
     * @return University
     */
    public function create(array $data): University
    {
        return $this->model->create([
            'name'        => $data['name'],
            'code'    => $data['code'],
            'address' => $data['address']
        ]);
    }

    /**
     * Find a university by ID with related data
     *
     * @param int $id
     * @return University|null
     */
    public function find(int $id): ?Model
    {
        return $this->model->with(['departments', 'subjects'])->find($id);
    }

    /**
     * Update a university
     *
     * @param int $id
     * @param array $data
     * @return University
     */
    public function update(int $id, array $data): University
    {
        $university = $this->model->findOrFail($id);

        $university->update([
            'id'        => $id,
            'name'        => $data['name'],
            'code'    => $data['code'],
            'address' => $data['address']
        ]);

        return $university;
    }

    /**
     * Delete a university
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $university = $this->find($id);
        if ($university) {
            $university->delete();
        }
    }
}
