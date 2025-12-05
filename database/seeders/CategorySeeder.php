<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Action', 'slug' => 'action', 'description' => 'Aksiyon dolu maceralar'],
            ['name' => 'Romance', 'slug' => 'romance', 'description' => 'Romantik hikayeler'],
            ['name' => 'Comedy', 'slug' => 'comedy', 'description' => 'Komik ve eğlenceli seriler'],
            ['name' => 'Fantasy', 'slug' => 'fantasy', 'description' => 'Fantastik dünyalar ve maceralar'],
            ['name' => 'Drama', 'slug' => 'drama', 'description' => 'Duygusal ve etkileyici hikayeler'],
            ['name' => 'Sci-Fi', 'slug' => 'sci-fi', 'description' => 'Bilim kurgu ve gelecek hikayeleri'],
            ['name' => 'Mystery', 'slug' => 'mystery', 'description' => 'Gizemli ve sürükleyici olaylar'],
            ['name' => 'Horror', 'slug' => 'horror', 'description' => 'Korku ve gerilim'],
            ['name' => 'Slice of Life', 'slug' => 'slice-of-life', 'description' => 'Günlük yaşam hikayeleri'],
            ['name' => 'Adventure', 'slug' => 'adventure', 'description' => 'Macera dolu yolculuklar'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
