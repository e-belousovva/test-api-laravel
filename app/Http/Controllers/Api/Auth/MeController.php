<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Auth;

use App\Http\Resources\Api\User\UserResource;
use OpenApi\Attributes as OA;

class MeController extends BaseAuthController
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    #[OA\Get(
        path: '/api/auth/me',
        operationId: 'apiAuthMe',
        description: 'This query should be used to get auth user',
        summary: 'Get User',
        security: [['bearerAuth' => []]],
        tags: ['AUTH'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful Operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
                    ],
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
    public function __invoke(): UserResource
    {
        return new UserResource(auth()->user());
    }
}
