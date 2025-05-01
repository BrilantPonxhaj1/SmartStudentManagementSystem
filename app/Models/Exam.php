<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    protected $table = 'exams';
    protected $fillable = [
        'university_id',
        'department_id',
        'course_offering_id',
        'title',
        'exam_type',
        'date',
        'duration',
        'max_score',
        'weight',
        'description',
    ];

    /** Exam belongs to a university */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /** Exam belongs to a department */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** Exam belongs to a course offering */
    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }
}
