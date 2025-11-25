<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained()->onDelete('cascade');
            $table->foreignId('volume_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('chapter_number', 8, 2);
            $table->string('title')->nullable();
            $table->string('slug');
            
            // Content fields (type-specific)
            $table->json('images')->nullable(); // For manga: array of image paths
            $table->longText('content')->nullable(); // For novels: rich text
            $table->text('video_embed')->nullable(); // For anime: embed code or path
            
            // Access control
            $table->boolean('is_premium')->default(false);
            $table->integer('unlock_cost')->default(0); // Ki coins required
            $table->integer('lock_duration_days')->default(3);
            $table->timestamp('free_at')->nullable(); // Auto-calculated
            
            // Publishing
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);
            
            // Stats
            $table->unsignedBigInteger('views_count')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['series_id', 'chapter_number']);
            $table->index('slug');
            $table->index('published_at');
            $table->index('is_published');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
