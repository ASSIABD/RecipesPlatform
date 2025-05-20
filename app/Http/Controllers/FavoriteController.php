<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Toggle favorite status for a recipe
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFavorite(Request $request, $id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                ], 401);
            }

            // Check if the recipe is already favorited
            $isFavorited = $user->favoriteRecipes()->where('item_id', $recipe->id)->exists();
            
            if ($isFavorited) {
                // Remove from favorites
                $user->favoriteRecipes()->detach($recipe->id);
                $isFavorited = false;
            } else {
                // Add to favorites
                $user->favoriteRecipes()->attach($recipe->id, ['item_type' => get_class($recipe)]);
                $isFavorited = true;
            }

            // Refresh the recipe to get the updated favorites count
            $recipe->refresh();

            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'favorites_count' => $recipe->favorites()->count(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error toggling favorite: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating favorites',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get the favorite status for a recipe
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkFavorite($id)
    {
        try {
            $recipe = Recipe::findOrFail($id);
            $isFavorited = false;
            
            if (Auth::check()) {
                $isFavorited = Auth::user()->favoriteRecipes()->where('item_id', $recipe->id)->exists();
            }

            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'favorites_count' => $recipe->favorites()->count(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error checking favorite: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while checking favorite status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all favorite recipes for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $favorites = auth()->user()->favoriteRecipes()->with('category')->get();
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $favorites
                ]);
            }
            
            return view('favorites.index', compact('favorites'));
        } catch (\Exception $e) {
            \Log::error('Error fetching favorites: ' . $e->getMessage());
            
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while fetching favorites',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'An error occurred while fetching your favorites.');
        }
    }

    /**
     * Get the count of favorites for the authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function count()
    {
        try {
            $count = auth()->user()->favoriteRecipes()->count();
            
            return response()->json([
                'success' => true,
                'count' => $count
            ]);
        } catch (\Exception $e) {
            \Log::error('Error counting favorites: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while counting favorites',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    // The toggleFavorite and checkFavorite methods handle all the favorite functionality
}
