<?php
namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseOfferingRequest;
use App\Http\Requests\UpdateCourseOfferingRequest;
use App\Http\Resources\CourseOfferingResource;
use App\Http\Resources\Admin\CourseOfferingResource as AdminCourseOfferingResource;
use App\Processors\AdminProcessors\CourseOfferingProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
/**
 * @OA\Tag(
 *   name="CourseOfferings",
 *   description="Endpoints to manage course offerings for students, admins, and professors"
 * )
 */
class CourseOfferingController extends Controller
{
    public function __construct(protected CourseOfferingProcessor $processor) {}

    /**
     * @OA\Get(
     *     path="/api/student/course_offerings",
     *     operationId="listCourseOfferingsBySemester",
     *     tags={"CourseOfferings"},
     *     summary="List available course offerings for a given semester",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="semester_id",
     *         in="query",
     *         description="Semester ID to filter offerings",
     *         required=true,
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A collection of course offerings",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CourseOffering")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=422, description="semester_id is required"),
     *     @OA\Response(response=500, description="Internal server error")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $semesterId = $request->query('semester_id');

        if (! $semesterId) {
            return response()->json([
                'message' => 'semester_id is required'
            ], 422);
        }

        $offerings = $this->processor->listBySemester((int)$semesterId);
        return response()->json([
            'data' => CourseOfferingResource::collection($offerings)
        ]);
    }
    /**
     * @OA\Get(
     *     path="/api/superadmin/course-offerings",
     *     operationId="listAllCourseOfferings",
     *     tags={"CourseOfferings"},
     *     summary="List all course offerings (admin view)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="A collection of all course offerings",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CourseOffering")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error fetching course offerings")
     * )
     */
    public function getAll(): JsonResponse {
        try {
            $items = $this->processor->list();
            return  response()->json([
                'data' => AdminCourseOfferingResource::collection($items)
            ], Response::HTTP_OK);
        }catch (Exception $exception){
            return response()->json(['message' => 'Error fetching course offerings',
                'error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/superadmin/course-offerings/{id}",
     *     operationId="getCourseOfferingById",
     *     tags={"CourseOfferings"},
     *     summary="Get a single course offering by ID (admin view)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Course offering ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course offering details",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/CourseOffering")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Course offering not found"),
     *     @OA\Response(response=500, description="Error retrieving course offering")
     * )
     */
    public function show(int $id): JsonResponse {
        try {
            $item = $this->processor->get($id);
            return  response()->json([
                'data' => new AdminCourseOfferingResource($item)
            ], Response::HTTP_OK);

        }catch (ModelNotFoundException $e) {
            return response()->json([
               'message' => 'Course offering not found',
                'error' => $e->getMessage()
            ]);
        }
    }
    /**
     * @OA\Post(
     *     path="/api/superadmin/course-offerings",
     *     operationId="createCourseOffering",
     *     tags={"CourseOfferings"},
     *     summary="Create a new course offering (admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreCourseOfferingRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Course offering created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/CourseOffering")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validation error"),
     *     @OA\Response(response=500, description="Error creating course offering")
     * )
     */
    public function store(StoreCourseOfferingRequest $request): JsonResponse
    {
        try{
            $data = $request->validated();
            $course = $this->processor->create($data);
            return   response()->json([
                'data' => new AdminCourseOfferingResource($course)
            ], Response::HTTP_CREATED);
        }catch (Exception $e){
            return  response()->json([
                'message' => 'Error creating course offering',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/superadmin/course-offerings/{id}",
     *     operationId="updateCourseOffering",
     *     tags={"CourseOfferings"},
     *     summary="Update an existing course offering (admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Course offering ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateCourseOfferingRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Course offering updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="#/components/schemas/CourseOffering")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Course offering not found"),
     *     @OA\Response(response=500, description="Error updating course offering")
     * )
     */

    public function update(int $id, UpdateCourseOfferingRequest $request): JsonResponse
    {
        try{
            $data = $request->validated();
            $course = $this->processor->update( $id, $data);
            return   response()->json([
               'data' => new AdminCourseOfferingResource($course)
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'Course offering not found',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/superadmin/course-offerings/{id}",
     *     operationId="deleteCourseOffering",
     *     tags={"CourseOfferings"},
     *     summary="Delete a course offering (admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Course offering ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(response=200, description="Course offering deleted successfully"),
     *     @OA\Response(response=404, description="Course offering not found")
     * )
     */
    public function destroy(int $id): JsonResponse {
        try {
            $this->processor->delete($id);
            return response()->json([
                'message' => 'Course offering deleted successfully'
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Course offering not found',
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_FOUND);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/professor/course-offerings/{professorId}",
     *     operationId="getCoursesOfProfessor",
     *     tags={"CourseOfferings"},
     *     summary="List course offerings for a specific professor",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="professorId",
     *         in="path",
     *         description="Professor ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Courses of professor",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(ref="#/components/schemas/CourseOffering")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Error fetching courses of professor")
     * )
     */
    public function coursesOfProfessor(int $professorId): JsonResponse
    {
        try{
            $courses = $this->processor->getCoursesOfProfessor($professorId);
            return response()->json([
                'data' => AdminCourseOfferingResource::collection($courses)
            ], Response::HTTP_OK);
        }catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching courses of professor',
                'error' => $e->getMessage()
            ]);
        }
    }
}
