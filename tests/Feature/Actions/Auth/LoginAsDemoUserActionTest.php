<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\LoginAsDemoUserAction;
use App\Enums\Role;
use App\Models\User;
use Database\Seeders\DemoUserSeeder;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use RuntimeException;
use Tests\TestCase;

class LoginAsDemoUserActionTest extends TestCase
{
    use RefreshDatabase;

    private LoginAsDemoUserAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(LoginAsDemoUserAction::class);
    }

    /** @test */
    public function it_should_throw_a_RuntimeException_when_a_user_tries_to_log_in_as_one_from_the_demo_users_but_demo_users_are_disabled_in_the_config(): void
    {
        Config::set('auth.demo_users_enable', false);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Demo users are disabled');

        $this->actionUnderTest->execute(Role::User);
    }

    /**
     * @test
     * @dataProvider usersProvider
     * */
    public function user_can_log_in_as_one_from_the_demo_users_when_users_are_enabled_in_the_config(Role $role, string $userName): void
    {
        Config::set('auth.demo_users_enable', true);
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            DemoUserSeeder::class,
        ]);

        $isLoggedIn = $this->actionUnderTest->execute($role);

        $this->assertTrue($isLoggedIn);
        $this->assertEquals($userName, auth()->user()->name);
        $this->assertAuthenticatedAs(User::firstWhere('name', $userName));
    }

    public static function usersProvider(): array
    {
        return [
            // Grams - start
            'log in as user' => [
                Role::User,
                'user',
            ],
            'log in as trainer' => [
                Role::Trainer,
                'trainer',
            ],
            'log in as admin' => [
                Role::Admin,
                'admin',
            ],
        ];
    }
}
