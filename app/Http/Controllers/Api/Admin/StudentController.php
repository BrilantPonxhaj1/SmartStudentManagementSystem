<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentsRequests;
use App\Http\Requests\UpdateStudentsRequests;
use App\Processors\AdminProcessors\StudentProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class StudentController extends Controller
{
    public function __construct(
        protected StudentProcessor $processor
    ){}
    public function store(StoreStudentsRequests $request): JsonResponse
    {
        try{
            $user = $this->processor->create($request->validated());
        }catch (Throwable $exception){
            return response()->json([
                'message' => 'Error creating student',
                'error'   => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Student created successfully',
            'user'    => $user,
        ], Response::HTTP_CREATED);

    }
    public function index(): JsonResponse
    {
        $list = $this->processor->list();
        return response()->json([
            'data' => $list,
        ], Response::HTTP_OK);

    }
    public function destroy(int $id): JsonResponse
    {
        try{
            $this->processor->delete($id);
            return response()->json([
                'message' => 'Student deleted successfully',
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'Student not found',
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch (Throwable $e){
            return response()->json([
                'message' => 'Error deleting student',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateStudentsRequests $request, int $id): JsonResponse {
        try{
            $user = $this->processor->update($id, $request->validated());
        }catch (Throwable $e){
            return response()->json([
                'message' => 'Error updating student',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        return response()->json([
            'message' => 'Student updated successfully',
            'user'    => $user,
        ], Response::HTTP_OK);

    }



}
