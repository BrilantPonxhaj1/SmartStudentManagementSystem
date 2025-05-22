<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Requests\UpdateComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Processors\ComplaintProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class AdminComplaintController extends BaseAdminController
{
    protected ComplaintProcessor $processor;

    public function __construct(ComplaintProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function index(): JsonResponse
    {
        try {
            $complaints = $this->processor->allComplaints();
            return ApiResponseFactory::success([
                'data' => ComplaintResource::collection($complaints)
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOpenComplaints(): JsonResponse
    {
        try {
            $complaints = $this->processor->getAllExceptClosed();
            return ApiResponseFactory::success([
                'data' => ComplaintResource::collection($complaints)
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateComplaintRequest $request, int $id): JsonResponse
    {
        try {
            $complaint = $this->processor->update($id, $request->validated());
            return ApiResponseFactory::success([
                'message' => 'Complaint updated successfully',
                'data' => new ComplaintResource($complaint)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
