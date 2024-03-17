<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $questions = Question::all();
        return view('admin.questions.index', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $this->validateQuestion($request);
        $question = Question::create($validatedData);
        $this->createAnswers($request, $question);

        return redirect()->route('admin.questions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.questions.create');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Question $question
     * @return View
     */
    public function edit(Question $question): View
    {
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Question $question
     * @return RedirectResponse
     */
    public function update(Request $request, Question $question): RedirectResponse
    {
        $validatedData = $this->validateQuestion($request);
        $question->update($validatedData);
        $question->answers()->delete();
        $this->createAnswers($request, $question);

        return redirect()->route('admin.questions.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Question $question
     * @return RedirectResponse
     */
    public function destroy(Question $question): RedirectResponse
    {
        $question->delete();

        return redirect()->route('admin.questions.index');
    }

    /**
     * Validate question data from the request.
     *
     * @param Request $request
     * @return array<mixed>
     */
    private function validateQuestion(Request $request): array
    {
        return $request->validate([
            'text' => 'required',
            'points' => 'required|integer|min:5|max:20',
            'answers' => 'required|array|min:1',
            'answers.*.text' => 'required',
        ]);
    }

    /**
     * Create answers for the question.
     *
     * @param Request $request
     * @param Question $question
     * @return void
     */
    private function createAnswers(Request $request, Question $question): void
    {
        /** @var array<mixed> $answer */
        foreach ($request->input('answers') as $answer) { /** @phpstan-ignore-line */
            $answer['is_correct'] = $answer['is_correct'] ?? 0;
            $question->answers()->create($answer);
        }
    }
}
