<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SemesterResource;
use App\Processors\SemesterProcessor;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
/**
 * @OA\Tag(
 *   name="Semesters",
 *   description="Public endpoints for semesters"
 * )
 */
class SemesterController
{  protected SemesterProcessor $processor;
    public function __construct( SemesterProcessor $processor)
    {
        $this->processor = $processor;
    }
    /**
     * @OA\Get(
     *     path="/api/semesters",
     *     operationId="publicListSemesters",
     *     tags={"Semesters"},
     *     summary="Get a list of semesters",
     *     @OA\Response(
     *         response=200,
     *         description="List of semesters",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/Semester")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error fetching semesters")
     * )
     */
    public function index(): JsonResponse
    {
        try{
            $items= $this->processor->list();
            return response()->json([
                'data' => SemesterResource::collection($items)
            ], Response::HTTP_OK);
        }catch (Exception $exception) {
            return response()->json([
                'message' => 'Error fetching semesters',
                'error' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/semesters/{id}",
     *     operationId="publicGetSemesterById",
     *     tags={"Semesters"},
     *     summary="Get details of a single semester",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Semester ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Semester details",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/Semester")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Semester not found"),
     *     @OA\Response(response=500, description="Error retrieving semester")
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $sem = $this->processor->get($id);
            return response()->json([
                'data' => new SemesterResource($sem)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message'=>'Semester not found',
                "error" => $exception->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

}
