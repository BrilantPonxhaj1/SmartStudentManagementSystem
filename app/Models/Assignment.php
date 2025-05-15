<?php

namespace App\Models;

use App\Enums\AssignmentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $table = 'assignments';

    protected $fillable = [
        'university_id',
        'department_id',
        'course_offering_id',
        'title',
        'description',
        'due_date',
        'max_score',
        'assignment_type',
    ];
    protected $casts =[
        'due_date' => 'datetime',
        'assignment_type' => AssignmentType::class,
    ];

    // Relationships
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class);
    }
}
