<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    public static function saveResult($totalScore)
    {
        $result = new self();
        $result->user_id = 1; // Auth will be added.
        $result->score = $totalScore;
        $result->save();
    }
}
