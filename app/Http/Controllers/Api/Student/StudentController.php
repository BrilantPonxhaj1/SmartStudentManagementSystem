<?php

namespace App\Http\Controllers\Api\Student;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfessorResource;
use App\Http\Resources\StudentResource;
use App\Processors\AdminProcessors\StudentProcessor;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class StudentController extends Controller
{

    protected StudentProcessor $processor;

    public function __construct(StudentProcessor $processor)
    {
        $this->processor = $processor;
    }
    /**
     * @OA\Get(
     *     path="/api/admin/getStudentByUser",
     *     operationId="getStudentByUser",
     *     tags={"Students"},
     *     summary="Get the student profile for the authenticated user",
     *     description="Returns the student information based on the authenticated user's ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Student profile retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=404, description="Student not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     * @throws Throwable
     */
    public function getStudentByUser()
    {
        try {
            $user = auth()->user();

            if (!$user || $user->getType() !== 'student') {
                return ApiResponseFactory::error('Unauthorized', Response::HTTP_UNAUTHORIZED);
            }

            $student = $this->processor->getStudentByUserId($user->id);

            if (!$student) {
                return ApiResponseFactory::error('Student not found', Response::HTTP_NOT_FOUND);
            }

            return ApiResponseFactory::success([
                'data' => new StudentResource($student)
            ]);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/student/professors",
     *     operationId="getProfessorsByStudentDept",
     *     tags={"Students"},
     *     summary="Get professors in the authenticated student's department",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Professors fetched successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Professor")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function getProfessorsByStudentDept(): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user || $user->getType() !== 'student') {
                return ApiResponseFactory::error('Unauthorized', Response::HTTP_UNAUTHORIZED);
            }

            $professors = $this->processor->getProfessorsForAuthenticatedStudent($user->id);

            return ApiResponseFactory::success([
                'data' => ProfessorResource::collection($professors)
            ]);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Something went wrong', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
