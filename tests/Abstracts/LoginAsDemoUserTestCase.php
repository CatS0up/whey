<?php

declare(strict_types=1);

namespace Tests\Abstracts;

use Database\Seeders\DemoUserSeeder;
use Illuminate\Support\Facades\Config;
use Tests\Concerns\Authorization;
use Tests\TestCase;

abstract class LoginAsDemoUserTestCase extends TestCase
{
    use Authorization {
        runPermissionSeeders as traitRunPermissionSeeders;
    }

    protected function runPermissionSeeders(): void
    {
        $this->traitRunPermissionSeeders();
        $this->seed(DemoUserSeeder::class);
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
