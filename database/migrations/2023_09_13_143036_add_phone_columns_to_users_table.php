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
            $table->after(
                column: 'email',
                callback: function (Blueprint $table): void {
                    $table->string('phone');
                    $table->string('phone_normalized');
                    $table->string('phone_national');
                    $table->string('phone_e164');
                    $table->string('phone_country');
                },
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->removeColumn([
                'phone',
                'phone_normalized',
                'phone_national',
                'phone_e164',
                'phone_country',
            ]);
        });
    }
};
