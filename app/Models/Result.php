<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Result extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'score'];

    /**
     * @return Collection
     */
    public function getTopTenGames(): Collection
    {
        return Result::select('name', DB::raw('MAX(score) as score'))
            ->join('users', 'user_id', '=', 'users.id')
            ->groupBy('user_id', 'name')
            ->limit(10)
            ->orderBy('score', 'desc')
            ->get();
    }
}
