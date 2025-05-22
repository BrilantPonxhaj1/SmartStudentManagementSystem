<?php

namespace App\Http\Controllers\Api\Professor;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Professor;
use App\Processors\AppointmentProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ProfessorAppointmentController extends Controller
{
    protected AppointmentProcessor $processor;

    public function __construct(AppointmentProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function getProfessorAppointments(): JsonResponse
    {
        try {
            $user = auth()->user();

            if (!$user || !$user->professorProfile) {
                return ApiResponseFactory::error('Professor not found.', Response::HTTP_FORBIDDEN);
            }

            $professorId = $user->professorProfile->id;

            $appointments = $this->processor->appointmentsByProfessor($professorId);

            return ApiResponseFactory::success([
                'data' => AppointmentResource::collection($appointments)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Professor not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve appointments', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $appointment = $this->processor->get($id);
            return ApiResponseFactory::success([
                'data' => new AppointmentResource($appointment)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Appointment not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve appointment', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update(UpdateAppointmentRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $appointment = $this->processor->update($id, $data);
            return ApiResponseFactory::success([
                'message' => 'Appointment updated successfully',
                'data' => new AppointmentResource($appointment)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Appointment not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->processor->delete($id);
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
