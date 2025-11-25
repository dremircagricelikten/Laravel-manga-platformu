<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->boolean('is_vip')->default(false)->after('password');
            $table->timestamp('vip_expires_at')->nullable()->after('is_vip');
            $table->timestamp('last_login_at')->nullable()->after('vip_expires_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'is_vip', 'vip_expires_at', 'last_login_at']);
        });
    }
};
