<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\SemesterResource;
use App\Processors\SemesterProcessor;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SemesterController
{  protected SemesterProcessor $processor;
    public function __construct( SemesterProcessor $processor)
    {
        $this->processor = $processor;
    }

    public function index(): JsonResponse
    {
        try{
            $items= $this->processor->list();
            return response()->json([
                'data' => SemesterResource::collection($items)
            ], Response::HTTP_OK);
        }catch (Exception $exception) {
            return response()->json([
                'message' => 'Error fetching semesters',
                'error' => $exception->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function show(int $id): JsonResponse
    {
        try {
            $sem = $this->processor->get($id);
            return response()->json([
                'data' => new SemesterResource($sem)
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $exception) {
            return response()->json(['message'=>'Semester not found',
                "error" => $exception->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

}
