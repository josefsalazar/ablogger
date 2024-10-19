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
    private function generateHeadersString(array $headers): string
    {
        $headersString = "";

        foreach ($headers as $header) {
            $headersString .= "H2: " . $header['h2'] . "\n";

            if (isset($header['h3']) && is_array($header['h3'])) {
                foreach ($header['h3'] as $h3) {
                    $headersString .= "H3: " . $h3 . "\n";
                }
            }
        }

        return $headersString;
    }

    public function generate(Request $request)
    {
        set_time_limit(300);

        $validated = $request->validate([
            'keyword' => 'required|string|max:255',
            'headers' => 'nullable|array',
            'headers.*.h2' => 'required|string|max:255',
            'headers.*.h3' => 'nullable|array',
            'headers.*.h3.*' => 'nullable|string|max:255',
            'additional_keywords' => 'nullable|string|max:500',
            'style' => 'required|string|in:professional,casual',
            'language' => 'required|string|in:english,spanish,french,german,portuguese,italian,russian,norwegian,icelandic',
            'article_length' => 'required|integer|min:1|max:5',
            'h3Length' => 'required|integer|min:1|max:5',
            'content' => 'nullable|string',
            'model' => 'required|string|in:gpt-3.5-turbo,gpt-4,gpt-4-32k',
            'format' => 'required|string|in:Blog,Lista,Guía,Tutorial,Comparativo',
            'takeaways' => 'nullable|boolean',
            'perspective' => 'nullable|string|in:first_person,second_person,third_person',
            'textIntention' => 'nullable|string|in:Informativa,Persuasiva,Narrativa,Descriptiva,Exhortativa,Expositiva,Argumentativa,Instructiva,Emotiva,Apreciativa,Opinionado',
        ]);



        if (!$request->user()->api_key) {
            return response()->json(
                [
                    'error' => 'API key is missing. Please add an API key in your profile settings.',
                ],
                400
            );
        }



        $headersString = $this->generateHeadersString($validated['headers']);

        $apiKey = $request->user()->api_key;

        $maxTokensPerH2 = 0;
        $maxTokensPerH3 = 0;

        if ($validated['article_length'] == 1) {
            $maxTokensPerH2 = 800;
        } elseif ($validated['article_length'] == 2) {
            $maxTokensPerH2 = 1600;
        } elseif ($validated['article_length'] == 3) {
            $maxTokensPerH2 = 2400;
        } elseif ($validated['article_length'] == 4) {
            $maxTokensPerH2 = 3200;
        } elseif ($validated['article_length'] == 5) {
            $maxTokensPerH2 = 4000;
        }

        if ($validated['h3Length'] == 1) {
            $maxTokensPerH3 = 800;
        } elseif ($validated['h3Length'] == 2) {
            $maxTokensPerH3 = 1600;
        } elseif ($validated['h3Length'] == 3) {
            $maxTokensPerH3 = 2400;
        } elseif ($validated['h3Length'] == 4) {
            $maxTokensPerH3 = 3200;
        } elseif ($validated['h3Length'] == 5) {
            $maxTokensPerH3 = 4000;
        }


        $systemh2 = "Estás a cargo de escribir un blog post en formato markdown usando únicamente h2 para los headers que se te va a indicar. Escribe en una combinación de oraciones cortas, medianas, y largas para un mejor flujo. Evita el relleno y trata de ir directo al punto. Cada palabra debe revelar información importante y pertinente del tema. No saludes ni des introducciones. NO HAGAS MÁS DE LO QUE SE TE PIDE. Escribe en un tono {$validated['style']}, en {$validated['language']}. El artículo debe tener un formato tipo {$validated['format']}. Tiene que tener una intención {$validated['textIntention']}, y en {$validated['perspective']}";

        $systemh3 = "Estás a cargo de escribir un blog post en formato markdown usando únicamente h3 para los headers que se te va a indicar. Escribe en una combinación de oraciones cortas, medianas, y largas para un mejor flujo. Evita el relleno y trata de ir directo al punto. Cada palabra debe revelar información importante y pertinente del tema. No saludes ni des introducciones. NO HAGAS MÁS DE LO QUE SE TE PIDE. Escribe en un tono {$validated['style']}, en {$validated['language']}. El artículo debe tener un formato tipo {$validated['format']}. Tiene que tener una intención {$validated['textIntention']}, y en {$validated['perspective']}";

        $systemKeyTakeaways = "Estás a cargo de escribir una introducción a un blog post en formato markdown usando el tema que se te va a indicar. Escribe en una combinación de oraciones cortas, medianas, y largas para un mejor flujo. Evita el relleno y trata de ir directo al punto. Cada palabra debe revelar información importante y pertinente del tema. Escribe en un tono {$validated['style']}, en {$validated['language']}. No escribas más de lo solicitado.";

        $fullContent = "";

        if ($validated['takeaways']) {

            $takeawaysCommand = "Responde a este tema o pregunta de inmediato {$validated['keyword']}, considerando que se hablará de estos temas: {$headersString} \n . No ocultes lo principal, hazlo en cuatro oraciones usando algunas {$validated['additional_keywords']}. Luego, escribe una introducción para el tema ({$validated['keyword']}). Asegúrate de intrigar al lector porque tenemos información valiosa más adelante. Finalmente, escribe en una lista conclusiones clave para que el usuario tenga una idea veloz de las ideas principales del tema. NO ESCRIBAS MÁS DE LO SOLICITADO, solo una introducción";


            try {
                $responseTakeaways = Http::withToken($apiKey)
                    ->withOptions(['verify' => false])
                    ->timeout(240)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $validated['model'],
                        'messages' => [
                            ['role' => 'system', 'content' => $systemKeyTakeaways],
                            ['role' => 'user', 'content' => $takeawaysCommand]
                        ],
                        'max_tokens' => 2000,
                        'temperature' => 0.6,
                        'top_p' => 0.8,
                        'frequency_penalty' => 0.3,
                        'presence_penalty' => 0.3
                    ]);

                if ($responseTakeaways->successful()) {
                    $generatedTakeaways = $responseTakeaways->json('choices.0.message.content');
                } else {
                }
            } catch (\Exception $e) {
            }


            $generatedTakeaways = $responseTakeaways->json('choices.0.message.content');


            $fullContent .= "# {$validated['keyword']}\n\n" . $generatedTakeaways . "\n\n";
        }

        $iteration = 0;

        foreach ($validated['headers'] as $header) {
            $h3Titles = isset($header['h3']) ? implode(', ', $header['h3']) : '';

            /* $h2Command = "No expliques nada profundo ni largo, solo explica lo que estamos por ver, ya que se procederá a hablar del tema '{$header['h2']}', y escribe el título en markdown h2 tal cual fue dado. El artículo ya ha sido comenzado, así que estás trabajando sobre algo ya iniciado. Así que explica brevemente por qué estos subtemas que trataremos son importantes: {$h3Titles}. Da razones brevemente de por qué son importantes y una breve reseña de lo que está por verse. Usa las keywords: '{$validated['additional_keywords']}'. No agregues más headers que el dado. No cambies de tema"; */

            $h2Command = "Escribe este título en markdown h2 tal cual es dado '{$header['h2']}', y haz una breve explicación del tema mencionando los subtemas que veremos a continuación: {$h3Titles}. No introduzcas el artículo ni cierres esta sección. Explica de manera breve por qué estos subtemas son importantes, sin extenderte demasiado. No comiences con frases del tipo 'A continuación veremos' ni 'Comencemos' o similares. Da razones claras y concisas sobre la relevancia de cada subtema. Usa las keywords: '{$validated['additional_keywords']}'. No agregues más headers que el dado. No cambies de tema.";



            $iteration = $iteration + 1;

            try {
                $responseH2 = Http::withToken($apiKey)
                    ->withOptions(['verify' => false])
                    ->timeout(240)
                    ->post('https://api.openai.com/v1/chat/completions', [
                        'model' => $validated['model'],
                        'messages' => [
                            ['role' => 'system', 'content' => $systemh2],
                            ['role' => 'user', 'content' => $h2Command]
                        ],
                        'max_tokens' => $maxTokensPerH2,
                        'temperature' => 0.7,
                        'top_p' => 0.8,
                        'frequency_penalty' => 0.3,
                        'presence_penalty' => 0.3
                    ]);

                if ($responseH2->successful()) {
                    $generatedH2Content = $responseH2->json('choices.0.message.content');
                } else {
                }
            } catch (\Exception $e) {
            }

            $generatedH2Content = $responseH2->json('choices.0.message.content');



            $fullContent .= $generatedH2Content . "\n\n";

            foreach ($header['h3'] as $subheader) {
                $h3Command = "Explica únicamente el subtema '{$subheader}' y escríbelo tal cual mostrado como un h3 en markdown. Explica a profundidad el tema para dar la mayor cantidad de información posible siempre y cuando esté directamente relacionada al actual subtema. Usa las keywords: '{$validated['additional_keywords']}'. No agregues más headers que el dado. No cambies de tema. No des introducciones.";



                try {
                    $responseH3 = Http::withToken($apiKey)
                        ->withOptions(['verify' => false])
                        ->timeout(240)
                        ->post('https://api.openai.com/v1/chat/completions', [
                            'model' => $validated['model'],
                            'messages' => [
                                ['role' => 'system', 'content' => $systemh3],
                                ['role' => 'user', 'content' => $h3Command]
                            ],
                            'max_tokens' => $maxTokensPerH3,
                            'temperature' => 0.8,
                            'top_p' => 0.8,
                            'frequency_penalty' => 0.3,
                            'presence_penalty' => 0.3
                        ]);

                    if ($responseH3->successful()) {
                        $generatedH3Content = $responseH3->json('choices.0.message.content');
                    } else {
                    }
                } catch (\Exception $e) {
                }


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
