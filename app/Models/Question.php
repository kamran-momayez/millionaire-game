<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['text', 'points'];

    /**
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * @return array<array-key, mixed>
     */
    public function getCorrectAnswersIds(): array
    {
        return self::answers()->where('is_correct', true)->pluck('id')->toArray();
    }

    /**
     * @param array<array-key, mixed> $correctAnswersIds
     * @return array<array-key, mixed>
     */
    public function getCorrectAnswersTexts(array $correctAnswersIds): array
    {
        return self::answers()->whereIn('id', $correctAnswersIds)->pluck('text')->toArray();
    }
}
