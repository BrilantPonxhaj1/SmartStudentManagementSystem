<?php
namespace App\Repositories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;

class StudentRepository extends BaseRepository
{
    public function __construct(Student $student)
    {
        parent::__construct($student);
    }
    // maybe we will use it later
    public function getByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }
}
