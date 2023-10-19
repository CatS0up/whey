<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    private static function dataTable(): array
    {
        return [
            // Exercise - start
            'add-exercise',
            'edit-exercise',
            'delete-exercise',
            'verify-exercise',
            // Exercise - end

            // Muscle - start
            'add-muscle',
            'edit-muscle',
            'delete-muscle',
            'verify-muscle',
            // Muscle - end

            // Permission - start
            'give-permission',
            'withdraw-permission',
            'reset-permission',
            // Permission - end

            // Role - start
            'give-role',
            'withdraw-role',
            'reset-role',
            // Role - end
        ];
    }

    public function run(): void
    {
        $permissions = self::dataTable();

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }
    }
}
