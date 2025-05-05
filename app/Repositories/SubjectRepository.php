<?php

namespace App\Repositories;

use App\Models\Scopes\TenantScope;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Model;

class SubjectRepository extends BaseRepository
{
    public function __construct(Subject $model)
    {
        parent::__construct($model);
    }

    /**
     * Create a new subject
     *
     * @param array $data
     * @return Subject
     */
    public function create(array $data): Subject
    {
        $subject =  $this->model->create([
            'university_id' => $data['university_id'],
            'department_id' => $data['department_id'],
            'code'          => $data['code'],
            'name'          => $data['name'],
            'description'   => $data['description'],
            'credits'       => $data['credits'],
            'type'          => $data['type'],
        ]);
        return $subject;
    }

    /**
     * Get all subjects with related data
     *
     * @return \Illuminate\Support\Collection
     */
    public function allWithRelations(): \Illuminate\Support\Collection
    {
        return $this->model
            ->with(['university', 'department', 'courseOfferings', 'literature', 'enrollments'])
            ->get();
    }

    /**
     * Find a subject by ID with related data
     *
     * @param int $id
     * @return Subject|null
     */
    public function find(int $id): ?Model
    {
        return $this->model
            ->with(['university', 'department', 'courseOfferings', 'literature', 'enrollments'])
            ->find($id);
    }

    /**
     * Update a subject
     *
     * @param int $id
     * @param array $data
     * @return Subject
     */
    public function update(int $id, array $data): Subject
    {
        $subject = $this->model->findOrFail($id);

        $subject->update([
            'university_id' => $data['university_id'],
            'department_id' => $data['department_id'],
            'code'          => $data['code'],
            'name'          => $data['name'],
            'description'   => $data['description'],
            'credits'       => $data['credits'],
            'type'          => $data['type'],
        ]);

        return $subject;
    }

    /**
     * Delete a subject
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $subject = $this->find($id);
        if ($subject) {
            $subject->delete();
        }
    }
}
