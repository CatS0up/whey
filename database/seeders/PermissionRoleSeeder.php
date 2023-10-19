<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role as RoleType;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()
            ->findOrFail(RoleType::User->value)
            ->permissions()
            ->sync(
                Permission::query()
                    ->whereSlugIn([
                        'exercise-add',
                        'exercise-edit',
                        'exercise-delete',
                    ])
                    ->pluck('id'),
            );

        Role::query()
            ->findOrFail(RoleType::User->value)
            ->permissions()
            ->sync(
                Permission::query()
                    ->whereSlugIn([
                        'exercise-add',
                        'exercise-edit',
                        'exercise-delete',
                        'exercise-verify',
                        'add-muscle',
                        'edit-muscle',
                        'delete-muscle',
                        'verify-muscle',
                    ])
                    ->pluck('id'),
            );

        Role::query()
            ->findOrFail(RoleType::User->value)
            ->permissions()
            ->sync(
                Permission::query()
                    ->whereSlugIn([
                        'exercise-add',
                        'exercise-edit',
                        'exercise-delete',
                        'exercise-verify',
                        'add-muscle',
                        'edit-muscle',
                        'delete-muscle',
                        'verify-muscle',
                        'give-permission',
                        'withdraw-permission',
                        'reset-permission',
                        'give-role',
                        'withdraw-role',
                        'reset-role',
                    ])
                    ->pluck('id'),
            );
    }
}
