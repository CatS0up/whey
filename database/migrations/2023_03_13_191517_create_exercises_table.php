<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table): void {
            $table->id();

            $table->string('name')->index();
            $table->string('difficulty_level')->index();
            $table->string('type')->index();
            $table->string('instructions_raw')->index();
            $table->text('instructions_html');
            $table->boolean('is_public')->default(true);

            $table->foreignIdFor(User::class, 'author_id')->constrained('users');

            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
