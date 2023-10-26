<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role as RoleType;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (config('demo_users_enable')) {
            return;
        }

        User::factory(3)
            ->state(new Sequence(
                ['name' => 'user'],
                ['name' => 'trainer'],
                ['name' => 'admin'],
            ))
            ->create();

        User::query()
            ->firstWhere('name', 'user')
            ->roles()
            ->sync(Role::find(RoleType::User->id())->value('id'));

        User::query()
            ->firstWhere('name', 'trainer')
            ->roles()
            ->sync(Role::find(RoleType::Trainer->id())->value('id'));

        User::query()
            ->firstWhere('name', 'admin')
            ->roles()
            ->sync(Role::find(RoleType::Admin->id())->value('id'));
    }
}
