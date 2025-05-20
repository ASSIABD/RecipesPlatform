<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FavoriteController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Favorite routes - Using web middleware for session-based authentication
Route::middleware('auth:web')->group(function () {
    Route::post('/favorites/{id}/toggle', [FavoriteController::class, 'toggleFavorite']);
    Route::get('/favorites/{id}/check', [FavoriteController::class, 'checkFavorite']);
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::get('/favorites/count', [FavoriteController::class, 'count']);
});
