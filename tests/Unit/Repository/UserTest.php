<?php

declare(strict_types=1);

namespace Unit\Repository;

use App\Interfaces\User\UserRepositoryInterface;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_all_users()
    {
        $usersFactory = User::factory(5)->create();
        $usersDb = $this->userRepository()->getAllUsers();

        $this->assertInstanceOf(Collection::class, $usersDb);
        $usersDb->each(function (User $user) use ($usersFactory) {
            $this->assertTrue($usersFactory->contains($user));
        });
    }

    /** @test */
    public function it_can_create_a_user()
    {
        $faker = Factory::create();

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $faker->password,
        ];

        $user = $this->userRepository()->createUser($data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $faker = Factory::create();
        $user = User::factory()->create();

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'password' => $faker->password,
        ];

        $user = $this->userRepository()->updateUser($user, $data);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
    }

    protected function userRepository(): UserRepositoryInterface
    {
        return app(UserRepositoryInterface::class);
    }
}
