<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use OpenApi\Attributes as OA;
use Illuminate\Http\JsonResponse;

class RefreshController extends BaseAuthController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    #[OA\Post(
        path: '/api/auth/refresh',
        operationId: 'apiAuthRefresh',
        description: 'This request should be used for user refresh token',
        summary: 'User Refresh',
        security: [['bearerAuth' => []]],
        tags: ['AUTH'],
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
            )
        ]
    )]
    public function __invoke(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }
}
