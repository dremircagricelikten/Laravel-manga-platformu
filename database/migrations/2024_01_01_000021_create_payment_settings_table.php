<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, boolean, file
            $table->boolean('is_active')->default(true);
            $table->string('group')->default('general'); // paytr, bank_transfer, general
            
            $table->timestamps();
            
            $table->index('key');
            $table->index('group');
        });
        
        // Insert default settings
        DB::table('payment_settings')->insert([
            // PayTR Settings
            ['key' => 'paytr_merchant_id', 'value' => '', 'type' => 'text', 'group' => 'paytr', 'is_active' => false],
            ['key' => 'paytr_merchant_key', 'value' => '', 'type' => 'text', 'group' => 'paytr', 'is_active' => false],
            ['key' => 'paytr_merchant_salt', 'value' => '', 'type' => 'text', 'group' => 'paytr', 'is_active' => false],
            ['key' => 'paytr_test_mode', 'value' => '1', 'type' => 'boolean', 'group' => 'paytr', 'is_active' => true],
            
            // Bank Transfer Settings
            ['key' => 'bank_name', 'value' => '', 'type' => 'text', 'group' => 'bank_transfer', 'is_active' => true],
            ['key' => 'bank_account_holder', 'value' => '', 'type' => 'text', 'group' => 'bank_transfer', 'is_active' => true],
            ['key' => 'bank_iban', 'value' => '', 'type' => 'text', 'group' => 'bank_transfer', 'is_active' => true],
            ['key' => 'bank_branch', 'value' => '', 'type' => 'text', 'group' => 'bank_transfer', 'is_active' => true],
            ['key' => 'bank_account_number', 'value' => '', 'type' => 'text', 'group' => 'bank_transfer', 'is_active' => true],
            
            // Created timestamp
            ...array_map(fn($row) => array_merge($row, ['created_at' => now(), 'updated_at' => now()]), [])
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
