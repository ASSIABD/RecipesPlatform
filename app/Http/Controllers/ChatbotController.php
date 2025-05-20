<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function respond(Request $request)
    {
        $message = $request->input('message');

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

        $reply = $response->json()['choices'][0]['message']['content'] ?? "Désolé, je n'ai pas compris.";

        return response()->json(['reply' => $reply]);
    }
}

