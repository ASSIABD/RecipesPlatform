<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Recipe;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Clear existing categories and seed new ones
        $this->call([
            CategorySeeder::class,
            SampleRecipesSeeder::class,
        ]);
        
        // Get all existing users
        $users = User::all();

        // If no users exist, create a sample user
        if ($users->isEmpty()) {
            $user = User::create([
                'name' => 'Sophie',
                'email' => 'notsophie18@gmail.com',
                'password' => bcrypt('password'),
            ]);
            $users[] = $user;
        }

        // Clear existing recipes for all users
        Recipe::query()->delete();

        // Create sample recipes for each user
        foreach ($users as $user) {
            for ($i = 1; $i <= 5; $i++) {
            // Get a random category ID (assuming categories exist)
            $categoryId = \App\Models\Category::inRandomOrder()->first()->id;
            
            // Array of food-related images matched to recipes
            $images = [
                'https://images.pexels.com/photos/4518843/pexels-photo-4518843.jpeg?auto=compress&cs=tinysrgb&w=400', // Pasta with tomato and shrimp
                'https://images.pexels.com/photos/1059905/pexels-photo-1059905.jpeg?auto=compress&cs=tinysrgb&w=400', // Vegetable salad
                'https://images.pexels.com/photos/16890470/pexels-photo-16890470.jpeg?auto=compress&cs=tinysrgb&w=400', // Pizza
                'https://images.pexels.com/photos/1639557/pexels-photo-1639557.jpeg?auto=compress&cs=tinysrgb&w=400', // Hamburger
                'https://images.pexels.com/photos/31923886/pexels-photo-31923886.jpeg?auto=compress&cs=tinysrgb&w=400', // Sushi
                'https://images.pexels.com/photos/566566/pexels-photo-566566.jpeg?auto=compress&cs=tinysrgb&w=400', // Pastry and egg (breakfast)
                'https://images.pexels.com/photos/14457510/pexels-photo-14457510.jpeg?auto=compress&cs=tinysrgb&w=400', // Dessert
                'https://images.pexels.com/photos/3493579/pexels-photo-3493579.jpeg?auto=compress&cs=tinysrgb&w=400', // Tomato soup
                'https://images.pexels.com/photos/27772885/pexels-photo-27772885.jpeg?auto=compress&cs=tinysrgb&w=400', // Grilled steak
                'https://images.pexels.com/photos/29843070/pexels-photo-29843070.jpeg?auto=compress&cs=tinysrgb&w=400', // Seafood paella
            ];
             // Generate a random title and description
            $titles = [
                'Classic Spaghetti Carbonara',
                'Fresh Garden Salad',
                'Margherita Pizza',
                'Classic Cheeseburger',
                'Sushi Platter',
                'Chocolate Lava Cake',
                'Avocado Toast',
                'Creamy Tomato Soup',
                'Grilled Ribeye Steak',
                'Seafood Paella'
            ];

            $descriptions = [
                'A creamy pasta dish with pancetta, eggs, and parmesan cheese.',
                'A refreshing mix of fresh vegetables and a light vinaigrette.',
                'Simple yet delicious pizza with fresh tomatoes and mozzarella.',
                'Juicy beef patty with all the classic toppings.',
                'A variety of fresh sushi rolls and sashimi.',
                'Decadent chocolate cake with molten center.',
                'Toasted bread topped with ripe avocado and spices.',
                'Smooth and comforting tomato soup with a hint of cream.',
                'Perfectly cooked steak with a rich flavor.',
                'Colorful and flavorful Spanish seafood dish.'
            ];

            Recipe::create([
                'user_id' => $user->id,
                'category_id' => $categoryId,
                'title' => $titles[$i-1],
                'description' => $descriptions[$i-1],
                'image' => $images[$i-1],
                'ingredients' => "Ingredient 1\nIngredient 2\nIngredient 3",
                'steps' => "Step 1: Prepare ingredients\nStep 2: Cook\nStep 3: Serve",
                'duration' => 30,
            ]);
        }

        echo "10 recipes added successfully.\n";
        }
    }
}
