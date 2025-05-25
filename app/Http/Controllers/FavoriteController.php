<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggleFavorite($id)
    {
        $user = Auth::user();
        $recipe = Recipe::findOrFail($id);

        if ($user->favoriteRecipes()->where('recipes.id', $id)->exists()) {
            $user->favoriteRecipes()->detach($recipe);
            return response()->json(['success' => true, 'favorited' => false]);
        } else {
            $user->favoriteRecipes()->attach($recipe);
            return response()->json(['success' => true, 'favorited' => true]);
        }
    }

    public function index()
    {
        $favorites = Auth::user()->favoriteRecipes;
        return view('favorites.index', compact('favorites'));
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $recipe = Recipe::findOrFail($id);
        $user->favoriteRecipes()->detach($recipe);

        return redirect()->route('favorites.index')->with('success', 'Recette retirée des favoris.');
    }
}
