<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

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
            'surname' => 'test-user',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'surname' => 'test-user',
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
