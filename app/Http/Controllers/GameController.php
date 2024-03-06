<?php

namespace App\Http\Controllers;

use App\Handlers\GameAnswerHandler;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GameController extends Controller
{
    public function index(): view
    {
        $questions = Question::inRandomOrder()->limit(5)->get();

        return view('game.index', compact('questions'));
    }

    public function answer(Request $request): view
    {
        $handler = new GameAnswerHandler($request->input('answers', []));
        $handler->handle();

        return view('game.result', [
            'totalScore' => $handler->getTotalScore(),
            'feedback' => $handler->getFeedback()
        ]);
    }
}
