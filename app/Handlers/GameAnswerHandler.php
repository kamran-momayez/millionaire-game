<?php

namespace App\Handlers;

use App\Models\Question;
use App\Models\Result;
use Exception;

class GameAnswerHandler
{
    private array $answers;
    private float $totalScore = 0;
    private array $feedback = [];

    public function __construct(array $answers)
    {
        $this->answers = $answers;
    }

    public function handle(): void
    {
        $this->checkAnswer();
        Result::saveResult($this->totalScore);
    }

    private function checkAnswer(): void
    {
        foreach ($this->answers as $questionId => $selectedAnswerIds) {
            $question = Question::findOrFail($questionId);
            $points = 0;

            try {
                $points = $this->getPoints($question, $selectedAnswerIds);
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


    public function getTotalScore(): float
    {
        return $this->totalScore;
    }

    public function getFeedback(): array
    {
        return $this->feedback;
    }
}
