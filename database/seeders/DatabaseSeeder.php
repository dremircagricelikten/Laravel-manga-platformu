<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            CoinPackageSeeder::class,
            SeriesSeeder::class,
            ChapterSeeder::class,
            MenuItemSeeder::class,
        ]);
    }
}
