<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->onDelete('cascade');
            $table->string('itemable_type'); // Polymorphic: CoinPackage, etc.
            $table->unsignedBigInteger('itemable_id');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Snapshot of price at add time
            $table->timestamps();
            
            $table->index('cart_id');
            $table->index(['itemable_type', 'itemable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
