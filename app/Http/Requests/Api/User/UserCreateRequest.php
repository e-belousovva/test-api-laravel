<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User;

use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(schema: 'UserCreateRequest')]
class UserCreateRequest extends FormRequest
{
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
        default: '12345%Df678',
    )]
    #[OA\Property(
        property: 'name',
        title: 'name',
        description: 'name',
        type: 'string',
        default: 'Misha',
    )]
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|min:1|max:60|unique:users,email',
            'password' => ['required', 'min:8', 'max:50', new PasswordRule()],
            'name' => 'nullable|string|max:60',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Пользователь с указанным Email  существует!',
        ];
    }
}
