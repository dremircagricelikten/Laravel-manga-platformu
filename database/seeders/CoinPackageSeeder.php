<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoinPackage;

class CoinPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Başlangıç Paketi',
                'slug' => 'starter-package',
                'description' => 'Başlamak için ideal paket',
                'coin_amount' => 100,
                'price' => 9.99,
                'bonus_coins' => 0,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Popüler Paket',
                'slug' => 'popular-package',
                'description' => 'En çok tercih edilen paket',
                'coin_amount' => 500,
                'price' => 39.99,
                'bonus_coins' => 50,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Mega Paket',
                'slug' => 'mega-package',
                'description' => 'Büyük tasarruf',
                'coin_amount' => 1000,
                'price' => 69.99,
                'bonus_coins' => 150,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Ultra Paket',
                'slug' => 'ultra-package',
                'description' => 'En iyi fiyat-performans',
                'coin_amount' => 2500,
                'price' => 149.99,
                'bonus_coins' => 500,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Premium Paket',
                'slug' => 'premium-package',
                'description' => 'Sınırsız okuma için',
                'coin_amount' => 5000,
                'price' => 249.99,
                'bonus_coins' => 1500,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($packages as $package) {
            CoinPackage::create($package);
        }
    }
}
