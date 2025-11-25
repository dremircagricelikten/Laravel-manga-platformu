<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['purchase', 'spend', 'refund', 'admin_adjustment']);
            $table->decimal('amount', 15, 2); // Positive or negative
            $table->decimal('balance_after', 15, 2);
            $table->string('description');
            
            // Polymorphic relation to what triggered this
            $table->string('transactionable_type')->nullable();
            $table->unsignedBigInteger('transactionable_id')->nullable();
            
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('type');
            $table->index(['transactionable_type', 'transactionable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
