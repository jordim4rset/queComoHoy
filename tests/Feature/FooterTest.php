<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FooterTest extends TestCase
{
    use RefreshDatabase;

    public function test_footer_shows_existing_site_links(): void
    {
        $response = $this->get(route('index'));

        $response
            ->assertOk()
            ->assertSee('Que Cocino Hoy')
            ->assertSee('Inicio')
            ->assertSee(route('index'), false)
            ->assertSee('Buscar recetas')
            ->assertSee(route('recetas.search'), false)
            ->assertSee('Buscar usuario')
            ->assertSee(route('users.index'), false)
            ->assertSee('Eventos')
            ->assertSee(route('eventos.index'), false)
            ->assertSee('Ingredientes')
            ->assertSee(route('ingredientes.index'), false)
            ->assertSee('Tienda ChefPoints')
            ->assertSee(route('shop'), false);
    }

    public function test_privacy_and_cookie_policies_are_text_without_links(): void
    {
        $content = $this->get(route('index'))->getContent();

        $this->assertStringContainsString('Politica de privacidad', $content);
        $this->assertStringContainsString('Politica de cookies', $content);
        $this->assertStringNotContainsString('<a href="#">Politica de privacidad</a>', $content);
        $this->assertStringNotContainsString('<a href="#">Politica de cookies</a>', $content);
        $this->assertStringContainsString('<span>Politica de privacidad</span>', $content);
        $this->assertStringContainsString('<span>Politica de cookies</span>', $content);
    }
}
