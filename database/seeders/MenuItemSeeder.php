<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuItems = [
            [
                'title' => 'Ana Sayfa',
                'route' => 'home',
                'parent_id' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Tüm Seriler',
                'route' => 'browse',
                'parent_id' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Son Bölümler',
                'route' => 'latest',
                'parent_id' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Popüler',
                'route' => 'popular',
                'parent_id' => null,
                'sort_order' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }
    }
}
