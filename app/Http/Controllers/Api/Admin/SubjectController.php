<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Processors\AdminProcessors\SubjectProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class SubjectController extends BaseAdminController
{
    protected SubjectProcessor $processor;

    public function __construct(SubjectProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function index(): JsonResponse
    {
        try {
            $subjects = $this->processor->listWithRelations();
            return ApiResponseFactory::success([
                'data' => SubjectResource::collection($subjects)
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error fetching subjects.', Response::HTTP_INTERNAL_SERVER_ERROR, ['error' => $e->getMessage()]);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $subject = $this->processor->get($id);
            return ApiResponseFactory::success([
                'data' => new SubjectResource($subject)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Subject not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve subject', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(StoreSubjectRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $subject = $this->processor->create($data);
            return ApiResponseFactory::success([
                'message' => 'Subject created successfully',
                'data'    => new SubjectResource($subject)
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error creating subject', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateSubjectRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $subject = $this->processor->update($id, $data);
            return ApiResponseFactory::success([
                'message' => 'Subject updated successfully',
                'data'    => new SubjectResource($subject)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Subject not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error updating subject', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->processor->delete($id);
            return ApiResponseFactory::success([
                'message' => 'Subject deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Subject not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error deleting subject', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
