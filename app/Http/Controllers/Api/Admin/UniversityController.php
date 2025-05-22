<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUniversityRequest;
use App\Http\Requests\UpdateUniversityRequest;
use App\Http\Resources\UniversityResource;
use App\Processors\AdminProcessors\UniversityProcessor;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;
/**
 * @OA\Tag(
 *   name="Universities",
 *   description="CRUD operations for universities"
 * )
 */
class UniversityController extends Controller
{
    public function __construct(protected UniversityProcessor $processor)
    {
        $this->processor = $processor;
    }
    /**
     * @OA\Get(
     *     path="/api/admin/universities",
     *     operationId="listUniversities",
     *     tags={"Universities"},
     *     summary="Get a list of all universities (for dropdown/select)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Array of universities",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/University")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index(): JsonResponse {
        try{
            $unis= $this->processor->listForSelect();
            return response()->json(
                $unis
            );
        }catch (Exception $exception){
            return response()->json([
                'message' => 'Failed to fetch universities -> ', $exception
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/admin/universities/{id}",
     *     operationId="getUniversityById",
     *     tags={"Universities"},
     *     summary="Retrieve a single university by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="University ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="University details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data",   ref="#/components/schemas/University")
     *         )
     *     ),
     *     @OA\Response(response=404, description="University not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $university = $this->processor->get($id);
            return ApiResponseFactory::success([
                'data' => new UniversityResource($university)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('University not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Failed to retrieve university', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/admin/universities",
     *     operationId="createUniversity",
     *     tags={"Universities"},
     *     summary="Create a new university",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreUniversityRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="University created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="University created successfully"),
     *             @OA\Property(property="data",    ref="#/components/schemas/University")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(StoreUniversityRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $university = $this->processor->create($data);
            return ApiResponseFactory::success([
                'message' => 'University created successfully',
                'data'    => new UniversityResource($university)
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/admin/universities/{id}",
     *     operationId="updateUniversity",
     *     tags={"Universities"},
     *     summary="Update an existing university",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="University ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateUniversityRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="University updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="University updated successfully"),
     *             @OA\Property(property="data",    ref="#/components/schemas/University")
     *         )
     *     ),
     *     @OA\Response(response=404, description="University not found"),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(UpdateUniversityRequest $request, int $id): JsonResponse
    {
        try {
            $data = $request->validated();
            $university = $this->processor->update($id, $data);
            return ApiResponseFactory::success([
                'message' => 'University updated successfully',
                'data'    => new UniversityResource($university)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('University not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/universities/{id}",
     *     operationId="deleteUniversity",
     *     tags={"Universities"},
     *     summary="Delete a university",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="University ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="University deleted successfully"),
     *     @OA\Response(response=404, description="University not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->processor->delete($id);
            return ApiResponseFactory::success([
                'message' => 'University deleted successfully'
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return ApiResponseFactory::error('University not found', Response::HTTP_NOT_FOUND);
        } catch (Throwable $e) {
            return ApiResponseFactory::error('Error deleting university', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
