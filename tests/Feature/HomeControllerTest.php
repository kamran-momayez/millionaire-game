<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function test_should_found_home_route()
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('home');
    }
}
