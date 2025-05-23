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

/**
 * @OA\Tag(
 *   name="Professors",
 *   description="CRUD operations for academic professors"
 * )
 */
class ProfessorController
{
    protected ProfessorProcessor $processor;

    public function __construct(ProfessorProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @OA\Get(
     *     path="/api/admin/professors",
     *     operationId="listProfessors",
     *     tags={"Professors"},
     *     summary="Get a list of all professors",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A collection of professors",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Professor")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal server error")
     * )
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
     * @OA\Get(
     *     path="/api/admin/professors/{id}",
     *     operationId="getProfessorById",
     *     tags={"Professors"},
     *     summary="Retrieve a single professor by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Professor ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Professor details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Professor")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Professor not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
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
     * @OA\Get(
     *     path="/api/admin/professors/department/{id}",
     *     operationId="getProfessorsByDepartment",
     *     tags={"Professors"},
     *     summary="List professors by department ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Professors in the specified department",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Professor")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function getProfessorsByDepartment(int $id): JsonResponse{
        try{
            $profs = $this->processor->professorsByDepartment($id);
            return ApiResponseFactory::success([
                'data' => ProfessorResource::collection($profs)
            ], Response::HTTP_OK);
        }catch (Throwable $e) {
            return ApiResponseFactory::error(
                'Failed to fetch professors by department ',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * @OA\Post(
     *     path="/api/admin/professors",
     *     operationId="createProfessor",
     *     tags={"Professors"},
     *     summary="Create a new professor",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreProfessorRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Professor created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Professor created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Professor")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
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
     * @OA\Put(
     *     path="/api/admin/professors/{id}",
     *     operationId="updateProfessor",
     *     tags={"Professors"},
     *     summary="Update an existing professor",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Professor ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateProfessorRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Professor updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Professor updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/Professor")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Professor not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
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
     * @OA\Delete(
     *     path="/api/admin/professors/{id}",
     *     operationId="deleteProfessor",
     *     tags={"Professors"},
     *     summary="Delete a professor",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Professor ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Professor deleted successfully"),
     *     @OA\Response(response=404, description="Professor not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
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
