<?php

namespace App\Http\Controllers\Api\Professor;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssignmentsRequest;
use App\Http\Requests\UpdateAssignmentsRequest;
use App\Http\Resources\AssignmentsResource;
use App\Processors\AssignmentProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * @OA\Tag(
 *   name="Assignments",
 *   description="Endpoints for professors to manage assignments"
 * )
 */
class AssignmentController extends Controller
{

    protected AssignmentProcessor $processor;
    public function __construct(AssignmentProcessor $processor)
    {
        $this->processor = $processor;
    }
    /**
     * @OA\Get(
     *     path="/api/professor/assignments",
     *     operationId="listAssignments",
     *     tags={"Assignments"},
     *     summary="List all assignments for the authenticated professor",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A collection of assignments",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Assignment")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function index(): JsonResponse{
        try{
            $assignments = $this->processor->listWithRelations();
            return ApiResponseFactory::success([
                'data' => AssignmentsResource::collection($assignments)
            ], Response::HTTP_OK);
        }catch (Exception $e){
            return  ApiResponseFactory::error('Failed to retrieve assignments', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/professor/assignments/{id}",
     *     operationId="getAssignmentById",
     *     tags={"Assignments"},
     *     summary="Get a specific assignment by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Assignment ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=42)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Assignment details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data",   ref="#/components/schemas/Assignment")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Assignment not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function show(int $id): JsonResponse{
        try{
            $assignment = $this->processor->get($id);
            return ApiResponseFactory::success([
                'data' => new AssignmentsResource($assignment)
            ], Response::HTTP_OK);
        }catch (Exception $e){
            return response()->json([
                'message' => 'Assignment not found',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/professor/assignments",
     *     operationId="createAssignment",
     *     tags={"Assignments"},
     *     summary="Create a new assignment",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreAssignmentsRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Assignment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data",   ref="#/components/schemas/Assignment")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function store(StoreAssignmentsRequest $request): JsonResponse{
        try{
            $data = $request->validated();
            $assignment = $this->processor->create($data);
            return  ApiResponseFactory::success([
                'data' => new AssignmentsResource($assignment)
            ], Response::HTTP_CREATED);
        }catch (Exception $e){
            return response()->json([
                'message' => 'Error creating assignment',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/professor/assignments/{id}",
     *     operationId="updateAssignment",
     *     tags={"Assignments"},
     *     summary="Update an existing assignment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Assignment ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=42)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateAssignmentsRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Assignment updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Assignment updated successfully"),
     *             @OA\Property(property="data",    ref="#/components/schemas/Assignment")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Assignment not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function update(UpdateAssignmentsRequest $request, int $id): JsonResponse{
        try{
            $data = $request->validated();
            $assignment = $this->processor->update($id, $data);
            return ApiResponseFactory::success([
                'message' => 'Assignment updated successfully',
                'data' => new AssignmentsResource($assignment)
            ]);

        }catch (ModelNotFoundException $e){
            return ApiResponseFactory::error('Assignment not found', Response::HTTP_NOT_FOUND);
        }
        catch (Exception $e){
            return response()->json([
                'message' => 'Error updating assignment',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/professor/assignments/{id}",
     *     operationId="deleteAssignment",
     *     tags={"Assignments"},
     *     summary="Delete an assignment",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Assignment ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=42)
     *     ),
     *     @OA\Response(response=200, description="Assignment deleted successfully"),
     *     @OA\Response(response=404, description="Assignment not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function destroy(int $id): JsonResponse{
        try{
            $this->processor->delete($id);
            return ApiResponseFactory::success([
                'message' => 'Assignment deleted successfully'
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e){
            return ApiResponseFactory::error('Assignment not found', Response::HTTP_NOT_FOUND);
        }catch (Exception $e){
            return response()->json([
                'message' => 'Error deleting assignment',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
