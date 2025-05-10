<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Processors\AdminProcessors\UniversityProcessor;
use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UniversityController extends Controller
{
    public function __construct(protected UniversityProcessor $processor)
    {
    }

    public function index(): JsonResponse {
        try{
            $unis= $this->processor->listForSelect();
            return response()->json(
                $unis
            );
        }catch (Exception $exception){
            return response()->json([
                'message' => 'Failed to fetch universities -> ', $exception
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
