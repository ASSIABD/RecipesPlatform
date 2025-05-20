<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Get the latest 4 recipes from all users
        $latestRecipes = Recipe::with(['user', 'category'])
                            ->latest()
                            ->take(4)
                            ->get();
        
        return view('welcome', compact('latestRecipes'));
    }
}