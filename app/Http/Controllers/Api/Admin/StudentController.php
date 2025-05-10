<?php

namespace App\Http\Controllers\Api\Admin;

use App\Factories\ApiResponseFactory;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Http\Resources\StudentResource;
use App\Processors\AdminProcessors\StudentProcessor;
use Throwable;

class StudentController extends BaseAdminController
{
    protected StudentProcessor $processor;

    public function __construct(StudentProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function index()
    {
        $students = $this->processor->list();
        return ApiResponseFactory::success(
            StudentResource::collection($students)
        );
    }

    /**
     * @throws Throwable
     */
    public function store(StoreStudentRequest $request)
    {
        $student = $this->processor->create($request->validated());
        return ApiResponseFactory::success(
            new StudentResource($student), 201
        );
    }

    public function show($id)
    {
        $student = $this->processor->get($id);
        return ApiResponseFactory::success(
            new StudentResource($student)
        );
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateStudentRequest $request, $id)
    {
        $student = $this->processor->update($id, $request->validated());
        return ApiResponseFactory::success(
            new StudentResource($student)
        );
    }

    public function destroy($id)
    {
        $this->processor->delete($id);
        return ApiResponseFactory::success(
            ['message' => 'Successfully deleted'], 200
        );
    }
}
