<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseForm extends Model
{
    protected $table = 'course_forms';

    protected $fillable = [
        'university_id',
        'department_id',
        'student_profile_id',
        'semester_id',
        'submission_date',
        'status',
        'approved_by',
        'approved_at',
        'remarks',
    ];

    /*
        * The university that the course form belongs to.
    */
    public function university(): BelongsTo {
        return $this->belongsTo(University::class);
    }
    /*
        * The department that the course form belongs to.
     */
    public function department(): BelongsTo {
        return $this->belongsTo(Department::class);
    }
    /*
        * The student that submitted the course form.
     */
    public function studentProfile(): BelongsTo {
        return $this->belongsTo(StudentProfile::class);
    }

    /**
        * The semester that the course belongs to.
     */
    public function semester(): BelongsTo {
        return $this->belongsTo(Semester::class);
    }
    /**
        * The user that approved the course form.
     */
    public function approver(): BelongsTo{
        return $this->belongsTo(User::class, 'approved_by');
    }
}
