<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $table = 'attendance';

    protected $fillable = [
        'university_id',
        'department_id',
        'student_profile_id',
        'course_offering_id',
        'date',
        'status',
        'remarks',
    ];


    /** Attendance belongs to a university */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /** Attendance belongs to a department */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /** Attendance belongs to a student profile */
    public function studentProfile(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /** Attendance belongs to a course offering */
    public function courseOffering(): BelongsTo
    {
        return $this->belongsTo(CourseOffering::class);
    }

}
