<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * LESSON: Pivot Table with Extra Columns (Branch 07)
     *
     * This pivot table has extra data beyond just the foreign keys.
     * We track WHEN the badge was earned and any notes.
     */
    public function up(): void
    {
        Schema::create('badge_user', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->foreignId('badge_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Extra pivot data!
            $table->timestamp('earned_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Prevent duplicate badges per user
            $table->unique(['badge_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge_user');
    }
};
