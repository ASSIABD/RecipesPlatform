<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Category;

class ShareCategories
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $categories = Category::all();
            view()->share('categories', $categories);
        } catch (\Exception $e) {
            // If categories table doesn't exist yet, just continue without sharing
            view()->share('categories', collect());
        }
        
        return $next($request);
    }
}
