<?php

namespace Tests\Feature;

use App\Http\Controllers\HomeController;
use App\Models\Result;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_should_found_home_route()
    {
        $response = $this->get('/');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('home');
    }


    public function test_should_run_getTopTenGames_and_find_topScores()
    {
        $mockResult = $this->createMock(Result::class);
        $mockResult->expects($this->once())->method('getTopTenGames');

        $controller = new HomeController();
        $response = $controller->index($mockResult);

        $this->assertArrayHasKey('topScores', $response->getData());
    }
}
