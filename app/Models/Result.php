<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Result extends Model
{
    use HasFactory;

    public static function saveResult($totalScore)
    {
        $result = new self();
        $result->user_id = Auth::user()->id;
        $result->score = $totalScore;
        $result->save();
    }
}
