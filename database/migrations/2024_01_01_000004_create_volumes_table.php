<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained()->onDelete('cascade');
            $table->integer('volume_number');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->timestamps();
            
            $table->index(['series_id', 'volume_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volumes');
    }
};
