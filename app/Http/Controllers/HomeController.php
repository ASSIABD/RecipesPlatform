<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Latest 4 recipes from all users with needed relations
        $latestRecipes = Recipe::with(['user', 'category', 'ratings'])
                            ->latest()
                            ->take(4)
                            ->get();

        // Current logged-in user's recipes with relations
        $userRecipes = Recipe::with(['category', 'ratings'])
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->get();

        return view('welcome', compact('latestRecipes', 'userRecipes'));
    }
}
