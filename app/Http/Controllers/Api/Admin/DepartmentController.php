<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Processors\AdminProcessors\DepartmentProcessor;
use Exception;
use Illuminate\Http\JsonResponse;

class DepartmentController extends BaseAdminController
{
    public function __construct(protected DepartmentProcessor $processor)
    {
    }

    public function getDeptByUniversity(int $uni):JsonResponse {
        try{
            $depts = $this->processor->listByUniversityId($uni);
            return response()->json($depts);
        }catch (Exception $exception){
            return response()->json([
                'message' => 'Error fetching departments',
                'error' => $exception->getMessage()
            ], 500);
        }

    }

}
