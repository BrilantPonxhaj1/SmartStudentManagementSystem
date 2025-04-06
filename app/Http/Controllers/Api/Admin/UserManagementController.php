<?php

namespace App\Http\Controllers\Api\Admin;

use App\Processors\AdminProcessors\AdminProcessor;

class UserManagementController extends BaseAdminController
{
    protected $adminProcessor;
    public function __construct(AdminProcessor $adminProcessor)
    {
        $this->adminProcessor = $adminProcessor;
    }
    /**
     * List all students.
     *
     * @OA\Get(
     *     path="/students",
     *     summary="Get a list of students",
     *     description="Retrieves a list of all students.",
     *     operationId="listStudents",
     *     tags={"Students"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="students",
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function listStudents()
    {
        $students = $this->adminProcessor->getStudents();
        return response()->json([
            'students' => $students,
        ]);
    }
}
