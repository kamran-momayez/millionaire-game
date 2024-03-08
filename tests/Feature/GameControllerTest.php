<?php

namespace Tests\Feature;

use App\Handlers\GameAnswerHandler;
use App\Http\Controllers\GameController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    private GameAnswerHandler|MockObject $gameAnswerHandlerMock;

    /**
     * @throws Exception
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->gameAnswerHandlerMock = $this->createMock(GameAnswerHandler::class);
    }

    public function test_should_found_game_root_route()
    {
        $response = $this->get('/game');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('game.index');
    }

    public function test_should_found_game_response_route()
    {
        $response = $this->post('/game/answer');

        $response->assertStatus(Response::HTTP_FOUND);
    }

    public function test_should_throw_exception_when_answer_input_is_not_provided()
    {
        $this->gameAnswerHandlerMock->expects($this->never())->method('handle');
        $gameController = new GameController($this->gameAnswerHandlerMock);

        $request = Request::create('/game/answer');

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The answers field is required.');
        $gameController->answer($request);
    }

    public function test_should_throw_exception_when_answer_input_is_not_array()
    {
        $this->gameAnswerHandlerMock->expects($this->never())->method('handle');
        $gameController = new GameController($this->gameAnswerHandlerMock);

        $answers = ['answers' => 'string'];
        $request = Request::create('/game/answer', 'POST', $answers);

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The answers field must be an array.');
        $gameController->answer($request);
    }

    public function test_should_return_view_when_validation_is_passed()
    {
        $this->gameAnswerHandlerMock->expects($this->once())->method('handle');
        $gameController = new GameController($this->gameAnswerHandlerMock);

        $answers = ['answers' => [1 => ['1']]];
        $request = Request::create('/game/answer', 'POST', $answers);

        $response = $gameController->answer($request);

        $this->assertInstanceOf(View::class, $response);
    }
}
