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
                column: 'bmi',
                callback: function (Blueprint $table): void {
                    $table->unsignedDouble('height');
                    $table->string('height_unit', 5);
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
            $table->removeColumn(['height', 'height_unit']);
        });
    }
};
