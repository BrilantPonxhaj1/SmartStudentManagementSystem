<?php

namespace App\Http\Controllers\Api\Student;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Models\CourseOffering;
use App\Models\Enrollment;
use App\Processors\EnrollmentProcessor;
    use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Throwable;

class EnrollmentController extends Controller
{
    public function __construct(protected EnrollmentProcessor $processor) {}

    /**
     * POST /api/student/course_offerings/{courseOffering}/register
     */
    public function register(CourseOffering $courseOffering): JsonResponse
    {
        $student = auth()->user()->studentProfile;
        try {
            $this->processor->register($student, $courseOffering);
            return ApiResponseFactory::success(
                ['message' => 'Successfully registered.'],
                201
            );
        } catch (ValidationException $e) {
            $first = Arr::flatten($e->errors())[0] ?? $e->getMessage();
            return ApiResponseFactory::error($first, 422);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/student/enrollments/{enrollment}
     */
    public function destroy(Enrollment $enrollment): JsonResponse
    {
        try {
            $this->processor->cancel($enrollment);
            return ApiResponseFactory::success(['message' => 'Enrollment cancelled.']);
        }catch (ValidationException $e){
            return ApiResponseFactory::error($e->getMessage(), 422);
        }
    }
}
