<?php

namespace App\Http\Controllers\Api\Professor;

use App\Factories\ApiResponseFactory;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfessorResource;
use App\Processors\AdminProcessors\ProfessorProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;
class ProfessorController extends Controller
{
    protected ProfessorProcessor $processor;

    public function __construct(ProfessorProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * GET /admin/professors/users/{userid}
     */
    public function getProfessorFromUser(int $user_id): JsonResponse
    {
        try{
            $prof = $this->processor->getProfessorFromUser($user_id);
            return ApiResponseFactory::success([
                'data' => new ProfessorResource($prof)
            ], Response::HTTP_OK);
        }catch (ModelNotFoundException $e){
            return ApiResponseFactory::error('Professor not found', Response::HTTP_NOT_FOUND);
        }catch (Exception $e){
            return ApiResponseFactory::error('Error fetching professor', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
