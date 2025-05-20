<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleRecipesSeeder extends Seeder
{
    public function run()
    {
        // Get all users
        $users = User::all();
        
        // Sample recipes data with Pexels images
        $recipes = [
            [
                'title' => 'Creamy Garlic Pasta',
                'description' => 'A rich and creamy garlic pasta that comes together in just 20 minutes!',
                'ingredients' => "8 oz fettuccine\n4 cloves garlic, minced\n2 tbsp butter\n1 cup heavy cream\n1/2 cup parmesan, grated\nSalt and pepper to taste\nFresh parsley, chopped",
                'steps' => "1. Cook pasta according to package instructions.\n2. In a pan, melt butter and sauté garlic until fragrant.\n3. Add heavy cream and bring to a simmer.\n4. Stir in parmesan until melted.\n5. Toss with cooked pasta.\n6. Season with salt, pepper, and garnish with parsley.",
                'duration' => 20,
                'difficulty' => 'easy',
                'image' => 'https://images.pexels.com/photos/1437267/pexels-photo-1437267.jpeg',
                'category_id' => 1
            ],
            [
                'title' => 'Grilled Salmon with Lemon',
                'description' => 'Perfectly grilled salmon with a fresh lemon butter sauce.',
                'ingredients' => "2 salmon fillets\n2 tbsp olive oil\n1 lemon, sliced\n2 cloves garlic, minced\n1 tsp dried dill\nSalt and pepper to taste",
                'steps' => "1. Preheat grill to medium-high heat.\n2. Brush salmon with olive oil and season with salt, pepper, and dill.\n3. Grill for 4-5 minutes per side.\n4. Top with lemon slices and minced garlic.\n5. Serve with grilled vegetables.",
                'duration' => 25,
                'difficulty' => 'medium',
                'image' => 'https://images.pexels.com/photos/46239/salmon-dish-food-meal-46239.jpeg',
                'category_id' => 4
            ],
            [
                'title' => 'Chocolate Lava Cake',
                'description' => 'Decadent chocolate lava cakes with a gooey center, perfect for any occasion.',
                'ingredients' => "1/2 cup butter\n4 oz dark chocolate\n1 cup powdered sugar\n2 eggs\n2 egg yolks\n1 tsp vanilla extract\n1/2 cup all-purpose flour\nPinch of salt",
                'steps' => "1. Preheat oven to 425°F (220°C).\n2. Melt butter and chocolate together.\n3. Stir in powdered sugar.\n4. Whisk in eggs, yolks, and vanilla.\n5. Fold in flour and salt.\n6. Pour into greased ramekins.\n7. Bake for 12-14 minutes.\n8. Let sit for 1 minute before inverting onto plates.",
                'duration' => 30,
                'difficulty' => 'hard',
                'image' => 'https://images.pexels.com/photos/45202/brownie-dessert-cake-sweet-45202.jpeg',
                'category_id' => 5
            ]
        ];

        // Create recipes for each user
        foreach ($users as $user) {
            foreach ($recipes as $recipeData) {
                $recipe = new Recipe($recipeData);
                $recipe->user_id = $user->id;
                $recipe->save();
            }
        }
    }
}
