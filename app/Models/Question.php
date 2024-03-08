<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    use HasFactory;

    /**
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * @return array
     */
    public function getCorrectAnswersIds(): array
    {
        return self::answers()->where('is_correct', true)->pluck('id')->toArray();
    }

    /**
     * @param $correctAnswersIds
     * @return array
     */
    public function getCorrectAnswersTexts($correctAnswersIds): array
    {
        return self::answers()->whereIn('id', $correctAnswersIds)->pluck('text')->toArray();
    }
}
