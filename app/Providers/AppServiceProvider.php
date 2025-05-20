<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Recipe;
use App\Observers\RecipeObserver;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the observer
        Recipe::observe(RecipeObserver::class);

        // Set the redirect path after authentication
        $this->app->instance('path.public', base_path('public'));
        
        // Override the default redirect path after login/register
        $this->app->when([
            \App\Http\Controllers\Auth\LoginController::class,
            \App\Http\Controllers\Auth\RegisterController::class,
            \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class
        ])->needs('$redirectTo')
          ->give(function () {
              return '/';
          });
    }
}
