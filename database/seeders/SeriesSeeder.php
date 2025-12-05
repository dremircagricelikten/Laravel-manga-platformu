<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Series;
use App\Models\Category;
use Illuminate\Support\Str;

class SeriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seriesData = [
            [
                'type' => 'manga',
                'title' => 'Kahraman Günlükleri',
                'description' => 'Sıradan bir lise öğrencisi, bir gün ansızın güçler kazanır ve dünyayı kurtarmak için bir yolculuğa çıkar. Aksiyon dolu sahneler ve güçlü dostluklar onu bekliyor.',
                'author' => 'Kenji Yamamoto',
                'artist' => 'Yuki Tanaka',
                'status' => 'ongoing',
                'is_nsfw' => false,
                'is_featured' => true,
                'categories' => ['Action', 'Adventure', 'Fantasy'],
            ],
            [
                'type' => 'manga',
                'title' => 'Gölgelerin Prensi',
                'description' => 'Karanlık bir krallıkta yaşayan genç bir prens, babası tarafından sürgün edilir. Ancak bu sürgün, onun gerçek gücünü keşfetmesine ve krallığı kurtarmasına yol açar.',
                'author' => 'Akira Sato',
                'artist' => 'Hiro Nakamura',
                'status' => 'ongoing',
                'is_nsfw' => false,
                'is_featured' => true,
                'categories' => ['Fantasy', 'Drama', 'Action'],
            ],
            [
                'type' => 'manga',
                'title' => 'Aşkın Melodisi',
                'description' => 'İki müzik öğrencisinin karşılaşması, onların hayatlarını tamamen değiştirir. Birlikte müzik yaparken, aşkın gerçek anlamını keşfederler.',
                'author' => 'Mika Suzuki',
                'artist' => 'Rina Kobayashi',
                'status' => 'completed',
                'is_nsfw' => false,
                'is_featured' => false,
                'categories' => ['Romance', 'Slice of Life', 'Drama'],
            ],
            [
                'type' => 'manga',
                'title' => 'Uzay Maceraları',
                'description' => 'Gelecekte, insanlık yıldızlar arasında yolculuk yapmaktadır. Bir grup kaşif, gizemli bir gezegen keşfeder ve orada beklenmedik tehlikelerle karşılaşır.',
                'author' => 'Takeshi Murata',
                'artist' => 'Ken Watanabe',
                'status' => 'ongoing',
                'is_nsfw' => false,
                'is_featured' => true,
                'categories' => ['Sci-Fi', 'Adventure', 'Action'],
            ],
            [
                'type' => 'manga',
                'title' => 'Komedi Kulübü',
                'description' => 'Bir lise komedi kulübünün üyeleri, her gün yeni şakalara imza atarlar. Kahkaha dolu anlar ve samimi dostluklar bu seriyi özel kılar.',
                'author' => 'Yui Tanaka',
                'artist' => 'Sakura Ito',
                'status' => 'ongoing',
                'is_nsfw' => false,
                'is_featured' => false,
                'categories' => ['Comedy', 'Slice of Life'],
            ],
            [
                'type' => 'manga',
                'title' => 'Karanlık Sırlar',
                'description' => 'Küçük bir kasabada gizemli cinayetler işlenmeye başlar. Genç bir dedektif, bu sırları çözmek için hayatını riske atar.',
                'author' => 'Ryo Ishikawa',
                'artist' => 'Kaito Yamada',
                'status' => 'ongoing',
                'is_nsfw' => true,
                'is_featured' => false,
                'categories' => ['Mystery', 'Horror', 'Drama'],
            ],
        ];

        foreach ($seriesData as $data) {
            // Extract categories
            $categoryNames = $data['categories'];
            unset($data['categories']);

            // Create series
            $series = Series::create([
                'type' => $data['type'],
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'description' => $data['description'],
                'cover_image' => $this->getCoverImage($data['title']),
                'status' => $data['status'],
                'author' => $data['author'],
                'artist' => $data['artist'],
                'is_nsfw' => $data['is_nsfw'],
                'is_featured' => $data['is_featured'],
                'views' => rand(100, 10000),
            ]);

            // Attach categories
            $categoryIds = Category::whereIn('name', $categoryNames)->pluck('id');
            $series->categories()->attach($categoryIds);
        }
    }

    private function getCoverImage($title)
    {
        $slug = Str::slug($title);
        $validSlugs = ['kahraman-gunlukleri', 'golgelerin-prensi', 'askin-melodisi', 'uzay-maceralari'];
        
        if (in_array($slug, $validSlugs)) {
            return "images/demo/{$slug}-cover.png";
        }
        
        // Fallback for others
        return "images/demo/kahraman-gunlukleri-cover.png";
    }
}
