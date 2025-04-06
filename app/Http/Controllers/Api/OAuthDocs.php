<?php

namespace App\Http\Controllers\Api;

/**
 * @OA\Post(
 *     path="/oauth/token",
 *     summary="Generate OAuth token",
 *     description="Generates an access token using user credentials.",
 *     operationId="issueToken",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         required=true,
 *         description="User credentials",
 *         @OA\JsonContent(
 *             required={"grant_type", "client_id", "client_secret", "username", "password"},
 *             @OA\Property(property="grant_type", type="string", example="password"),
 *             @OA\Property(property="client_id", type="string", example="3"),
 *             @OA\Property(property="client_secret", type="string", example="your_client_secret"),
 *             @OA\Property(property="username", type="string", example="user@example.com"),
 *             @OA\Property(property="password", type="string", example="secret")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful token generation",
 *         @OA\JsonContent(
 *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOi..."),
 *             @OA\Property(property="refresh_token", type="string", example="def50200..."),
 *             @OA\Property(property="expires_in", type="integer", example=3600),
 *             @OA\Property(property="token_type", type="string", example="Bearer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized. Invalid credentials.",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="invalid_credentials"),
 *             @OA\Property(property="error_description", type="string", example="The user credentials were incorrect.")
 *         )
 *     )
 * )
 */
class OAuthDocs
{

}
