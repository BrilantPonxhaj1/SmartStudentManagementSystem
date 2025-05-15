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

class ExamController extends BaseAdminController
{
    protected ExamProcessor $processor;

    public function __construct(ExamProcessor $processor)
    {
        $this->processor = $processor;
    }

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
