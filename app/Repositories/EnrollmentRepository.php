<?php

namespace App\Repositories;

use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Validation\ValidationException;

class EnrollmentRepository extends BaseRepository
{
    public function __construct(Enrollment $model)
    {
        parent::__construct($model);
    }

    /**
     * Override BaseRepository::create so it’s typed as Enrollment.
     *
     * @param  array<string,mixed>  $attributes
     * @return Enrollment
     */
    public function create(array $attributes): Enrollment
    {
        /** @var Enrollment $model */
        $model = parent::create($attributes);
        return $model;
    }

    /**
     * Register a student for a course offering.
     *
     * @throws ValidationException
     */
    public function register(Student $student, CourseOffering $offering): Enrollment
    {
        $semester = $offering->semester;
        $studentId = $student->id;
        $offeringId = $offering->id;

        // 1) capacity & course‐count checks (unchanged) …
        $count = Enrollment::where('student_profile_id', $studentId)
            ->whereHas('courseOffering', fn($q) => $q->where('semester_id', $semester->id))
            ->where('status', 'active')
            ->count();

        if ($offering->enrollments()->where('status','active')->count() >= $offering->capacity) {
            throw ValidationException::withMessages(['message'=>'This course offering is full.']);
        }
        if ($count >= $semester->max_courses) {
            throw ValidationException::withMessages([
                'message'=>"You may enroll in at most {$semester->max_courses} courses this semester."
            ]);
        }

        // 2) look for any existing enrollment (regardless of status)
        $existing = $this->model
            ->where('student_profile_id', $studentId)
            ->where('course_offering_id', $offeringId)
            ->first();

        if ($existing) {
            if ($existing->status === 'active') {
                // true duplicate
                throw ValidationException::withMessages([
                    'message' => 'Already enrolled in this course offering.'
                ]);
            }

            // was dropped before → reactivate
            $existing->update([
                'status'      => 'active',
                'enrolled_at' => now(),
            ]);

            return $existing;
        }

        // 3) no prior enrollment at all → create new
        return $this->create([
            'university_id'      => $student->university_id,
            'department_id'      => $student->department_id,
            'student_profile_id' => $studentId,
            'course_offering_id' => $offeringId,
            'enrolled_at'        => now(),
            'status'             => 'active',
        ]);
    }
    public function cancelEnrollment(Enrollment $enrollment): void
    {
        $enrollment->update(['status' => 'dropped']);
    }
}
