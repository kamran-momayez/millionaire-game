<?php

namespace Tests\Unit;

use App\Handlers\GameAnswerHandler;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class GameAnswerHandlerTest extends TestCase
{
    use DatabaseMigrations;

    private GameAnswerHandler $handler;

    public function setUp(): void
    {
        parent::setUp();

        User::factory()->create();
        $question = Question::factory()->create(['text' => 'q1', 'points' => 10]);

        $correctAnswersData = [
            ['text' => 'a1', 'is_correct' => true],
            ['text' => 'a2', 'is_correct' => true],
            ['text' => 'a3', 'is_correct' => false],
        ];
        $question->answers()->createMany($correctAnswersData);

        $this->handler = new GameAnswerHandler();
    }

    public function test_should_throw_exception_when_question_is_not_found()
    {
        $answers = [
            2 => [1, 2]
        ];

        $this->expectException(ModelNotFoundException::class);
        $this->handler->handle($answers);
    }

    public function test_should_get_full_score_on_correct_answer()
    {
        $answers = [
            1 => [1, 2]
        ];

        $this->handler->handle($answers);
        $this->assertEquals(10, $this->handler->getTotalScore());
        $this->assertEquals(["Correct answer!"], $this->handler->getFeedback());
        $this->assertDatabaseHas('results', ['score' => 10]);
    }

    public function test_should_get_partial_score_on_partial_correct_answer()
    {
        $answers = [
            1 => [1]
        ];

        $this->handler->handle($answers);
        $this->assertEquals(5, $this->handler->getTotalScore());
        $this->assertEquals(["Correct answer!"], $this->handler->getFeedback());
        $this->assertDatabaseHas('results', ['score' => 5]);
    }

    public function test_should_not_get_any_score_on_incorrect_answer()
    {
        $answers = [
            1 => [1, 3]
        ];

        $this->handler->handle($answers);
        $this->assertEquals(0, $this->handler->getTotalScore());
        $this->assertEquals(["Incorrect answer. The correct answers are: a1, a2."], $this->handler->getFeedback());
        $this->assertDatabaseHas('results', ['score' => 0]);
    }
}
