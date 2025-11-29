<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relation (Series or Chapter)
            $table->morphs('commentable');
            
            // User who made the comment
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Nested replies
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade');
            
            // Content
            $table->text('content');
            $table->string('gif_url')->nullable(); // Optional GIF
            
            // Moderation
            $table->boolean('is_approved')->default(true);
            $table->boolean('is_pinned')->default(false);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['commentable_type', 'commentable_id']);
            $table->index('user_id');
            $table->index('parent_id');
            $table->index('is_approved');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
