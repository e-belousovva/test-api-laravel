<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'AuthenticateRequest')]
class AuthenticateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[OA\Property(
        property: 'email',
        title: 'email',
        description: 'email',
        type: 'string',
        default: 'misha@test.com',
    )]
    #[OA\Property(
        property: 'password',
        title: 'password',
        description: 'password',
        type: 'string',
        default: 'password',
    )]
    public function rules(): array
    {
        return [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ];
    }
}
