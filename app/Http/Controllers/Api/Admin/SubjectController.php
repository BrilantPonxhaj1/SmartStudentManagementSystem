<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Processors\AdminProcessors\SubjectProcessor;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
/**
 * @OA\Tag(
 *   name="Subjects",
 *   description="CRUD operations for academic subjects"
 * )
 */
class SubjectController extends BaseAdminController
{
    protected SubjectProcessor $processor;

    public function __construct(SubjectProcessor $processor)
    {
        $this->processor = $processor;
    }
    /**
     * @OA\Get(
     *     path="/api/admin/subjects",
     *     operationId="listSubjects",
     *     tags={"Subjects"},
     *     summary="Get a list of all subjects",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A collection of subjects",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Subject")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function index(): JsonResponse
    {
        try {
            $subjects = $this->processor->allSubjects();
            return ApiResponseFactory::success([
                'data' => SubjectResource::collection($subjects)
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR, ['error' => $e->getMessage()]);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/admin/subjects/{id}",
     *     operationId="getSubjectById",
     *     tags={"Subjects"},
     *     summary="Retrieve a single subject by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Subject ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subject details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Subject")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Subject not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $subject = $this->processor->get($id);
            return ApiResponseFactory::success([
                'data' => new SubjectResource($subject)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Subject not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve subject', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/admin/subjects",
     *     operationId="createSubject",
     *     tags={"Subjects"},
     *     summary="Create a new subject",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreSubjectRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Subject created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Subject created successfully"),
     *             @OA\Property(property="data",    ref="#/components/schemas/Subject")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function store(StoreSubjectRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $subject = $this->processor->create($data);
            return ApiResponseFactory::success([
                'message' => 'Subject created successfully',
                'data'    => new SubjectResource($subject)
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/admin/subjects/{id}",
     *     operationId="updateSubject",
     *     tags={"Subjects"},
     *     summary="Update an existing subject",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Subject ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSubjectRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subject updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Subject updated successfully"),
     *             @OA\Property(property="data",    ref="#/components/schemas/Subject")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Subject not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function update(UpdateSubjectRequest $request, int $id): JsonResponse
    {
        try{
            $data = $request->validated();
            $subject = $this->processor->update($id, $data);
            return ApiResponseFactory::success([
                'message' => 'Subject updated successfully',
                'data'    => new SubjectResource($subject)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/admin/subjects/{id}",
     *     operationId="deleteSubject",
     *     tags={"Subjects"},
     *     summary="Delete a subject",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Subject ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Subject deleted successfully"),
     *     @OA\Response(response=404, description="Subject not found"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->processor->delete($id);
            return ApiResponseFactory::success([
                'message' => 'Subject deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('Subject not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error deleting subject', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/admin/subjects/department/{deptId}",
     *     operationId="getSubjectsByDepartment",
     *     tags={"Subjects"},
     *     summary="List subjects by department ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="deptId",
     *         in="path",
     *         description="Department ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=2)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Subjects in the specified department",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Subject")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function byDepartment(int $deptId): JsonResponse
    {
        try{
            $subjects = $this->processor->getSubjectsByDeptId($deptId);

            return ApiResponseFactory::success([
                'data' => SubjectResource::collection($subjects)
            ], Response::HTTP_OK);

        }catch (Throwable $e) {
            return ApiResponseFactory::error(
                'Failed to fetch subjects by department',
                Response::HTTP_INTERNAL_SERVER_ERROR,
                ['error' => $e->getMessage()]
            );
        }

    }
}
