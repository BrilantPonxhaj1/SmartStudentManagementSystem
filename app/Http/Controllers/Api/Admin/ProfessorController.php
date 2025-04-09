<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfessorRequest;
use App\Processors\AdminProcessors\ProfessorProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

use App\Http\Requests\StoreProfessorRequest;



class ProfessorController extends Controller
{
    public function __construct(
        protected ProfessorProcessor $processor
    ){}
    public function store(StoreProfessorRequest $request): JsonResponse
    {
        try{
            $user = $this->processor->create($request->validated());
        }catch (\Throwable $exception){
            return response()->json([
                'message' => 'Error creating professor',
                'error'   => $exception->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'message' => 'Professor created successfully',
            'user'    => $user,
        ], Response::HTTP_CREATED);

    }

    public function index(): JsonResponse
    {
        $list = $this->processor->list();
        return response()->json([
            'data' => $list,
        ], Response::HTTP_OK);

    }

    public function destroy(int $id): JsonResponse
    {
        try{
            $this->processor->delete($id);
            return response()->json([
                'message' => 'Professor deleted successfully',
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e){
            return response()->json([
                'message' => 'Professor not found',
                'error' => $e->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }catch (\Throwable $e){
            return response()->json([
                'message' => 'Error deleting professor',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(UpdateProfessorRequest $request, int $id): JsonResponse {
        try{
            $user = $this->processor->update($id, $request->validated());
        }catch (\Throwable $e){
            return response()->json([
                'message' => 'Error updating professor',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }


        return response()->json([
            'message' => 'Professor updated successfully',
            'user'    => $user,
        ], Response::HTTP_OK);

    }

}
