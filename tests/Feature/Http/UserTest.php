<?php

declare(strict_types=1);

namespace Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private const USERS_URL = 'api/users';
    private const LOGIN_URL = 'api/auth/login';
    private const EMAIL = 'test@test.com';
    private const PASSWORD = 'Pa$$20rd';

    /** @test */
    public function it_can_delete_user()
    {
        $user = User::factory([
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ])->create();

        $token = JWTAuth::fromUser($user);

        $this->delete(self::USERS_URL . '/' . $user->id . '?token=' . $token)
            ->assertStatus(204)
            ->assertNoContent();
    }
}
