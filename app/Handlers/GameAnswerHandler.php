<?php

namespace App\Handlers;

use App\Models\Question;
use App\Models\Result;
use Exception;
use Illuminate\Support\Facades\Auth;

class GameAnswerHandler
{
    private float $totalScore = 0;
    private array $feedback = [];

    /**
     * @return float
     */
    public function getTotalScore(): float
    {
        return $this->totalScore;
    }

    /**
     * @return array
     */
    public function getFeedback(): array
    {
        return $this->feedback;
    }

    /**
     * @param array $answers
     * @return void
     */
    public function handle(array $answers): void
    {
        $this->checkAnswer($answers);

        Result::create([
            'user_id' => Auth::user()->id,
            'score' => $this->totalScore
        ]);
    }

    /**
     * @param $answers
     * @return void
     */
    private function checkAnswer($answers): void
    {
        foreach ($answers as $questionId => $selectedAnswerIds) {
            $question = Question::findOrFail($questionId);
            $points = 0;

            try {
                $points = $this->getPoints($question, $selectedAnswerIds);
                $this->feedback[] = "Correct answer!";
            } catch (Exception $exception) {
                $this->feedback[] = $exception->getMessage();
            }

            $this->totalScore += $points;
        }
    }

    /**
     * @param Question $question
     * @param $selectedAnswerIds
     * @return float
     * @throws Exception
     */
    private function getPoints(Question $question, $selectedAnswerIds): float
    {
        $selectedAnswerIds = array_filter($selectedAnswerIds);
        if (empty($selectedAnswerIds))
            throw new Exception("Not answered!");

        $correctAnswersIds = $question->getCorrectAnswersIds();

        $correctAnswersDiff = count(array_diff($correctAnswersIds, $selectedAnswerIds));
        if ($correctAnswersDiff === 0) {
            return $question->points;
        }

        $wrongAnswersDiff = count(array_diff($selectedAnswerIds, $correctAnswersIds));
        if ($wrongAnswersDiff === 0) {
            $numCorrectAnswers = count($correctAnswersIds);
            // this condition is added in case of the seeder didn't add any correct answers,
            // and it's not needed in real life scenarios.
            if ($numCorrectAnswers) {
                $numSelectedCorrectAnswers = count($selectedAnswerIds);

                return ($question->points / $numCorrectAnswers) * $numSelectedCorrectAnswers;
            }
        }

        $correctAnswersText = $question->getCorrectAnswersTexts($correctAnswersIds);
        $correctAnswersText = implode(', ', $correctAnswersText);

        throw new Exception("Incorrect answer. The correct answers are: $correctAnswersText.");
    }
}
