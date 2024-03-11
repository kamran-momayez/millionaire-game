<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * @param Result $result
     * @return View
     */
    public function index(Result $result): View
    {
        $topScores = $result->getTopTenGames();

        return view('home')->with('topScores', $topScores);
    }
}
