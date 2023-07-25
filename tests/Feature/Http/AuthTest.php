<?php

declare(strict_types=1);

namespace Feature\Http;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    private const LOGIN_URL = 'api/auth/login';
    private const LOGOUT_URL = 'api/auth/logout';
    private const ME_URL = 'api/auth/me';
    private const EMAIL = 'test@test.com';
    private const PASSWORD = 'Pa$$20rd';

    /** @test */
    public function it_can_login_user()
    {
        User::factory([
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ])->create();

        $response = $this->postJson(self::LOGIN_URL, [
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ]);

        $response->assertOk();
        $response->assertJsonFragment([
            'token_type' => 'bearer',
            'expires_in' => 3600
        ]);
    }

    /** @test */
    public function it_can_logout_user()
    {
        $user = User::factory([
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ])->create();

        $token = JWTAuth::fromUser($user);

        $this->post(self::LOGOUT_URL . '?token=' . $token)
            ->assertStatus(200)
            ->assertJsonStructure(['message']);

        $this->assertGuest('api');
    }

    /** @test */
    public function it_can_get_me()
    {
        $user = User::factory([
            'email' => self::EMAIL,
            'password' => self::PASSWORD,
        ])->create();

        $token = JWTAuth::fromUser($user);

        $this->get(self::ME_URL . '?token=' . $token)
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }
}
