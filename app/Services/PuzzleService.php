<?php
namespace App\Services;

use App\Enums\PuzzleStatus;
use App\Models\Puzzle;
use App\Models\Submission;
use App\Services\WordValidatorService;

class PuzzleService
{
    /**
     * @var WordValidatorService
     */
    protected WordValidatorService $validator;

    /**
     * Initialise the class
     *
     * @param WordValidatorService $validator
     */
    public function __construct(WordValidatorService $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Create a puzzle for the student
     *
     * @param integer $studentId
     * @return Puzzle
     */
    public function startPuzzle(int $studentId): Puzzle
    {
        $randomString = $this->generateRandomString(14);
        return Puzzle::create([
            'student_id' => $studentId,
            'original_string' => $randomString,
            'remaining_string' => $randomString,
            'status' => PuzzleStatus::ACTIVE,
        ]);
    }

    /**
     * Submit a word for a puzzle if it is valid english word and satisfies the remaining_string
     *
     * @param Puzzle $puzzle
     * @param string $word
     * @return array
     */
    public function submitWord(Puzzle $puzzle, string $word): array
    {
        if (!$puzzle->isActive()) {
            return ['valid' => false, 'message' => 'Puzzle has ended'];
        }

        if (!$this->validator->isValidEnglishWord($word)) {
            return ['valid' => false, 'message' => 'Invalid English word'];
        }

        if (!$this->validator->canConstructFromAvailable($word, $puzzle->remaining_string)) {
            return ['valid' => false, 'message' => 'Word cannot be constructed from available letters'];
        }

        // Score is length of the word
        $score = strlen($word);

        // Save submission
        Submission::create([
            'puzzle_id' => $puzzle->id,
            'word' => $word,
            'score' => $score,
        ]);

        // Update remaining string
        $updated = $this->consumeLetters($word, $puzzle->remaining_string);
        $puzzle->update(['remaining_string' => $updated]);

        return [
            'valid' => true,
            'score' => $score,
            'remaining_letters' => $updated,
        ];
    }

    /**
     * Mark the puzzle as completed
     *
     * @param Puzzle $puzzle
     * @return array
     */
    public function endPuzzle(Puzzle $puzzle): array
    {
        $puzzle->update(['status' => PuzzleStatus::COMPLETED]);

        $usedWords = $puzzle->submissions->pluck('word')->toArray();
        $available = $puzzle->remaining_string;

        $validRemaining = $this->validator->findValidWords($available, $usedWords);

        return [
            'final_score' => $puzzle->submissions()->sum('score'),
            'valid_words_remaining' => $validRemaining,
        ];
    }

    /**
     * Create the new remaining_string bt removing the consumed letters
     *
     * @param string $word
     * @param string $available
     * @return string
     */
    public function consumeLetters(string $word, string $available): string
    {
        $availableLetters = count_chars($available, 1);
        $wordLetters = count_chars($word, 1);

        foreach ($wordLetters as $ascii => $count) {
            if (isset($availableLetters[$ascii])) {
                $availableLetters[$ascii] -= $count;
                if ($availableLetters[$ascii] <= 0) {
                    unset($availableLetters[$ascii]);
                }
            }
        }

        // Convert back to string
        $newStr = '';
        foreach ($availableLetters as $ascii => $count) {
            $newStr .= str_repeat(chr($ascii), $count);
        }

        return $newStr;
    }

    /**
     * Generate a random string
     *
     * @param integer $length
     * @return string
     */
    private function generateRandomString($length = 14): string
    {
        $letters = 'abcdefghijklmnopqrstuvwxyz';
        return substr(str_shuffle(str_repeat($letters, ceil($length / strlen($letters)))), 0, $length);
    }
}
