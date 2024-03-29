<?php

namespace Tests\Feature;

use App\Handlers\GameAnswerHandler;
use App\Http\Controllers\GameController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class GameControllerTest extends TestCase
{
    use RefreshDatabase;

    private GameAnswerHandler|MockObject $gameAnswerHandlerMock;


    public function __construct(string $name)
    {
        parent::__construct($name);

        $this->gameAnswerHandlerMock = $this->createMock(GameAnswerHandler::class);
    }

    public function test_should_redirect_to_login_when_user_is_not_authenticated()
    {
        $response = $this->get('/game');

        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect('/login');
    }

    public function test_should_found_game_root_route_when_user_is_authenticated()
    {
        $user = User::factory()->create([
            'surname' => 'surname',
            'password' => 'password',
        ]);

        Auth::login($user);

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
