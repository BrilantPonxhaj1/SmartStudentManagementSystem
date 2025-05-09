<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUniversityRequest;
use App\Http\Requests\UpdateUniversityRequest;
use App\Http\Resources\UniversityResource;
use App\Processors\AdminProcessors\UniversityProcessor;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class UniversityController extends Controller
{
    protected UniversityProcessor $processor;
    public function __construct(UniversityProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function index(): JsonResponse {
        try{
            $unis= $this->processor->listForSelect();
            return response()->json(
                $unis
            );
        }catch (Exception $exception){
            return response()->json([
                'message' => 'Failed to fetch universities -> ', $exception
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // GET -> admin/universities/{id}
    public function show(int $id): JsonResponse
    {
        try {
            $university = $this->processor->get($id);
            return ApiResponseFactory::success([
                'data' => new UniversityResource($university)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('University not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve university', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // POST -> admin/universities
    public function store(StoreUniversityRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $university = $this->processor->create($data);
            return ApiResponseFactory::success([
                'message' => 'University created successfully',
                'data'    => new UniversityResource($university)
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // PUT -> admin/universities/{id}
    public function update(UpdateUniversityRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $university = $this->processor->update($id, $data);
            return ApiResponseFactory::success([
                'message' => 'University updated successfully',
                'data'    => new UniversityResource($university)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('University not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // DELETE -> admin/universities/{id}
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->processor->delete($id);
            return ApiResponseFactory::success([
                'message' => 'University deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('University not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error deleting university', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
