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
     * LESSON: Pivot Table (Branch 07)
     *
     * This is a "pivot" or "junction" table for the Many-to-Many
     * relationship between Tasks and Tags.
     *
     * Note: Convention is alphabetical order (tag_task), but we're
     * using task_tag to be explicit. Laravel allows custom names!
     */
    public function up(): void
    {
        Schema::create('task_tag', function (Blueprint $table) {
            $table->id();

            // Foreign keys to both related tables
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();

            $table->timestamps();

            // Prevent duplicate relationships
            $table->unique(['task_id', 'tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_tag');
    }
};
