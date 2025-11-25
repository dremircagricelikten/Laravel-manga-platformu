<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, boolean, number, json
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            ['key' => 'site_name', 'value' => 'Manga Diyarı', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'site_description', 'value' => 'En iyi manga okuma deneyimi', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'facebook_url', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'twitter_url', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'discord_url', 'value' => '', 'type' => 'text', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
