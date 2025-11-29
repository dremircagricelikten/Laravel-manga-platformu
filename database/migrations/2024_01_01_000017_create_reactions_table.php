<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relation (Series or Chapter)
            $table->morphs('reactionable');
            
            // User who reacted
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Reaction type: like, love, haha, wow, sad, angry
            $table->enum('type', ['like', 'love', 'haha', 'wow', 'sad', 'angry']);
            
            $table->timestamps();
            
            // A user can only have one reaction per item
            $table->unique(['reactionable_type', 'reactionable_id', 'user_id']);
            
            $table->index(['reactionable_type', 'reactionable_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};
