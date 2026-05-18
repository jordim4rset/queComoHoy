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
}
