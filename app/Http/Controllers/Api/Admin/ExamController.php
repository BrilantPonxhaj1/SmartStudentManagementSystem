<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Resources\ExamResource;
use App\Processors\AdminProcessors\ExamProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
/**
 * @OA\Tag(
 *   name="Exams",
 *   description="CRUD operations for exams"
 * )
 */
class ExamController extends BaseAdminController
{
    protected ExamProcessor $processor;

    public function __construct(ExamProcessor $processor)
    {
        $this->processor = $processor;
    }
    /**
     * @OA\Get(
     *     path="/api/admin/exams",
     *     operationId="listExams",
     *     tags={"Exams"},
     *     summary="Get all exams",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A list of exams",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Exam")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $exams = $this->processor->allExams();
            return ApiResponseFactory::success([
                'data' => ExamResource::collection($exams)
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/admin/exams/{id}",
     *     operationId="getExamById",
     *     tags={"Exams"},
     *     summary="Get a specific exam by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exam ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exam details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Exam")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Exam not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $exam = $this->processor->get($id);
            return ApiResponseFactory::success([
                'data' => new ExamResource($exam)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Exam not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve exam', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/admin/exams",
     *     operationId="createExam",
     *     tags={"Exams"},
     *     summary="Create a new exam",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreExamRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Exam created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Exam created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Exam")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(StoreExamRequest $request): JsonResponse
    {
        try {
            $exam = $this->processor->create($request->validated());
            return ApiResponseFactory::success([
                'message' => 'Exam created successfully',
                'data' => new ExamResource($exam)
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/admin/exams/{id}",
     *     operationId="updateExam",
     *     tags={"Exams"},
     *     summary="Update an existing exam",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exam ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateExamRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Exam updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Exam updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Exam")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Exam not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(UpdateExamRequest $request, int $id): JsonResponse
    {
        try {
            $exam = $this->processor->update($id, $request->validated());
            return ApiResponseFactory::success([
                'message' => 'Exam updated successfully',
                'data' => new ExamResource($exam)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/admin/exams/{id}",
     *     operationId="deleteExam",
     *     tags={"Exams"},
     *     summary="Delete an exam",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Exam ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Exam deleted successfully"),
     *     @OA\Response(response=404, description="Exam not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->processor->delete($id);
            return ApiResponseFactory::success(['message' => 'Exam deleted successfully'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Exam not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error deleting exam', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
