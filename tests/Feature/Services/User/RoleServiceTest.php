<?php

declare(strict_types=1);

namespace Tests\Feature\Services\User;

use App\Models\Role;
use App\Services\User\RoleService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class RoleServiceTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private RoleService $serviceUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceUnderTest = app()->make(RoleService::class);
    }

    /** @test */
    public function it_should_give_roles_by_slugs_for_user_given_by_id(): void
    {
        $slugs = Role::factory(5)
            ->create()
            ->pluck('slug')
            ->toArray();


        $this->assertTrue($this->user->roles->isEmpty());

        $this->serviceUnderTest->giveRoles(
            userId: $this->user->id,
            slugs: $slugs,
        );

        $this->refreshUser();

        $this->assertFalse($this->user->roles->isEmpty());
        $this->assertCount(5, $this->user->roles);
        foreach ($this->user->roles as $userRole) {
            $this->assertContains($userRole->slug, $slugs);
        }
    }

    /** @test */
    public function it_should_withdraw_roles_by_slugs_for_user_given_by_id(): void
    {
        $roles = Role::factory(5)
            ->create();

        $this->user->roles()->sync($roles->pluck('id')->toArray());

        $this->assertFalse($this->user->roles->isEmpty());
        $this->assertCount(5, $this->user->roles);

        $this->serviceUnderTest->withdrawRoles(
            userId: $this->user->id,
            slugs: $roles->pluck('slug')->toArray(),
        );

        $this->assertFalse($this->user->roles->isEmpty());
        $this->assertCount(5, $this->user->roles);
    }

    /** @test */
    public function it_should_refresh_roles_by_slugs_for_user_given_by_id(): void
    {
        $initialRoles = Role::factory(5)
            ->create();
        $initialRolesSlugs = $initialRoles->pluck('slug')->toArray();
        $newRoles = Role::factory(3)
            ->create();
        $newRolesSlugs = $newRoles->pluck('slug')->toArray();

        $this->user->roles()->sync($initialRoles->pluck('id')->toArray());

        $this->assertFalse($this->user->roles->isEmpty());
        $this->assertCount(5, $this->user->roles);

        $this->serviceUnderTest->refreshRoles(
            userId: $this->user->id,
            slugs: $newRoles->pluck('slug')->toArray(),
        );

        $this->refreshUser();

        $this->assertFalse($this->user->roles->isEmpty());
        $this->assertCount(3, $this->user->roles);

        foreach ($this->user->roles as $userRole) {
            $this->assertNotContains($userRole->slug, $initialRolesSlugs);
        }

        foreach ($this->user->roles as $userRole) {
            $this->assertContains($userRole->slug, $newRolesSlugs);
        }
    }
}
