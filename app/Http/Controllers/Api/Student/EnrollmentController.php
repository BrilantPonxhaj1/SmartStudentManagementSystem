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
/**
 * @OA\Tag(
 *   name="Enrollments",
 *   description="Endpoints for student enrollments"
 * )
 */
class EnrollmentController extends Controller
{
    public function __construct(protected EnrollmentProcessor $processor) {}

    /**
     * @OA\Post(
     *     path="/api/student/course_offerings/{courseOffering}/register",
     *     operationId="registerForCourseOffering",
     *     tags={"Enrollments"},
     *     summary="Register the authenticated student for a course offering",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="courseOffering",
     *         in="path",
     *         description="ID of the course offering to register for",
     *         required=true,
     *         @OA\Schema(type="integer", example=7)
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Registration successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Successfully registered.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error (e.g., already registered)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="You are already registered for this course.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred.")
     *         )
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/student/enrollments/{enrollment}",
     *     operationId="cancelEnrollment",
     *     tags={"Enrollments"},
     *     summary="Cancel an existing enrollment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="enrollment",
     *         in="path",
     *         description="ID of the enrollment to cancel",
     *         required=true,
     *         @OA\Schema(type="integer", example=15)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Enrollment cancelled successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Enrollment cancelled.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error (e.g., cannot cancel past enrollment)",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Cannot cancel this enrollment.")
     *         )
     *     )
     * )
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
