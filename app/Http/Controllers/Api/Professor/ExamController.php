<?php

namespace App\Http\Controllers\Api\Professor;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use App\Http\Resources\ExamResource;
use App\Models\User;
use App\Processors\ExamProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExamController extends Controller
{
    protected ExamProcessor $processor;

    public function __construct(ExamProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function index(): JsonResponse
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            if (!$user || !$user->professorProfile) {
                return ApiResponseFactory::error('Professor not found.', Response::HTTP_FORBIDDEN);
            }

            $professorId = $user->professorProfile->id;
            $exams = $this->processor->allForProfessor($professorId);

            return ApiResponseFactory::success([
                'data' => ExamResource::collection($exams)
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve exams', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            if (!$user || !$user->professorProfile) {
                return ApiResponseFactory::error('Professor not found.', Response::HTTP_FORBIDDEN);
            }

            $professorId = $user->professorProfile->id;
            $exam = $this->processor->getOwnedByProfessor($id, $professorId);

            if (!$exam) {
                return ApiResponseFactory::error('Exam not found', Response::HTTP_NOT_FOUND);
            }

            return ApiResponseFactory::success([
                'data' => new ExamResource($exam)
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve exam', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreExamRequest $request): JsonResponse
    {
        try {
            /** @var User $user */
            $user = auth()->user();

            if (!$user || !$user->professorProfile) {
                return ApiResponseFactory::error('Professor not found.', Response::HTTP_FORBIDDEN);
            }

            $professorId = $user->professorProfile->id;
            $exam = $this->processor->create($request->validated(), $professorId);

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
            /** @var User $user */
            $user = auth()->user();

            if (!$user || !$user->professorProfile) {
                return ApiResponseFactory::error('Professor not found.', Response::HTTP_FORBIDDEN);
            }

            $professorId = $user->professorProfile->id;
            $exam = $this->processor->update($id, $request->validated(), $professorId);

            return ApiResponseFactory::success([
                'message' => 'Exam updated successfully',
                'data' => new ExamResource($exam)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Exam not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            /** @var User $user */
            $user = auth()->user();
            $professorId = $user->professorProfile->id;
            $this->processor->delete($id);

            return ApiResponseFactory::success(['message' => 'Exam deleted successfully'], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Exam not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error deleting exam', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
