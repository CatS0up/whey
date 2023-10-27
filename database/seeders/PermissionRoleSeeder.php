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
            ->findOrFail(RoleType::User->id())
            ->permissions()
            ->sync(
                Permission::query()
                    ->whereSlugIn([
                        'create-exercises',
                        'edit-exercises',
                        'delete-exercises',
                    ])
                    ->pluck('id'),
            );

        Role::query()
            ->findOrFail(RoleType::User->id())
            ->permissions()
            ->sync(
                Permission::query()
                    ->whereSlugIn([
                        'create-exercises',
                        'edit-exercises',
                        'delete-exercises',
                        'verify-exercises',
                        'add-muscles',
                        'edit-muscles',
                        'delete-muscles',
                        'verify-muscles',
                    ])
                    ->pluck('id'),
            );

        Role::query()
            ->findOrFail(RoleType::User->id())
            ->permissions()
            ->sync(
                Permission::query()
                    ->whereSlugIn([
                        'create-exercises',
                        'edit-exercises',
                        'delete-exercises',
                        'verify-exercises',
                        'create-muscles',
                        'edit-muscles',
                        'delete-muscles',
                        'verify-muscles',
                        'give-permissions',
                        'withdraw-permissions',
                        'reset-permissions',
                        'give-roles',
                        'withdraw-roles',
                        'reset-roles',
                    ])
                    ->pluck('id'),
            );
    }
}
