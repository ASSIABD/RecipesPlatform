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
            [
                'name' => 'Appetizers',
                'slug' => 'appetizers',
                'description' => 'Delicious appetizers and starters',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Main Dishes',
                'slug' => 'main-dishes',
                'description' => 'Main course recipes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'description' => 'Sweet treats and desserts',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beverages',
                'slug' => 'beverages',
                'description' => 'Drink recipes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Breakfast',
                'slug' => 'breakfast',
                'description' => 'Breakfast recipes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($categories as $category) {
            try {
                Category::create($category);
            } catch (\Illuminate\Database\QueryException $e) {
                // Skip if category already exists
                continue;
            }
        }
    }
}
