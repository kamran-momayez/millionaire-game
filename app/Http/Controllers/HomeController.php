<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $topScores = Result::getTopTenGames();

        return view('home')->with('topScores', $topScores);
    }
}
