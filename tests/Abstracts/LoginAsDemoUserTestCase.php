<?php

declare(strict_types=1);

namespace Tests\Abstracts;

use Database\Seeders\DemoUserSeeder;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

abstract class LoginAsDemoUserTestCase extends TestCase
{
    protected function runPermissionSeeders(): void
    {
        $this->seed([
            RoleSeeder::class,
            PermissionSeeder::class,
            PermissionRoleSeeder::class,
            DemoUserSeeder::class,
        ]);
    }

    protected function enableDemoUsersInConfig(): void
    {
        $this->toogleDemoUsersInConfig(true);
    }

    protected function disableDemoUsersInConfig(): void
    {
        $this->toogleDemoUsersInConfig(false);
    }

    private function toogleDemoUsersInConfig(bool $on): void
    {
        Config::set('auth.demo_users_enable', $on);
    }
}
