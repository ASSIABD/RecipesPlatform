<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Salads',
            'Desserts',
            'Juice',
            'Asian Food',
            'Main Dishes',
            'Appetizers',
            'Soups',
            'Breakfast',
            'Vegan',
            'Vegetarian',
            'Gluten Free',
            'Low Carb',
            'Quick Meals',
            'Family Meals',
            'Holiday Specials'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category
            ]);
        }
    }
}
