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
     * LESSON: Polymorphic Reactions (Branch 08)
     *
     * Reactions can be added to Tasks, Projects, Comments, etc.
     * Uses the same polymorphic pattern as Comments.
     *
     * Example reactions: 🔥, 💯, 🙌, ❤️, 🚀, 👀
     */
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('emoji'); // The reaction emoji

            // LESSON: morphs() for polymorphic relationship
            // Creates: reactionable_id (bigint), reactionable_type (string)
            $table->morphs('reactionable');

            $table->timestamps();

            // Prevent duplicate reactions from same user on same item
            $table->unique(['user_id', 'reactionable_id', 'reactionable_type', 'emoji']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
