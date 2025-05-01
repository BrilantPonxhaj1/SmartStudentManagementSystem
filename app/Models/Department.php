<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'university_id',
        'name',
        'code',
        'description',
        'head_id',
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function head()
    {
        return $this->belongsTo(ProfessorProfile::class, 'head_id');
    }

    /**
     * All professors in this department.
     */
    public function professors()
    {
        return $this->hasMany(ProfessorProfile::class);
    }

    /**
     * All students in this department.
     */
    public function students()
    {
        return $this->hasMany(StudentProfile::class);
    }

    /**
     * Subjects (courses) offered by this department.
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
