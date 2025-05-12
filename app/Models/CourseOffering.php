<?php

namespace App\Models;

use App\Models\Scopes\TenantScope;
use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseOffering extends Model
{
    use HasFactory,TenantScoped;

    /**
     * The table associated with the model.
     */
    protected $table = 'course_offerings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'university_id',
        'department_id',
        'subject_id',
        'professor_profile_id',
        'semester_id',
        'section',
        'schedule',
        'capacity',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'capacity' => 'integer',
    ];

    /**
     * The university that this offering belongs to.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * The department that offers this course.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * The subject being taught in this offering.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    /**
     * The professor teaching this offering.
     */
    public function professorProfile()
    {
        return $this->belongsTo(Professor::class);
    }

    /**
     * The semester in which this offering takes place.
     */
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * The enrollments for this offering.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * The attendance records for this offering.
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * The exams scheduled for this offering.
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * The assignments given in this offering.
     */
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function professors()
    {
        return $this->belongsToMany(
            Professor::class,
            'teaching_assignments',          // pivot table
            'course_offering_id',            // this model’s FK
            'professor_profile_id'           // related model’s FK
        )
            ->withPivot(['role','hours_per_week','office_hours'])
            ->withTimestamps();
    }
}
