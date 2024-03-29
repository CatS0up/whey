<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'user',
        ]);

        Role::create([
            'name' => 'trainer',
        ]);

        Role::create([
            'name' => 'admin',
        ]);
    }
}
