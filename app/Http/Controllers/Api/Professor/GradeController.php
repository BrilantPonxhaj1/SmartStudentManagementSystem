<?php

namespace App\Http\Controllers\Api\Professor;

use App\Http\Controllers\Controller;
use App\Http\Requests\GradeRequest;
use App\Http\Resources\GradeResource;
use App\Processors\GradeProcessor;
use Illuminate\Http\JsonResponse;
use Exception;

class GradeController extends Controller
{
    public function __construct(protected GradeProcessor $processor)
    {}

    /**
     * GET /api/professor/grades
     *
     */
    public function index(): JsonResponse
    {
        try{
            $grades = $this->processor->list();
            return response()->json(['data' => GradeResource::collection($grades)]);
        }catch (Exception $exception){
            return response()->json([
                'message' => 'Error fetching grades',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * GET /api/professor/grades/{id}
     * @param int $id
     */

    public function show(int $id): JsonResponse
    {

        try{
            $grade = $this->processor->get($id);
            return response()->json(['data' => new GradeResource($grade)]);
        }catch (Exception $exception){
            return response()->json([
                'message' => 'Error fetching grade',
                'error' => $exception->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/professor/grades
     * @param array $data
     */
    public function store(GradeRequest $request): JsonResponse{
     $data = $request->validated();
        try{
            $grade = $this->processor->create($data);
            return response()->json([
                'data' => new GradeResource($grade)
            ], 201);
        }catch (Exception $e){
            return response()->json([
                'message' => 'Error storing grade',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * DELETE /api/professor/grades/{id}
     * @param int $id
     */

    public function destroy(int $id): JsonResponse {
        try {
            $this->processor->delete($id);
            return response()->json([
                'message' => 'Grade deleted successfully'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Error deleting grade',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
