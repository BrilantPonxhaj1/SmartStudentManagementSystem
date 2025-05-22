<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Processors\AdminProcessors\StudentProcessor;
use Throwable;
/**
 * @OA\Tag(
 *   name="Students",
 *   description="CRUD operations for students"
 * )
 */
class StudentController
{
    protected StudentProcessor $processor;

    public function __construct(StudentProcessor $processor)
    {
        $this->processor = $processor;
    }
    /**
     * @OA\Get(
     *     path="/api/admin/students",
     *     operationId="listStudents",
     *     tags={"Students"},
     *     summary="Get list of all students",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A collection of students",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Student")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function index()
    {
        $students = $this->processor->list();
        return ApiResponseFactory::success(
            StudentResource::collection($students)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/admin/students",
     *     operationId="createStudent",
     *     tags={"Students"},
     *     summary="Create a new student",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreStudentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Student created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status",  type="string", example="success"),
     *             @OA\Property(property="data",    ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Server error")
     * )
     * @throws Throwable
     */
    public function store(StoreStudentRequest $request)
    {
        $student = $this->processor->create($request->validated());
        return ApiResponseFactory::success(
            new StudentResource($student), 201
        );
    }
    /**
     * @OA\Get(
     *     path="/api/admin/students/{id}",
     *     operationId="getStudentById",
     *     tags={"Students"},
     *     summary="Retrieve a single student by ID",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student details",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Student not found"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function show($id)
    {
        $student = $this->processor->get($id);
        return ApiResponseFactory::success(
            new StudentResource($student)
        );
    }

    /**
     * @OA\Put(
     *     path="/api/admin/students/{id}",
     *     operationId="updateStudent",
     *     tags={"Students"},
     *     summary="Update an existing student",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateStudentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Student updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data",   ref="#/components/schemas/Student")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=404, description="Student not found"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Server error")
     * )
     * @throws Throwable
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        $student = $this->processor->update($id, $request->validated());
        return ApiResponseFactory::success(
            new StudentResource($student)
        );
    }
    /**
     * @OA\Delete(
     *     path="/api/admin/students/{id}",
     *     operationId="deleteStudent",
     *     tags={"Students"},
     *     summary="Delete a student",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Student ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(response=200, description="Student deleted successfully"),
     *     @OA\Response(response=404, description="Student not found"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=500, description="Server error")
     * )
     */
    public function destroy($id)
    {
        $this->processor->delete($id);
        return ApiResponseFactory::success(
            ['message' => 'Successfully deleted'], 200
        );
    }
}
