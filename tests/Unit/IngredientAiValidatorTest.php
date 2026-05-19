<?php

namespace Tests\Unit;

use App\Services\IngredientAiValidator;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IngredientAiValidatorTest extends TestCase
{
    public function test_it_uses_the_default_model_when_configured_model_is_empty(): void
    {
        config([
            'services.openai.key' => 'test-key',
            'services.openai.model' => '',
        ]);

        Http::fake([
            'api.openai.com/*' => Http::response([
                'output_text' => json_encode([
                    'is_food' => true,
                    'corrected_name' => 'Tomate',
                    'category' => 'Verdura',
                    'reason' => '',
                ]),
            ]),
        ]);

        (new IngredientAiValidator())->validate('tomatte');

        Http::assertSent(function (Request $request) {
            return $request['model'] === 'gpt-4o-mini';
        });
    }

    public function test_the_prompt_includes_common_spanish_typo_corrections(): void
    {
        config([
            'services.openai.key' => 'test-key',
            'services.openai.model' => 'gpt-4o-mini',
        ]);

        Http::fake([
            'api.openai.com/*' => Http::response([
                'output_text' => json_encode([
                    'is_food' => true,
                    'corrected_name' => 'Boqueron',
                    'category' => 'Pescado',
                    'reason' => '',
                ]),
            ]),
        ]);

        (new IngredientAiValidator())->validate('voqueron');

        Http::assertSent(function (Request $request) {
            $systemPrompt = $request['input'][0]['content'] ?? '';

            return str_contains($systemPrompt, 'b/v')
                && str_contains($systemPrompt, 'voqueron -> Boqueron');
        });
    }
}
