<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ArticleController extends Controller
{
    public function generate(Request $request)
    {
        set_time_limit(300);

        $validated = $request->validate([
            'keyword' => 'required|string',
            'headers' => 'nullable|array',
            'headers.*.h2' => 'required|string',
            'headers.*.h3' => 'nullable|array',
            'headers.*.h3.*' => 'nullable|string',
            'additional_keywords' => 'nullable|string',
            'style' => 'required|string',
            'language' => 'required|string',
            'article_length' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string',
            'model' => 'required|string',
            'format' => 'required|string|in:Blog,Lista,Guía',
            'takeaways' => 'nullable|boolean'
        ]);

        if (!$request->user()->api_key) {
            return response()->json(
                [
                    'error' => 'API key is missing. Please add an API key in your profile settings.',
                ],
                400
            );
        }

        $headersString = "";

        // Recorrer los headers validados (H2 y H3)
        foreach ($validated['headers'] as $header) {
            // Agregar el H2 al string
            $headersString .= "H2: " . $header['h2'] . "\n";

            // Verificar si existen H3 y agregarlos
            if (isset($header['h3']) && is_array($header['h3'])) {
                foreach ($header['h3'] as $h3) {
                    $headersString .= "H3: " . $h3 . "\n";
                }
            }
        }

        $apiKey = $request->user()->api_key;

        $systemh2 = "Estás a cargo de escribir un blog post en formato markdown usando solo h2 para los headers que se te va a indicar. Escribe en una combinación de oraciones cortas, medianas, y largas para un mejor flujo. Evita el relleno y trata de ir directo al punto. Cada palabra debe revelar información importante y pertinente del tema. Escribe en un tono {$validated['style']}, en {$validated['language']}. El artículo debe tener un formato tipo {$validated['format']}. No hagas más headers de los ya enviados";

        $systemh3 = "Estás a cargo de escribir un blog post en formato markdown usando solo h3 para los headers que se te va a indicar. Escribe en una combinación de oraciones cortas, medianas, y largas para un mejor flujo. Evita el relleno y trata de ir directo al punto. Cada palabra debe revelar información importante y pertinente del tema. Escribe en un tono {$validated['style']}, en {$validated['language']}. El artículo debe tener un formato tipo {$validated['format']}. No hagas más headers de los ya enviados";

        $systemKeyTakeaways = "Estás a cargo de escribir una introducción a un blog post en formato markdown usando el tema que se te va a indicar. Escribe en una combinación de oraciones cortas, medianas, y largas para un mejor flujo. Evita el relleno y trata de ir directo al punto. Cada palabra debe revelar información importante y pertinente del tema. Escribe en un tono {$validated['style']}, en {$validated['language']}. No escribas más de lo solicitado.";

        $fullContent = "";

        if ($validated['takeaways']) {

            $takeawaysCommand = "Responde a este tema o pregunta de inmediato {$validated['keyword']}, considerando que se hablará de estos temas: {$headersString} \n . No ocultes lo principal, hazlo en cuatro oraciones usando algunas {$validated['additional_keywords']} o LSIs. Luego, escribe una introducción para el tema ({$validated['keyword']}). Asegúrate de intrigar al lector porque tenemos información valiosa más adelante. Finalmente, escribe en una lista conclusiones clave para que el usuario tenga una idea veloz de las ideas principales del tema ";

            $responseTakeaways = Http::withToken($apiKey)
                ->withOptions(['verify' => false])
                ->timeout(240)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $validated['model'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemKeyTakeaways],
                        ['role' => 'user', 'content' => $takeawaysCommand]
                    ],
                    'max_tokens' => 4000,
                    'temperature' => 1,
                    'top_p' => 0.8,
                    'frequency_penalty' => 0.3,
                    'presence_penalty' => 0.3
                ]);

            $generatedTakeaways = $responseTakeaways->json('choices.0.message.content');
            $fullContent .= "# {$validated['keyword']}\n\n" . $generatedTakeaways . "\n\n";
        }

        foreach ($validated['headers'] as $header) {
            $h2Command = "Escribe una introducción extensa sobre '{$header['h2']}'. Usa Latent Semantic Indexing de las keywords: '{$validated['additional_keywords']}'.";

            $responseH2 = Http::withToken($apiKey)
                ->withOptions(['verify' => false])
                ->timeout(240)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $validated['model'],
                    'messages' => [
                        ['role' => 'system', 'content' => $systemh2],
                        ['role' => 'user', 'content' => $h2Command]
                    ],
                    'max_tokens' => 4000,
                    'temperature' => 1,
                    'top_p' => 0.8,
                    'frequency_penalty' => 0.3,
                    'presence_penalty' => 0.3
                ]);

            $generatedH2Content = $responseH2->json('choices.0.message.content');
            $fullContent .= $generatedH2Content . "\n\n";

            foreach ($header['h3'] as $subheader) {
                $h3Command = "Explica el subtema '{$subheader}'. usando Latent Semantic Indexing de las keywords: '{$validated['additional_keywords']}'.";

                $responseH3 = Http::withToken($apiKey)
                    ->withOptions(['verify' => false])
                    ->timeout(240)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $validated['model'],
                        'messages' => [
                            ['role' => 'system', 'content' => $systemh3],
                            ['role' => 'user', 'content' => $h3Command]
                        ],
                        'max_tokens' => 4000,
                        'temperature' => 1,
                        'top_p' => 0.8,
                        'frequency_penalty' => 0.3,
                        'presence_penalty' => 0.3
                    ]);

                $generatedH3Content = $responseH3->json('choices.0.message.content');
                $fullContent .= $generatedH3Content . "\n\n";
            }
        }


        $title = ucfirst($validated['keyword']);
        $article = Article::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'content' => $fullContent,
        ]);

        return response()->json([
            'content' => $fullContent
        ]);
    }



    public function index()
    {
        $articles = Article::where('user_id', Auth::id())->get();

        return Inertia::render('Articles/Index', [
            'articles' => $articles
        ]);
    }

    public function show(Article $article)
    {
        if ($article->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Articles/Show', [
            'article' => $article
        ]);
    }
}
