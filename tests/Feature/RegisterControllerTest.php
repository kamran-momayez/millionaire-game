<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function test_should_found_register_index_route()
    {
        $response = $this->get('/register');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('auth.register');
    }

    public function test_should_return_error_when_input_is_not_provided()
    {
        $response = $this->post('register');

        $response->assertSessionHasErrors(['name', 'surname', 'password']);
    }

    public function test_should_register_user_when_validation_passed()
    {
        $userData = [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'password' => $this->faker->password(8),
        ];

        $response = $this->post('register', $userData);

        $response->assertRedirect(route('game.index'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'surname' => $userData['surname'],
        ]);
    }
}
