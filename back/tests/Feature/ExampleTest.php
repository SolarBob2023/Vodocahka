<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_rates_index()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->get('/api/admin/rates');
        $response->assertStatus(200);
    }

    public function test_rates_store()
    {
        $user = User::find(1);
        Sanctum::actingAs($user);
        $response = $this->post('/api/admin/rates');
        $response->assertStatus(200);
    }
}
