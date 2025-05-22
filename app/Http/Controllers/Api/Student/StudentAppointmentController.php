<?php

namespace App\Http\Controllers\Api\Student;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Processors\AppointmentProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class StudentAppointmentController extends Controller
{
    protected AppointmentProcessor $processor;

    public function __construct(AppointmentProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function getStudentCurrentAppointments(): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user || !$user->studentProfile) {
                return ApiResponseFactory::error('Student not found.', Response::HTTP_FORBIDDEN);
            }

            $studentId = $user->studentProfile->id;

            $appointments = $this->processor->studentCurrentAppointments($studentId);

            return ApiResponseFactory::success([
                'data' => AppointmentResource::collection($appointments)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Professor not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve appointments', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function store(StoreAppointmentRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $appointment = $this->processor->create($data);
            return ApiResponseFactory::success([
                'message' => 'Appointment created successfully',
                'data' => new AppointmentResource($appointment)
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->processor->deleteForStudent($id);
            return ApiResponseFactory::success([
                'message' => 'Appointment deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Appointment not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error deleting appointment', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
