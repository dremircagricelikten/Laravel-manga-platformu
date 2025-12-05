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
                'type' => 'internal',
                'url' => '/',
                'target' => '_self',
                'parent_id' => null,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Gözat',
                'type' => 'internal',
                'url' => '/browse',
                'target' => '_self',
                'parent_id' => null,
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Market',
                'type' => 'internal',
                'url' => '/market',
                'target' => '_self',
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
