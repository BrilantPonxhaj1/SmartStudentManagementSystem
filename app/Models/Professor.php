<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    /**
     * Mass-assignable attributes.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'user_id',
        'university_id',
        'department_id',
        'employee_number',
        'specialization',
        'academic_role',
    ];

    /**
     * The user account for this professor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The university this professor belongs to.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * The department this professor belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Direct class offerings (sections) taught by this professor.
     */
    public function courseOfferings()
    {
        return $this->hasMany(CourseOffering::class);
    }

    /**
     * Subjects this professor can teach (via course_offerings pivot).
     */
    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'course_offerings',
            'professor_profile_id',
            'subject_id'
        )->withTimestamps();
    }

    /**
     * Exams the professor creates/conducts.
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Appointments scheduled with students or staff.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Complaints filed by or about this professor.
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'user_id');
    }

    /**
     * (Optional) Literature resources contributed by this professor.
     */
    public function literature()
    {
        return $this->hasMany(Literature::class);
    }
}
