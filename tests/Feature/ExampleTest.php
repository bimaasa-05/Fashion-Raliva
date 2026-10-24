<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee(__('Curated fashion for the modern minimalist. Discover the art of everyday dressing.'))
            ->assertSee(route('landing'), false)
            ->assertSee(__('landing.nav.register_owner'))
            ->assertDontSee('id="daftar"', false);
    }
}
