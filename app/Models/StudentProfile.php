<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'user_id',
        'university_id',
        'department_id',
        'student_number',
        'program',
        'year_of_study',
        'enrollment_year',
    ];

    /**
     * The user account for this student.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The university this student belongs to.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * The department (major) this student is enrolled in.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Course registration forms submitted by this student.
     */
    public function courseForms()
    {
        return $this->hasMany(CourseForm::class);
    }

    /**
     * Actual enrollments (subjects/classes) for this student.
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Grade records (exams, assignments, final grades).
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Attendance records across all classes.
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Complaints filed by this student.
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Scholarship entries for this student.
     */
    public function scholarships()
    {
        return $this->hasMany(Scholarship::class);
    }

    /**
     * Transcript records for this student.
     */
    public function transcripts()
    {
        return $this->hasMany(Transcript::class);
    }

    /**
     * Appointments scheduled with professors.
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
