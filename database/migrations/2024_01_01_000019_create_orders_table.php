<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            
            // Pricing
            $table->decimal('total_amount', 10, 2);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('final_amount', 10, 2);
            
            // Payment
            $table->enum('payment_method', ['paytr', 'bank_transfer'])->default('paytr');
            $table->enum('payment_status', ['pending', 'processing', 'paid', 'failed', 'cancelled'])->default('pending');
            
            // PayTR specific
            $table->string('paytr_token')->nullable();
            $table->text('paytr_response')->nullable();
            
            // Bank transfer specific
            $table->string('bank_receipt')->nullable(); // Dekont/makbuz dosyası
            $table->timestamp('bank_transfer_date')->nullable();
            
            // Admin
            $table->text('admin_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('order_number');
            $table->index('payment_status');
            $table->index('payment_method');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
