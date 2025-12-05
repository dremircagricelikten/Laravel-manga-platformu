<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Series;
use App\Models\Chapter;
use Illuminate\Support\Str;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $series = Series::all();

        foreach ($series as $seriesItem) {
            // Completed series get more chapters
            $chapterCount = $seriesItem->status === 'completed' ? 12 : 5;

            for ($i = 1; $i <= $chapterCount; $i++) {
                $isFree = $i <= 2; // First 2 chapters are free
                $coinCost = $isFree ? 0 : ($i <= 5 ? 10 : 20); // Early chapters cost 10, later ones 20

                $chapter = Chapter::create([
                    'series_id' => $seriesItem->id,
                    'chapter_number' => $i,
                    'title' => "Bölüm $i",
                    'slug' => Str::slug($seriesItem->title . '-chapter-' . $i),
                    'content' => $this->generateChapterContent($seriesItem->title, $i),
                    'unlock_cost' => $coinCost, // Changed from coin_cost
                    'is_published' => true,
                    'published_at' => now()->subDays($chapterCount - $i),
                    'views' => rand(50, 5000),
                    'free_at' => $isFree ? now() : now()->addDays(3),
                ]);

                // Create 10-15 pages for each chapter
                $pageCount = rand(10, 15);
                $pages = [];
                $slug = Str::slug($seriesItem->title);
                $validSlugs = ['kahraman-gunlukleri', 'golgelerin-prensi', 'askin-melodisi', 'uzay-maceralari'];
                
                if (!in_array($slug, $validSlugs)) {
                    $slug = 'kahraman-gunlukleri';
                }

                $maxPages = ($slug === 'uzay-maceralari') ? 3 : 4;

                for ($p = 1; $p <= $pageCount; $p++) {
                    $pageNum = ($p % $maxPages) == 0 ? $maxPages : ($p % $maxPages);
                    $pages[] = "images/demo/{$slug}-page-{$pageNum}.png";
                }

                $chapter->update(['images' => $pages]); // Changed from pages to images
            }
        }
    }

    /**
     * Generate sample chapter content
     */
    private function generateChapterContent(string $seriesTitle, int $chapterNumber): string
    {
        $templates = [
            "Bu, $seriesTitle serisinin heyecan verici $chapterNumber. bölümü! Kahramanımız yeni zorluklarla karşılaşıyor ve beklenmedik olaylar yaşanıyor.",
            "Bölüm $chapterNumber'de hikaye daha da derinleşiyor. $seriesTitle evreni genişliyor ve yeni karakterler tanıtılıyor.",
            "Sürükleyici macera devam ediyor! $chapterNumber. bölümde gerilim doruğa ulaşıyor ve sırlar açığa çıkıyor.",
            "Duygusal anlar ve heyecan dolu sahneler bu bölümde bir arada. $seriesTitle hayranları bu bölümü çok sevecek!",
        ];

        return $templates[array_rand($templates)];
    }
}
