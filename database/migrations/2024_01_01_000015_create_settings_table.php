<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->enum('type', ['string', 'text', 'boolean', 'integer', 'json'])->default('string');
            $table->string('group')->default('general'); // e.g., 'general', 'appearance', 'social', 'seo'
            $table->boolean('is_public')->default(false); // Can be accessed in frontend
            $table->timestamps();
            
            $table->index('key');
            $table->index('group');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
