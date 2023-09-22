<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->unique(['phone']);
            $table->unique(['phone_normalized']);
            $table->unique(['phone_national']);
            $table->unique(['phone_e164']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table): void {
            $table->dropIndex([
                'users_phone_unique',
                'users_phone_normalized_unique',
                'users_phone_national_unique',
                'users_phone_e164_unique',
            ]);
        });
    }
};
