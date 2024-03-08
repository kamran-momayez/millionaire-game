<?php

namespace App\Http\Controllers;

use App\Handlers\GameAnswerHandler;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    /**
     * @param GameAnswerHandler $gameAnswerHandler
     */
    public function __construct(
        private readonly GameAnswerHandler $gameAnswerHandler
    ) {
    }

    /**
     * @return View
     */
    public function index(): view
    {
        $questions = Question::inRandomOrder()->limit(5)->get();

        return view('game.index', compact('questions'));
    }

    /**
     * @param Request $request
     * @return View
     */
    public function answer(Request $request): view
    {
        $request->validate([
            'answers' => 'required|array',
        ]);

        $this->gameAnswerHandler->handle($request->input('answers', []));

        return view('game.result', [
            'totalScore' => $this->gameAnswerHandler->getTotalScore(),
            'feedback' => $this->gameAnswerHandler->getFeedback()
        ]);
    }
}
