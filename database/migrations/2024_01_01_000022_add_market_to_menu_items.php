<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if item already exists to avoid duplicates
        $exists = DB::table('menu_items')->where('route', 'market')->exists();

        if (!$exists) {
            DB::table('menu_items')->insert([
                'title' => 'Market',
                'route' => 'market',
                'sort_order' => 4, // Adjust based on existing items, assuming popular etc are 1,2,3
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('menu_items')->where('route', 'market')->delete();
    }
};
