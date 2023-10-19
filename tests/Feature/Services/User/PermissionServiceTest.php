<?php

declare(strict_types=1);

namespace Tests\Feature\Services\User;

use App\Models\Permission;
use App\Services\User\PermissionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

class PermissionServiceTest extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    private PermissionService $serviceUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serviceUnderTest = app()->make(PermissionService::class);
    }

    /** @test */
    public function it_should_give_permissions_by_slugs_for_user_given_by_id(): void
    {
        $slugs = Permission::factory(5)
            ->create()
            ->pluck('slug')
            ->toArray();

        $this->assertTrue($this->user->permissions->isEmpty());

        $this->serviceUnderTest->givePermissionsTo(
            userId: $this->user->id,
            slugs: $slugs,
        );

        $this->refreshUser();

        $this->assertFalse($this->user->permissions->isEmpty());
        $this->assertCount(5, $this->user->permissions);
        foreach ($this->user->permissions as $userPermission) {
            $this->assertContains($userPermission->slug, $slugs);
        }
    }

    /** @test */
    public function it_should_withdraw_permissions_by_slugs_for_user_given_by_id(): void
    {
        $permissions = Permission::factory(5)
            ->create();

        $this->user->permissions()->sync($permissions->pluck('id')->toArray());

        $this->assertFalse($this->user->permissions->isEmpty());
        $this->assertCount(5, $this->user->permissions);

        $this->serviceUnderTest->withdrawPermissionsTo(
            userId: $this->user->id,
            slugs: $permissions->pluck('slug')->toArray(),
        );

        $this->assertFalse($this->user->permissions->isEmpty());
        $this->assertCount(5, $this->user->permissions);
    }

    /** @test */
    public function it_should_refresh_permissions_by_slugs_for_user_given_by_id(): void
    {
        $initialPermissions = Permission::factory(5)
            ->create();
        $initialPermissionsSlugs = $initialPermissions->pluck('slug')->toArray();
        $newPermissions = Permission::factory(3)
            ->create();
        $newPermissionsSlugs = $newPermissions->pluck('slug')->toArray();

        $this->user->permissions()->sync($initialPermissions->pluck('id')->toArray());

        $this->assertFalse($this->user->permissions->isEmpty());
        $this->assertCount(5, $this->user->permissions);

        $this->serviceUnderTest->refreshPermissions(
            userId: $this->user->id,
            slugs: $newPermissions->pluck('slug')->toArray(),
        );

        $this->refreshUser();

        $this->assertFalse($this->user->permissions->isEmpty());
        $this->assertCount(3, $this->user->permissions);

        foreach ($this->user->permissions as $userPermission) {
            $this->assertNotContains($userPermission->slug, $initialPermissionsSlugs);
        }

        foreach ($this->user->permissions as $userPermission) {
            $this->assertContains($userPermission->slug, $newPermissionsSlugs);
        }
    }
}
