<?php

namespace App\Http\Controllers\Api\Student;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComplaintRequest;
use App\Http\Resources\ComplaintResource;
use App\Processors\ComplaintProcessor;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class StudentComplaintController extends Controller
{
    protected ComplaintProcessor $processor;

    public function __construct(ComplaintProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function getStudentComplaintsByUserId(int $userId): JsonResponse
    {
        try {
            $complaints = $this->processor->getAllByUserId($userId);
            return ApiResponseFactory::success([
                'data' => ComplaintResource::collection($complaints)
            ]);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve complaints for user', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function storeStudentComplaint(StoreComplaintRequest $request): JsonResponse
    {
        try {
            $complaint = $this->processor->create($request->validated());
            return ApiResponseFactory::success([
                'message' => 'Complaint created successfully',
                'data' => new ComplaintResource($complaint)
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
