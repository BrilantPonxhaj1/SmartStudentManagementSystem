<?php

namespace App\Http\Controllers\Api;

use App\Factories\ApiResponseFactory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * Class AuthController
 * @package App\Http\Controllers\Api
 */
class AuthController
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     tags={"Auth"},
     *     description="Verifies user credentials and returns user data upon successful login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Login Successful"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthorized. Invalid credentials.")
     *         )
     *     )
     * )
     */
    public function login(Request $request) : JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        if ($user->getEmail() !== $request->input('email')) {
            return ApiResponseFactory::error('Unauthorized. Email does not match the authenticated user.',401);
        }

        if (!Hash::check($request->input('password'), $user->getPassword())) {
            return ApiResponseFactory::error('Unauthorized. Password does not match the authenticated user.',401);
        }

        return ApiResponseFactory::success($user);
    }
}
