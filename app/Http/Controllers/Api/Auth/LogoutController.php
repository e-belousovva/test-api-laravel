<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use OpenApi\Attributes as OA;

class LogoutController extends BaseAuthController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    #[OA\Post(
        path: '/api/auth/logout',
        operationId: 'apiAuthLogout',
        description: 'This request should be used for user logout',
        summary: 'User Logout',
        security: [['bearerAuth' => []]],
        tags: ['AUTH'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully logged out',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Successfully logged out'),
                    ],
                ),
            ),
        ]
    )]
    public function __invoke(): JsonResponse
    {
        auth()->logout();

        return new JsonResponse([
            'status' => true,
            'message' => 'Successfully logged out',
        ], ResponseAlias::HTTP_OK);
    }
}
