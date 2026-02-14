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
     * LESSON: Polymorphic Table Structure (Branch 08)
     *
     * The morphs() helper creates two columns:
     * - commentable_id: The ID of the parent model (Task, Project, etc.)
     * - commentable_type: The class name of the parent model
     *
     * This allows Comments to belong to ANY model!
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('body');

            // LESSON: morphs() creates the polymorphic columns
            // Creates: commentable_id (bigint), commentable_type (string)
            // Also creates an index automatically!
            $table->morphs('commentable');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
