<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapter_unlocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('chapter_id')->constrained()->onDelete('cascade');
            $table->timestamp('unlocked_at');
            $table->integer('cost_paid')->default(0); // Ki coins spent (0 if free)
            $table->timestamps();
            
            $table->unique(['user_id', 'chapter_id']);
            $table->index('user_id');
            $table->index('chapter_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapter_unlocks');
    }
};
