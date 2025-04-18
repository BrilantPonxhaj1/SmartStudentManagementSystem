<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfessorRequest;
use App\Processors\AdminProcessors\ProfessorProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Requests\StoreProfessorRequest;



class ProfessorController extends Controller
{
    public function __construct(
        protected ProfessorProcessor $processor
    ){}

    /**
     * Create a new professor.
     *
     * @OA\Post(
     *     path="/admin/professors",
     *     summary="Create professor",
     *     tags={"Professors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"first_name","last_name","email","phone","password","role"},
     *             @OA\Property(property="first_name", type="string"),
     *             @OA\Property(property="last_name",  type="string"),
     *             @OA\Property(property="email",      type="string", format="email"),
     *             @OA\Property(property="phone",      type="string"),
     *             @OA\Property(property="password",   type="string", format="password"),
     *             @OA\Property(property="role",       type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Professor created",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="user",    type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function store(StoreProfessorRequest $request): JsonResponse
    {
        try{
            $user = $this->processor->create($request->validated());
        }catch (\Throwable $exception){
            return response()->json([
                'message' => 'Error creating professor',
                'error'   => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Professor created successfully',
            'user'    => $user,
        ], Response::HTTP_CREATED);

    }


    /**
     * List all professors.
     *
     * @OA\Get(
     *     path="/admin/professors",
     *     summary="List professors",
     *     tags={"Professors"},
     *     @OA\Response(
     *         response=200,
     *         description="An array of professors",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id",          type="integer"),
     *                     @OA\Property(property="first_name",  type="string"),
     *                     @OA\Property(property="last_name",   type="string"),
     *                     @OA\Property(property="email",       type="string", format="email"),
     *                     @OA\Property(property="phone",       type="string"),
     *                     @OA\Property(property="role",        type="string")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */

    public function index(): JsonResponse
    {
        $list = $this->processor->list();
        return response()->json([
            'data' => $list,
        ], Response::HTTP_OK);

    }

    /**
     * Delete a professor.
     *
     * @OA\Delete(
     *     path="/admin/professors/{id}",
     *     summary="Delete professor",
     *     tags={"Professors"},
     *     @OA\Parameter(
     *         name="id", in="path", required=true, @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Professor deleted"),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function destroy(int $id): JsonResponse
    {
        try{
            $this->processor->delete($id);
            return response()->json([
                'message' => 'Professor deleted successfully',
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'Professor not found',
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch (\Throwable $e){
            return response()->json([
                'message' => 'Error deleting professor',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update an existing professor.
     *
     * @OA\Put(
     *     path="/admin/professors/{id}",
     *     summary="Update professor",
     *     tags={"Professors"},
     *     @OA\Parameter(
     *         name="id", in="path", required=true, @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *     ),
     *     @OA\Response(response=200, description="Professor updated"),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function update(UpdateProfessorRequest $request, int $id): JsonResponse {
        try{
            $user = $this->processor->update($id, $request->validated());
        }catch (\Throwable $e){
            return response()->json([
                'message' => 'Error updating professor',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        return response()->json([
            'message' => 'Professor updated successfully',
            'user'    => $user,
        ], Response::HTTP_OK);

    }

}
