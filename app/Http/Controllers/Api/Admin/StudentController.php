<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentsRequests;
use App\Http\Requests\UpdateStudentsRequests;
use App\Processors\AdminProcessors\StudentProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class StudentController extends Controller
{
    public function __construct(
        protected StudentProcessor $processor
    ){}
    /**
     * Create a new student.
     *
     * @OA\Post(
     *     path="/admin/students",
     *     summary="Create student",
     *     tags={"Students"},
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
     *         description="Student created",
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
    public function store(StoreStudentsRequests $request): JsonResponse
    {
        try{
            $user = $this->processor->create($request->validated());
        }catch (Throwable $exception){
            return response()->json([
                'message' => 'Error creating student',
                'error'   => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Student created successfully',
            'user'    => $user,
        ], Response::HTTP_CREATED);

    }
    /**
     * List all students.
     *
     * @OA\Get(
     *     path="/admin/students",
     *     summary="List students",
     *     tags={"Students"},
     *     @OA\Response(
     *         response=200,
     *         description="An array of students",
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
     * Delete a student.
     *
     * @OA\Delete(
     *     path="/admin/students/{id}",
     *     summary="Delete student",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id", in="path", required=true, @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Student deleted"),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try{
            $this->processor->delete($id);
            return response()->json([
                'message' => 'Student deleted successfully',
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'Student not found',
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch (Throwable $e){
            return response()->json([
                'message' => 'Error deleting student',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Update an existing student.
     *
     * @OA\Put(
     *     path="/admin/students/{id}",
     *     summary="Update student",
     *     tags={"Students"},
     *     @OA\Parameter(
     *         name="id", in="path", required=true, @OA\Schema(type="integer")
     *     ),
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
     *     @OA\Response(response=200, description="Student updated"),
     *     @OA\Response(response=404, description="Not found"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */

    public function update(UpdateStudentsRequests $request, int $id): JsonResponse {
        try{
            $user = $this->processor->update($id, $request->validated());
        }catch (Throwable $e){
            return response()->json([
                'message' => 'Error updating student',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        return response()->json([
            'message' => 'Student updated successfully',
            'user'    => $user,
        ], Response::HTTP_OK);

    }



}
