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
                'title' => 'Gözat',
                'route' => 'browse',
                'parent_id' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Market',
                'route' => 'market.index',
                'parent_id' => null,
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }
    }
}
