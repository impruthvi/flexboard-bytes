<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flexes', function (Blueprint $table) {
            $table->id();

            // LESSON: Foreign Key (Branch 06)
            // This creates the relationship: Flex belongsTo User
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Optional: Link to task that triggered the flex
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();

            // The flex message
            $table->string('message');
            $table->integer('points_earned')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flexes');
    }
};
