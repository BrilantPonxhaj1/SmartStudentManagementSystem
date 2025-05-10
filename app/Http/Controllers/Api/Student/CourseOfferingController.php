<?php
namespace App\Http\Controllers\Api\Student;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseOfferingResource;
use App\Processors\AdminProcessors\CourseOfferingProcessor;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
}
