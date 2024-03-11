<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_found_login_index_route()
    {
        $response = $this->get('/login');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('auth.login');
    }

    public function test_should_return_error_when_credentials_are_invalid()
    {
        $response = $this->post(route('login'), [
            'surname' => 'invalid',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['surname' => 'Invalid credentials']);
        $this->assertGuest();
    }

    public function test_should_login_when_credentials_are_valid()
    {
        $user = User::factory()->create([
            'surname' => 'surname',
            'password' => 'password',
        ]);

        $response = $this->post('/login', [
            'surname' => 'surname',
            'password' => 'password',
        ]);

        $response->assertRedirect('/game');
        $this->assertAuthenticatedAs($user);
    }

    public function test_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
