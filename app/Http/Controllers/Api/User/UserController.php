<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserCreateRequest;
use App\Http\Requests\Api\User\UserUpdateRequest;
use App\Http\Resources\Api\User\UserCollection;
use App\Http\Resources\Api\User\UserResource;
use App\Interfaces\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    )
    {
    }

    #[OA\Get(
        path: '/api/users',
        operationId: 'apiUsersGet',
        description: 'This query should be used to get all users',
        summary: 'Get All Users',
        security: [['bearerAuth' => []]],
        tags: ['USERS'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful Operation',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/UserResource')),
                        new OA\Property(property: 'links', type: 'object', example: []),
                        new OA\Property(property: 'meta', type: 'object', example: []),
                    ],
                ),
            ),
            new OA\Response(
                response: 500,
                description: 'Error Response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Error Message'),
                    ],
                ),
            )
        ]
    )]
    public function index(): UserCollection|JsonResponse
    {
        try {
            $users = $this->userRepository->getAllUsers();

            return new UserCollection($users);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage(),
            ], $exception->getCode() ?? ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    #[OA\Post(
        path: '/api/users',
        operationId: 'apiUserPost',
        description: 'This query should be used to create user',
        summary: 'Create User',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: 'Body Data',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UserCreateRequest', required: ['name', 'email', 'password']),
        ),
        tags: ['USERS'],
        parameters: [],
        responses: [
            new OA\Response(
                response: 201,
                description: 'Successful Created',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', ref: '#/components/schemas/UserResource'),
                    ],
                ),
            ),
            new OA\Response(
                response: 500,
                description: 'Error Response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Error Message'),
                    ],
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Unprocessable Content',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Unprocessable Content'),
                    ],
                ),
            )
        ]
    )]
    public function store(UserCreateRequest $request): JsonResponse|UserResource
    {
        try {
            $data = $request->validated();
            $user = $this->userRepository->createUser($data);

            return new UserResource($user);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage(),
            ], $exception->getCode() ?? ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Get(
        path: '/api/users/{userID}',
        operationId: 'apiUserGet',
        description: 'This query should be used to get user',
        summary: 'Get User',
        security: [['bearerAuth' => []]],
        tags: ['USERS'],
        parameters: [
            new OA\Parameter(
                name: 'userID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
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
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\User] 3'),
                    ],
                ),
            )
        ]
    )]
    public function show(User $user): JsonResponse|UserResource
    {
        return new UserResource($user);
    }

    #[OA\Put(
        path: '/api/users/{userID}',
        operationId: 'apiUserPut',
        description: 'This query should be used to update user',
        summary: 'Update User',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: 'Body Data',
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UserUpdateRequest', required: ['login', 'email', 'password']),
        ),
        tags: ['USERS'],
        parameters: [
            new OA\Parameter(
                name: 'userID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
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
                response: 500,
                description: 'Error Response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Error Message'),
                    ],
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Unprocessable Content',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Unprocessable Content'),
                    ],
                ),
            ),
            new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\User] 3'),
                    ],
                ),
            )
        ]
    )]
    public function update(UserUpdateRequest $request, User $user): JsonResponse|UserResource
    {
        try {
            $data = $request->validated();
            $user = $this->userRepository->updateUser($user, $data);

            return new UserResource($user);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage(),
            ], $exception->getCode() ?? ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[OA\Delete(
        path: '/api/users/{userID}',
        operationId: 'apiUserDelete',
        description: 'This query should be used to delete user',
        summary: 'Delete User',
        security: [['bearerAuth' => []]],
        tags: ['USERS'],
        parameters: [
            new OA\Parameter(
                name: 'userID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
        ],
        responses: [
            new OA\Response(
                response: '204',
                description: 'No Content',
            ),
            new OA\Response(
                response: 500,
                description: 'Error Response',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Error Message'),
                    ],
                ),
            ),
            new OA\Response(
                response: 404,
                description: 'Not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'status', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\User] 3'),
                    ],
                ),
            )
        ]
    )]
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return new JsonResponse([
                'message' => '',
            ], ResponseAlias::HTTP_NO_CONTENT);
        } catch (Throwable $exception) {
            return new JsonResponse([
                'status' => false,
                'message' => $exception->getMessage(),
            ], $exception->getCode() ?? ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
