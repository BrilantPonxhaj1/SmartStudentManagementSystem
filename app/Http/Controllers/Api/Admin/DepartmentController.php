<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDepartmentRequest;
use App\Http\Requests\UpdateDepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Processors\AdminProcessors\DepartmentProcessor;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

/**
 * @OA\Tag(
 *   name="Departments",
 *   description="CRUD operations for academic departments"
 * )
 */
class DepartmentController extends BaseAdminController
{
    public function __construct(protected DepartmentProcessor $processor)
    {
    }
    /**
     * @OA\Get(
     *     path="/api/admin/departments",
     *     operationId="listDepartments",
     *     tags={"Departments"},
     *     summary="Get list of all departments",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of departments",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Department")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $depts = $this->processor->list();
            return ApiResponseFactory::success(
                DepartmentResource::collection($depts),
                200
            );
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), 500);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/admin/departments",
     *     operationId="createDepartment",
     *     tags={"Departments"},
     *     summary="Create a new department",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","university_id"},
     *             @OA\Property(property="name", type="string", example="Computer Science"),
     *             @OA\Property(property="code", type="string", example="CS101"),
     *             @OA\Property(property="university_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Department created",
     *         @OA\JsonContent(ref="#/components/schemas/Department")
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        try {
            $dept = $this->processor->create($request->validated());
            return ApiResponseFactory::success(
                new DepartmentResource($dept),
                201
            );
        } catch (ValidationException $e) {
            $first = Arr::flatten($e->errors())[0] ?? $e->getMessage();
            return ApiResponseFactory::error($first, 422);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), 500);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/admin/departments/{id}",
     *     operationId="deleteDepartment",
     *     tags={"Departments"},
     *     summary="Delete a department",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=204, description="Department deleted"),
     *     @OA\Response(response=404, description="Department not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->processor->delete($id);
            return ApiResponseFactory::success(['message' => 'Department deleted'], 204);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Department not found', 404);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), 500);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/admin/departments/{id}",
     *     operationId="updateDepartment",
     *     tags={"Departments"},
     *     summary="Update an existing department",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Computer Engineering"),
     *             @OA\Property(property="code", type="string", example="CE202"),
     *             @OA\Property(property="university_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Department updated",
     *         @OA\JsonContent(ref="#/components/schemas/Department")
     *     ),
     *     @OA\Response(response=404, description="Department not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(UpdateDepartmentRequest $request, int $id): JsonResponse
    {
        try {
            $dept = $this->processor->update($id, $request->validated());
            return ApiResponseFactory::success(
                new DepartmentResource($dept),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Department not found', 404);
        } catch (ValidationException $e) {
            $first = Arr::flatten($e->errors())[0] ?? $e->getMessage();
            return ApiResponseFactory::error($first, 422);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/admin/departments/{id}",
     *     operationId="getDepartment",
     *     tags={"Departments"},
     *     summary="Get a specific department by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Department details",
     *         @OA\JsonContent(ref="#/components/schemas/Department")
     *     ),
     *     @OA\Response(response=404, description="Department not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $dept = $this->processor->get($id);
            return ApiResponseFactory::success(
                new DepartmentResource($dept),
                200
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Department not found', 404);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), 500);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/admin/departments/university/{uni}",
     *     operationId="getDepartmentsByUniversity",
     *     tags={"Departments"},
     *     summary="Get departments for a specific university",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="uni",
     *         in="path",
     *         description="University ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of departments",
     *         @OA\JsonContent(type="array",
     *             @OA\Items(ref="#/components/schemas/Department")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getDeptByUniversity(int $uni):JsonResponse {
        try{
            $depts = $this->processor->listByUniversity($uni);
            return response()->json($depts);
        }catch (Exception $exception){
            return response()->json([
                'message' => 'Error fetching departments',
                'error' => $exception->getMessage()
            ], 500);
        }

    }

}
