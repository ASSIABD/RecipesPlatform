<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController; // J'ai corrigé le chemin ici, en supposant que c'est le RegisterController standard de Laravel.
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
// Si vous avez un DashboardController, décommentez la ligne suivante et assurez-vous qu'il existe :
// use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RatingController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route pour la page d'accueil & "home" (souvent la même après connexion)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Redirect /home to the root URL to fix 404 errors
Route::redirect('/home', '/');

// Route pour la barre de navigation (si c'est une vue statique et non un composant inclus)
Route::get('/navBare', function () {
    return view('layouts.navBare');
})->name('navbare'); // Donnons-lui un nom pour la cohérence

// Routes nécessitant une authentification
Route::middleware(['auth'])->group(function () {
    // Si vous avez un dashboard spécifique après connexion
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Routes pour les favoris
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/recipes/{recipe}/favorite', [FavoriteController::class, 'toggleFavorite'])->name('recipes.favorite.toggle'); // Route plus RESTful
    // Les routes check et count pourraient être gérées via des appels API ou des composants dynamiques si besoin.
    // Route::get('/favorites/{id}/check', [FavoriteController::class, 'checkFavorite'])->name('favorites.check');
    // Route::get('/favorites/count', [FavoriteController::class, 'count'])->name('favorites.count');

    // Routes pour le profil utilisateur connecté
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Route pour se déconnecter
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Route spéciale pour le bouton "Add Recipe"
    Route::get('/add-recipe', function () {
        return redirect()->route('recipes.create');
    })->name('add-recipe');
});

// Route pour afficher le layout principal (si c'est une page de démo/test, sinon à revoir)
Route::get('/main', function () {
    return view('layouts.main');
})->name('main.layout');

// Routes pour les auteurs (profils publics d'auteurs)
Route::get('/auteurs', [AuthorController::class, 'index'])->name('auteurs.index');
Route::get('/auteurs/{user}', [AuthorController::class, 'showRecipes'])->name('auteurs.recipes'); // {user} est plus idiomatique
Route::get('/auteurs/{user}/comments', [AuthorController::class, 'showComments'])->name('auteurs.comments');

// Routes pour les Recettes
Route::get('/AllRecipes', [RecipeController::class, 'index1'])->name('recette.index'); // Liste publique des recettes
// La route pour afficher un détail de recette est gérée par Route::resource plus bas (recipes.show)

// Routes CRUD pour les recettes (create, store, show, edit, update, destroy)
Route::resource('recipes', RecipeController::class);
// Pour protéger certaines actions du resource controller (comme create, store, edit, update, destroy) :
// Route::resource('recipes', RecipeController::class)->middleware('auth')->except(['index', 'show']);
// Ou protéger individuellement dans le constructeur du RecipeController.

// Routes pour le ChatBot
Route::get('/chatbot', function () {
    return view('chatbot');
})->name('chatbot.show');
Route::post('/chatbot', [ChatbotController::class, 'respond'])->name('chatbot.respond');

// Routes d'authentification générées par Laravel UI/Breeze/Jetstream
Auth::routes(['logout' => false]); // Inclut login, register, password reset, etc. MAIS désactive la route logout de Auth::routes() car on l'a définie manuellement dans le groupe 'auth'.

// Routes pour les invités (non connectés) - Généralement déjà couvertes par Auth::routes() si utilisé.
// Si vous n'utilisez pas Auth::routes() ou voulez surcharger :
/*
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});
*/
Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

?>