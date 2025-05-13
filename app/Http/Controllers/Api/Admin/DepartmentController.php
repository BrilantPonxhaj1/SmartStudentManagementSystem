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

class DepartmentController extends BaseAdminController
{
    public function __construct(protected DepartmentProcessor $processor)
    {
    }
    /** GET /api/admin/departments */
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

    /** POST /api/admin/departments */
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


    /** DELETE /api/admin/departments/{id} */
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

    /** PUT /api/admin/departments/{id} */
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

    /** GET /api/admin/departments/{id} */
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
