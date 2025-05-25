<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function respond(Request $request)
{
    $message = $request->input('message');

    // Affiche la clé API pour vérifier qu'elle est bien chargée
    \Log::info('Clé API OpenAI:', [env('OPENAI_API_KEY')]);

    // Envoie la requête à OpenAI
    $response = Http::withToken(env('OPENAI_API_KEY'))
        ->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Tu es un assistant cuisine. Donne des conseils de recettes.'],
                ['role' => 'user', 'content' => $message]
            ],
            'temperature' => 0.7,
            'max_tokens' => 300
        ]);

    // Logue la réponse brute reçue d'OpenAI
    \Log::info('Réponse brute OpenAI:', ['body' => $response->body()]);

    // Essaie d'extraire la réponse texte
    $reply = $response->json()['choices'][0]['message']['content'] ?? "Hello! How can I help you?";

    return response()->json(['reply' => $reply]);
}

}

