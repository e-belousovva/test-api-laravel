<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserResource')]
class UserResource extends JsonResource
{
    #[OA\Property(
        property: 'id',
        title: 'id',
        description: 'id',
        type: 'integer',
        default: 1,
    )]
    #[OA\Property(
        property: 'email',
        title: 'email',
        description: 'email',
        type: 'string',
        default: 'misha@test.com',
    )]
    #[OA\Property(
        property: 'name',
        title: 'name',
        description: 'name',
        type: 'string',
        default: 'Misha',
    )]
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
        ];
    }
}
