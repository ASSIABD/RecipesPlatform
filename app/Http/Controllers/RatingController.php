<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recipe;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        Rating::updateOrCreate(
            ['user_id' => Auth::id(), 'recipe_id' => $validated['recipe_id']],
            ['rating' => $validated['rating']]
        );

        return back()->with('success', 'Thanks for your rating!');
    }
}
