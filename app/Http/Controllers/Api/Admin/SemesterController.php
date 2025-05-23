<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\StoreSemesterRequest;
use App\Http\Requests\UpdateSemesterRequest;
use App\Http\Resources\SemesterResource;
use App\Processors\SemesterProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Throwable;
/**
 * @OA\Tag(
 *   name="Semesters",
 *   description="CRUD operations for academic semesters"
 * )
 */
class SemesterControlle
{
    public function __construct(protected SemesterProcessor $processor)
    {
    }
    /**
     * @OA\Post(
     *     path="/api/admin/semesters",
     *     operationId="createSemester",
     *     tags={"Semesters"},
     *     summary="Create a new semester",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreSemesterRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Semester created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Semester")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function store(StoreSemesterRequest $request): JsonResponse
    {
        try{
            $data = $request->validated();
            $semester = $this->processor->create($data);
            return response()->json([
                'data' => new SemesterResource($semester)
            ], Response::HTTP_CREATED);
        }catch (Exception $exception) {
            return response()->json([
                'message' => 'Error creating semester',
                'error' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Put(
     *     path="/api/admin/semesters/{id}",
     *     operationId="updateSemester",
     *     tags={"Semesters"},
     *     summary="Update an existing semester",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Semester ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateSemesterRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Semester updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Semester")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function update(UpdateSemesterRequest $request, int $id): JsonResponse
    {
        try{
            $data = $request->validated();
            $old = $this->processor->get($id);
            $data['university_id'] = $old->university_id;
            $newSemester = $this->processor->update($id,$data);
            return response()->json([
                'data' => new SemesterResource($newSemester)
            ], Response::HTTP_OK);
        }catch (Exception $exception) {
            return response()->json([
                'message' => 'Error updating semester',
                'error' => $exception->getMessage()
            ]);
        }
    }
    /**
     * @OA\Delete(
     *     path="/api/admin/semesters/{id}",
     *     operationId="deleteSemester",
     *     tags={"Semesters"},
     *     summary="Delete a semester",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Semester ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Semester deleted successfully"),
     *     @OA\Response(response=404, description="Semester not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try{
            $this->processor->delete($id);
            return response()->json([
               'message' => 'Semester deleted successfully'
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $exception) {
            return response()->json([
                'message'=>'Semester not found',
                "error" => $exception->getMessage()]
                , Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/admin/semesters/university/{universityId}",
     *     operationId="getSemestersByUniversity",
     *     tags={"Semesters"},
     *     summary="Get semesters by university",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="universityId",
     *         in="path",
     *         description="University ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of semesters",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Semester")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function getByUniversity(int $universityId): JsonResponse
    {
        try {
            $semesters = $this->processor->semestersByUniversity($universityId);

            return response()->json([
                'data' => SemesterResource::collection($semesters)
            ], Response::HTTP_OK);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Failed to fetch semesters by university',
                'error'   => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
