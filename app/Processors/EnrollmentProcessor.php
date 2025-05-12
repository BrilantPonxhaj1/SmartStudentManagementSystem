<?php
// app/Processors/EnrollmentProcessor.php

namespace App\Processors;

use App\Repositories\EnrollmentRepository;
use App\Models\Student;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use Illuminate\Validation\ValidationException;
// Me kqyr a me lon a me hek  qet proc nese met veq 1 metod
class EnrollmentProcessor
{
    public function __construct(protected EnrollmentRepository $repo) {}

    /**
     * Handle registration logic.
     * @throws ValidationException
     */
    public function register(Student $student, CourseOffering $offering): Enrollment
    {
        return $this->repo->register($student, $offering);
    }

    public function cancel(Enrollment $enrollment): void
    {
      $this->repo->cancelEnrollment($enrollment);
    }
}
