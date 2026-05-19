<?php

namespace App\Services;

use App\Models\Ingredient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class IngredientAiValidator
{
    public function validate(string $ingredientName): array
    {
        $categories = implode(', ', Ingredient::CATEGORIES);

        $response = Http::withToken(config('services.openai.key'))
            ->timeout(20)
            ->post('https://api.openai.com/v1/responses', [
                'model' => config('services.openai.model') ?: 'gpt-4o-mini',

                'input' => [
                    [
                        'role' => 'system',
                        'content' => "
                            Eres un validador de ingredientes para una aplicacion de recetas llamada QueComoHoy.

                            Tu tarea es analizar el ingrediente escrito por el usuario.

                            Reglas:
                            - Si el texto corresponde a un alimento real, devuelve is_food = true.
                            - Acepta ingredientes simples y tambien nombres compuestos habituales de cocina.
                            - Acepta cortes o partes de alimentos: lomo de cerdo, pechuga de pollo, costilla de ternera.
                            - Acepta alimentos con origen o tipo: leche de coco, harina de trigo, aceite de oliva.
                            - Corrige faltas de ortografia, errores foneticos y errores pequenos de teclado.
                            - No rechaces un alimento solo porque este mal escrito si hay un ingrediente culinario espanol claro.
                            - Acepta confusiones habituales del espanol como b/v, c/q/k, g/j, ll/y, h omitida, letras duplicadas, letras cambiadas, acentos omitidos o falta de enye.
                            - Cuando el texto parezca una palabra incompleta o con una letra cambiada, elige el ingrediente culinario mas probable, no uno generico.
                            - Conserva el animal o alimento especifico cuando sea reconocible por proximidad. Por ejemplo, avo en una receta debe corregirse como pavo, no como ave.
                            - Si el usuario escribe una preposicion mal pero el alimento es claro, corrigela. Por ejemplo: lomo se cerdi -> Lomo de cerdo.
                            - Devuelve el nombre corregido en espanol.
                            - Devuelve el nombre en singular cuando tenga sentido.
                            - La primera letra del nombre debe ir en mayuscula.
                            - Elige una categoria valida de esta lista: {$categories}.
                            - No aceptes utensilios de cocina.
                            - No aceptes objetos.
                            - No aceptes marcas comerciales como ingrediente principal.
                            - No aceptes instrucciones o frases largas que no sean un ingrediente.
                            - No aceptes ingredientes inventados.
                            - Si no estas seguro de que sea comida, devuelve is_food = false.

                            Ejemplos:
                            - tomatte -> Tomate, Verdura
                            - sevollas -> Cebolla, Verdura
                            - arros -> Arroz, Cereal
                            - voqueron -> Boqueron, Pescado
                            - boquerones -> Boqueron, Pescado
                            - peskado -> Pescado, Pescado
                            - xampinon -> Champinon, Setas
                            - lomo se cerdi -> Lomo de cerdo, Carne
                            - pechuga pollo -> Pechuga de pollo, Carne
                            - echuga de avo -> Pechuga de pavo, Carne
                            - pexuga pavo -> Pechuga de pavo, Carne
                            - leche coco -> Leche de coco, Bebida
                            - martillo -> no valido
                            - cuchara -> no valido
                            - mesa -> no valido
                        ",
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Ingrediente escrito por el usuario: ' . $ingredientName,
                    ],
                ],

                'text' => [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => 'ingredient_validation',
                        'strict' => true,
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'is_food' => [
                                    'type' => 'boolean',
                                ],
                                'corrected_name' => [
                                    'type' => 'string',
                                ],
                                'category' => [
                                    'type' => 'string',
                                    'enum' => Ingredient::CATEGORIES,
                                ],
                                'reason' => [
                                    'type' => 'string',
                                ],
                            ],
                            'required' => [
                                'is_food',
                                'corrected_name',
                                'category',
                                'reason',
                            ],
                            'additionalProperties' => false,
                        ],
                    ],
                ],

                'max_output_tokens' => 200,
            ]);

        if (!$response->successful()) {
            return $this->invalid($this->openAiErrorMessage($response->json(), $response->status()));
        }

        $json = $response->json();

        $text = $json['output_text'] ?? $this->extractOutputText($json);

        if (!$text) {
            return $this->invalid('La IA no devolvio una respuesta valida.');
        }

        $data = json_decode($text, true);

        if (!is_array($data)) {
            return $this->invalid('La respuesta de la IA no era JSON valido.');
        }

        return [
            'is_food' => (bool) ($data['is_food'] ?? false),
            'corrected_name' => Str::title(trim($data['corrected_name'] ?? '')),
            'category' => $data['category'] ?? 'Verdura',
            'reason' => $data['reason'] ?? '',
        ];
    }

    private function extractOutputText(array $json): ?string
    {
        foreach ($json['output'] ?? [] as $output) {
            foreach ($output['content'] ?? [] as $content) {
                if (($content['type'] ?? null) === 'output_text') {
                    return $content['text'] ?? null;
                }
            }
        }

        return null;
    }

    private function invalid(string $reason): array
    {
        return [
            'is_food' => false,
            'corrected_name' => '',
            'category' => 'Verdura',
            'reason' => $reason,
        ];
    }

    private function openAiErrorMessage(?array $json, int $status): string
    {
        $code = $json['error']['code'] ?? null;

        return match ($code) {
            'insufficient_quota' => 'La API key de OpenAI no tiene cuota disponible. Revisa el plan, billing o saldo en la plataforma de OpenAI.',
            'invalid_api_key' => 'La API key de OpenAI no es valida.',
            default => "OpenAI devolvio un error {$status}.",
        };
    }
}
