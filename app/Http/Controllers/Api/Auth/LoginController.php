<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Api\Auth\AuthenticateRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use OpenApi\Attributes as OA;

class LoginController extends BaseAuthController
{
    #[OA\Post(
        path: '/api/auth/login',
        operationId: 'apiAuthLogin',
        description: 'This request should be used for user authentication',
        summary: 'User Authentication',
        requestBody: new OA\RequestBody(
            description: 'Body Data',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/AuthenticateRequest', required: ['email', 'password']),
        ),
        tags: ['AUTH'],
        parameters: [],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful Operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'access_token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9'),
                        new OA\Property(property: 'token_type', type: 'string', example: 'bearer'),
                        new OA\Property(property: 'expires_in', type: 'integer', example: 3600),
                    ]
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Unprocessable Content',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthorized'),
                    ],
                ),
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthorized'),
                    ],
                ),
            )
        ]
    )]
    public function __invoke(AuthenticateRequest $request): JsonResponse
    {
        $credentials = $request->all();

        if (! $token = auth()->attempt($credentials)) {
            return new JsonResponse([
                'status' => false,
                'message' => 'Unauthorized',
            ], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }
}
