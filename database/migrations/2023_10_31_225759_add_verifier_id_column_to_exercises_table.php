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
        Schema::table('exercises', function (Blueprint $table): void {
            $table->foreignIdFor(User::class, 'verifier_id')->nullable()->after('author_id')->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table): void {
            $table->dropForeignIdFor(User::class, 'verifier_id');
            $table->dropColumn('verifier_id');
        });
    }
};
