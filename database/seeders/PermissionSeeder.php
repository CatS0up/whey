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
            // Exercise -  start
            'create exercises',
            'edit exercises',
            'delete exercises',
            'verify exercises',
            // Exercise - end

            // Muscle - start
            'create muscles',
            'edit muscles',
            'delete muscles',
            'verify muscles',
            // Muscle - end

            // Permission - start
            'give permissions',
            'withdraw permissions',
            'reset permissions',
            // Permission - end

            // Role - start
            'give roles',
            'withdraw roles',
            'reset roles',
            // Role - end

            // Media - start
            'upload ckeditor images',
            // Media - end
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
