<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_policy_page_renders_default_content(): void
    {
        $response = $this->get(route('legal.privacy'));

        $response
            ->assertOk()
            ->assertSee('Politica de privacidad')
            ->assertSee('Datos que podemos tratar')
            ->assertSee('Contenido pendiente');
    }

    public function test_cookie_policy_page_renders_default_content(): void
    {
        $response = $this->get(route('legal.cookies'));

        $response
            ->assertOk()
            ->assertSee('Politica de cookies')
            ->assertSee('Cookies necesarias')
            ->assertSee('Gestion');
    }
}
