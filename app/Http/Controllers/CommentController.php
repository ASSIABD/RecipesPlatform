<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request)
    {
        // Vérifier si l'utilisateur est connecté
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour laisser un commentaire.');
        }

        // Validation des données
        $validated = $request->validate([
            'recipe_id' => 'required|exists:recipes,id',
            'content' => 'required|string|max:1000',
        ]);

        try {
            // Création du commentaire avec l'ID de l'utilisateur connecté
            $comment = new Comment();
            $comment->user_id = Auth::id();
            $comment->recipe_id = $validated['recipe_id'];
            $comment->content = $validated['content'];
            $comment->save();

            return back()->with('success', 'Commentaire ajouté avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de l\'ajout du commentaire.')->withInput();
        }
    }
}
