<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfessorRequest;
use App\Http\Resources\ProfessorResource;
use App\Processors\AdminProcessors\ProfessorProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Requests\StoreProfessorRequest;
USE Throwable;


class ProfessorController extends BaseAdminController
{
    protected ProfessorProcessor $processor;

    public function __construct(ProfessorProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * GET /admin/professors
     */
    public function index(): JsonResponse
    {
        try {
            $profs = $this->processor
                ->listWithUser();
            return ApiResponseFactory::success([
                'data' => ProfessorResource::collection($profs)
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Failed to retrieve professors', $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * GET /admin/professors/{id}
     */
    public function show(int $id): JsonResponse
    {
        try {
            $prof = $this->processor->get($id)->load('user');
            return ApiResponseFactory::success([
                'data' => new ProfessorResource($prof)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Professor not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve professor', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * POST /admin/professors
     */
    public function store(StoreProfessorRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $prof = $this->processor->create($data);
            $prof->load('user');
            return ApiResponseFactory::success([
                'message' => 'Professor created successfully',
                'data'    => new ProfessorResource($prof)
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return response()->json([
                'error' => 'Error creating Professor!', $e
            ], Response::HTTP_INTERNAL_SERVER_ERROR);        }
    }

    /**
     * PUT /admin/professors/{id}
     */
    public function update(UpdateProfessorRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $prof = $this->processor->update($id, $data);
            $prof->load('user');
            return ApiResponseFactory::success([
                'message' => 'Professor updated successfully',
                'data'    => new ProfessorResource($prof)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Professor not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * DELETE /admin/professors/{id}
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->processor->delete($id);
            return ApiResponseFactory::success([
                'message' => 'Professor deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Professor not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error deleting professor', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
