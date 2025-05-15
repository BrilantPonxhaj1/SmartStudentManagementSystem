<?php
namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseOfferingRequest;
use App\Http\Requests\UpdateCourseOfferingRequest;
use App\Http\Resources\CourseOfferingResource;
use App\Processors\AdminProcessors\CourseOfferingProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class CourseOfferingController extends Controller
{
    public function __construct(protected CourseOfferingProcessor $processor) {}

    /**
     * GET /api/student/course-offerings?semester_id=#
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
     * GET /api/superadmin/course-offerings
     * @return JsonResponse
     */
    public function getAll(): JsonResponse {
        try {
            $items = $this->processor->list();
            return  response()->json([
                'data' => CourseOfferingResource::collection($items)
            ], Response::HTTP_OK);
        }catch (Exception $exception){
            return response()->json(['message' => 'Error fetching course offerings',
                'error' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * GET /api/superadmin/course-offerings/{id}
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse {
        try {
            $item = $this->processor->get($id);
            return  response()->json([
                'data' => new CourseOfferingResource($item)
            ], Response::HTTP_OK);

        }catch (ModelNotFoundException $e) {
            return response()->json([
               'message' => 'Course offering not found',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Creates a new course offering
     * @param StoreCourseOfferingRequest $request
     * @return JsonResponse
     */
    public function store(StoreCourseOfferingRequest $request): JsonResponse
    {
        try{
            $data = $request->validated();
            $course = $this->processor->create($data);
            return   response()->json([
                'data' => new CourseOfferingResource($course)
            ], Response::HTTP_CREATED);
        }catch (Exception $e){
            return  response()->json([
                'message' => 'Error creating course offering',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Updates a course offering based on its id
     * @param int $id
     * @param UpdateCourseOfferingRequest $request
     * @return JsonResponse
     */

    public function update(int $id, UpdateCourseOfferingRequest $request): JsonResponse
    {
        try{
            $data = $request->validated();
            $course = $this->processor->update( $id, $data);
            return   response()->json([
               'data' => new CourseOfferingResource($course)
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'Course offering not found',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Deletes a course offering based on its id
     * @param int $id
     * @return JsonResponse
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
     * Fetches course-offerings of that perticular professor
     * @param int $professorId
     * @return JsonResponse
     */

    public function coursesOfProfessor(int $professorId): JsonResponse
    {
        try{
            $courses = $this->processor->getCoursesOfProfessor($professorId);
            return response()->json([
                'data' => CourseOfferingResource::collection($courses)
            ], Response::HTTP_OK);
        }catch (Exception $e) {
            return response()->json([
                'message' => 'Error fetching courses of professor',
                'error' => $e->getMessage()
            ]);
        }
    }


}
