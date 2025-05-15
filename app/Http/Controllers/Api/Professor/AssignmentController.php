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


class AssignmentController extends Controller
{

    protected AssignmentProcessor $processor;
    public function __construct(AssignmentProcessor $processor)
    {
        $this->processor = $processor;
    }

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
     * GET /api/professor/assignments/{id}
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
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
     * POST /api/professor/assignments
     *
     * @param StoreAssignmentsRequest $request
     * @return JsonResponse
     * @throws Exception
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
     * DELETE /api/professor/assignments/{id}
     *
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     * @throws ModelNotFoundException
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
