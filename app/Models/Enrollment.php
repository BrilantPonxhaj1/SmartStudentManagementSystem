<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    protected $table = 'enrollments';
    protected $fillable = [
        'university_id',
        'department_id',
        'student_profile_id',
        'course_offering_id',
        'enrolled_at',
        'status',
        'final_grade',
    ];
    /** Enrollment belongs to a university */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /** Enrollment belongs to a department */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** Enrollment belongs to a student profile */
    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(StudentProfile::class);
    }

    /** Enrollment belongs to a course offering */
    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }
}
