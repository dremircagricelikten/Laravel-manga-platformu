<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['manga', 'novel', 'anime']);
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('alternative_titles')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->enum('status', ['ongoing', 'completed', 'hiatus', 'cancelled'])->default('ongoing');
            $table->string('author')->nullable();
            $table->string('artist')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_nsfw')->default(false);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index('type');
            $table->index('status');
            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
