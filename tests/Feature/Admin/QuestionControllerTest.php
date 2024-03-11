<?php

namespace Tests\Feature\Admin;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class QuestionControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();

        $adminUser = User::factory()->create([
            'name' => 'admin',
            'surname' => 'admin',
            'password' => 'password',
            'role' => 'ROLE_ADMIN'
        ]);
        Auth::login($adminUser);
    }

    public function test_should_find_questions_index_route()
    {
        $questions = Question::factory()->count(3)->create();
        $response = $this->get('/admin/questions');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('admin.questions.index');
        $response->assertViewHas('questions', $questions);
    }

    public function test_should_find_questions_create_route()
    {
        $response = $this->get('/admin/questions/create');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('admin.questions.create');
    }

    public function test_should_return_error_when_store_validation_fails()
    {
        $response = $this->post('/admin/questions');

        $response->assertSessionHasErrors(['text', 'points', 'answers']);
    }

    public function test_should_store_resource_when_validation_passes()
    {
        $data = [
            'text' => 'Sample Question',
            'points' => 10,
            'answers' => [
                ['text' => 'Answer 1', 'is_correct' => true],
                ['text' => 'Answer 2', 'is_correct' => false],
                ['text' => 'Answer 3'],
            ],
        ];

        $response = $this->post('/admin/questions', $data);

        $this->assertCount(1, Question::all());
        $this->assertCount(3, Answer::all());
        $this->assertDatabaseHas('questions', [
            'text' => 'Sample Question',
            'points' => 10,
        ]);
        $this->assertDatabaseHas('answers', [
            'text' => 'Answer 1',
            'is_correct' => true,
            'question_id' => 1
        ]);
        $this->assertDatabaseHas('answers', [
            'text' => 'Answer 2',
            'is_correct' => false,
            'question_id' => 1
        ]);
        $this->assertDatabaseHas('answers', [
            'text' => 'Answer 3',
            'is_correct' => false,
            'question_id' => 1
        ]);
        $response->assertStatus(Response::HTTP_FOUND);
        $response->assertRedirect(route('admin.questions.index'));
    }

    public function test_should_find_questions_edit_route()
    {
        $question = Question::factory()->create();
        $response = $this->get(route('admin.questions.edit', $question));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertViewIs('admin.questions.edit');
        $response->assertViewHas('question', $question);
    }

    public function test_should_return_error_when_update_validation_fails()
    {
        $question = Question::factory()->create();
        $response = $this->put(route('admin.questions.update', $question));

        $response->assertSessionHasErrors(['text', 'points', 'answers']);
    }

    public function test_should_update_resource_when_validation_passes()
    {
        $question = Question::factory()->create();
        $updatedText = 'Updated Question';

        $response = $this->put(route('admin.questions.update', $question), [
            'text' => $updatedText,
            'points' => 15,
            'answers' => [
                ['text' => 'Answer 1', 'is_correct' => true],
            ],
        ]);

        $this->assertDatabaseHas('questions', [
            'id' => $question->id,
            'text' => $updatedText,
            'points' => 15,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.questions.index'));
    }

    public function testDestroy()
    {
        $question = Question::factory()->create();

        $this->assertDatabaseHas('questions', [
            'id' => $question->id
        ]);

        $response = $this->delete(route('admin.questions.destroy', $question));

        $this->assertDatabaseMissing('questions', [
            'id' => $question->id
        ]);
        $response->assertStatus(302);
        $response->assertRedirect(route('admin.questions.index'));
    }

}
