<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Recipe; // Recipe model n'est pas directement utilisé ici car on passe par les relations de User
// Inutile d'importer Comment ici, on l'utilise via la relation de User

class AuthorController extends Controller
{
    public function index()
    {
        $users = User::withCount('recipes')->latest()->paginate(12); // Ajout de latest() et paginate() pour une meilleure présentation
        return view('profiles.authors', compact('users'));
    }

    public function showRecipes($id)
    {
        // Récupère l'auteur, s'assure qu'il existe, et compte ses recettes
        $author = User::withCount('recipes')->findOrFail($id);

        // Récupère les recettes de l'auteur, paginées
        $recipes = $author->recipes()->latest()->paginate(6, ['*'], 'recipes_page'); // Paginé avec un nom de page spécifique

        // NOUVEAU: Récupérer les commentaires de cet auteur
        // Eager load 'recipe' pour accéder aux détails de la recette commentée
        // Eager load 'user' n'est pas strictement nécessaire ici car $author est déjà $comment->user, mais bonne pratique si le template est générique
        $comments = $author->comments()
                           ->with('recipe') // Important pour afficher le titre de la recette commentée
                           ->latest()       // Les plus récents en premier
                           ->paginate(5, ['*'], 'comments_page'); // Paginé avec un nom de page spécifique pour éviter conflit

        // La vue qui affiche le profil de l'auteur et ses recettes
        // Doit être resources/views/profiles/recipes.blade.php
        return view('profiles.recipes', compact('author', 'recipes', 'comments'));
    }

    /**
     * Affiche une page dédiée aux commentaires d'un utilisateur.
     * Votre vue actuelle est 'profiles.comments.blade.php', donc adaptons le nom de la vue.
     */
    public function showComments($id)
    {
        $user = User::findOrFail($id);
        // Charger la recette ET l'utilisateur (auteur du commentaire) pour chaque commentaire
        $comments = $user->comments()->with(['recipe', 'user'])->latest()->paginate(10);
        // Assurez-vous que la variable passée ici est $user si la vue attend $user->name
        // ou $author si la vue attend $author->name. Ici c'est $user.
        return view('profiles.comments', compact('user', 'comments')); // Changé 'authors.comments' en 'profiles.comments'
    }
}