<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('coin_package_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            
            $table->timestamps();
            
            // A user can have same package only once in cart
            $table->unique(['user_id', 'coin_package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
