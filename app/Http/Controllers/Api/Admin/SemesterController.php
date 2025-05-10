<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Requests\StoreSemesterRequest;
use App\Http\Requests\UpdateSemesterRequest;
use App\Http\Resources\SemesterResource;
use App\Processors\SemesterProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class SemesterController extends BaseAdminController
{

    public function store(StoreSemesterRequest $request): JsonResponse
    {
        try{
            $data = $request->validated();
            $semester = $this->processor->create($data);
            return response()->json([
                'data' => new SemesterResource($semester)
            ], Response::HTTP_CREATED);
        }catch (Exception $exception) {
            return response()->json([
                'message' => 'Error creating semester',
                'error' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateSemesterRequest $request, int $id): JsonResponse
    {
        try{
            $data = $request->validated();
            $newSemester = $this->processor->update($id,$data);
            return response()->json([
                'data' => new SemesterResource($newSemester)
            ], Response::HTTP_OK);
        }catch (Exception $exception) {
            return response()->json([
                'message' => 'Error updating semester',
                'error' => $exception->getMessage()
            ]);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try{
            $this->processor->delete($id);
            return response()->json([
               'message' => 'Semester deleted successfully'
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $exception) {
            return response()->json([
                'message'=>'Semester not found',
                "error" => $exception->getMessage()]
                , Response::HTTP_NOT_FOUND);
        }
    }

}
