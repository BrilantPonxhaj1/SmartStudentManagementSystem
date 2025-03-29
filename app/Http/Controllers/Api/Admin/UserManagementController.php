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
    public function listStudents()
    {
        $students = $this->adminProcessor->getStudents();
        return response()->json([
            'students' => $students,
        ]);
    }
}
