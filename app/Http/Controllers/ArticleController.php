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

        // Validation
        $validated = $request->validate([
            'keyword' => 'required|string',
            'headers' => 'nullable|array',
            'headers.*.h2' => 'required|string',
            'headers.*.h3' => 'nullable|array',
            'headers.*.h3.*' => 'nullable|string',
            'additional_keywords' => 'nullable|string',
            'style' => 'required|string',
            'language' => 'required|string',
            'article_length' => 'required|integer|min:0|max:100',
            'content' => 'nullable|string',
        ]);

        if (!$request->user()->api_key) {
            return response()->json(
                [
                    'error' => 'API key is missing. Please add an API key in your profile settings.',
                ],
                400
            );  // Send a 400 (Bad Request) response
        }

        // Get the API key from the logged-in user or fallback to the default key
        $apiKey = $request->user()->api_key ?? env('OPENAI_API_KEY');

        // System message setup
        $system = "Estás a cargo de escribir un blog post en formato markdown. Escribe en oraciones cortas, medianas, y largas para un mejor flujo. Evita el relleno y trata de ir directo al punto. Cada palabra debe revelar información importante del tema. Escribe en un tono {$validated['style']}, en {$validated['language']}. No des conclusiones, ni resumenes hasta que se te indique.";

        $fullContent = "";
        $iteration = 0;

        // Loop through each header and send API requests
        foreach ($validated['headers'] as $header) {
            $userCommand = "Escribe una sección extensa sobre '{$header['h2']}'. Debes de usar estos subtemas: '" . implode(', ', $header['h3']) . "'. Usa también las keywords: '{$validated['additional_keywords']}'.";


            $response = Http::withToken($apiKey)
                ->withOptions(['verify' => false])
                ->timeout(240)
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'system', 'content' => $system],
                        ['role' => 'user', 'content' => $userCommand]
                    ],
                    'max_tokens' => 4000,
                    'temperature' => 1,
                    'top_p' => 0.8,
                    'frequency_penalty' => 0.3,
                    'presence_penalty' => 0.3
                ]);

            $generatedContent = $response->json('choices.0.message.content');
            $fullContent .= $generatedContent . "\n\n";
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

    /* public function generate(Request $request)
    {


        $validated = $request->validate([
            'keyword' => 'required|string',
            'headers' => 'nullable|array',
            'headers.*.h2' => 'required|string',
            'headers.*.h3' => 'nullable|array',
            'headers.*.h3.*' => 'nullable|string',
            'additional_keywords' => 'nullable|string',
            'style' => 'required|string',
            'language' => 'required|string',
            'article_length' => 'required|integer|min:0|max:100',
            'content' => 'nullable|string',
        ]);

        $headersMarkdown = '';
        foreach ($validated['headers'] as $header) {
            // Convertir cada H2
            $headersMarkdown .= "## " . $header['h2'] . "\n\n";

            // Convertir cada H3 debajo del H2
            foreach ($header['h3'] as $subheader) {
                $headersMarkdown .= "### " . $subheader . "\n\n";
            }
        }


        $sampleText = "# {$validated['keyword']}

        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec a diam lectus. Sed sit amet ipsum mauris.

        ## {$headersMarkdown}

        Vestibulum auctor dapibus neque. **Nunc dignissim risus id metus.** Cras ornare tristique elit. Vivamus vestibulum sagittis diam.

        ### Vivamus Vestibulum Sagittis

        Phasellus fermentum in, dolor. Pellentesque facilisis. Nulla imperdiet sit amet magna. **Vestibulum dapibus**, mauris nec malesuada fames ac turpis velit.

        #### Pellentesque Facilisis

        - Lorem ipsum dolor sit amet
        - Consectetur adipiscing elit
        - Proin pharetra nonummy pede
        - Mauris et orci
        - Nulla facilisi

        1. Integer malesuada
        2. Cras ornare tristique elit
        3. Nullam vel sem
        1. Aenean dignissim
        2. Phasellus ultrices nulla

        > \"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin fringilla, augue id viverra venenatis, justo libero mattis nisi, ac efficitur turpis nisi in ligula.\"  
        > — John Doe

        Fusce venenatis, quam sit amet **venenatis** tincidunt, lacus nunc vehicula nisi, eget fringilla velit quam nec lacus.";
        $sampleText = preg_replace('/^\s+/m', '', $sampleText);

        $title = ucfirst($validated['keyword']);

        $article = Article::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'content' => $sampleText,
        ]);

        return response()->json([
            'content' => $sampleText
        ]);
    } */


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
