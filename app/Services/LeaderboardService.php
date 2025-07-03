<?php
namespace App\Services;

use App\Models\LeaderboardEntry;

class LeaderboardService
{
    /**
     * Insert the new word if its score sits in the top 10
     *
     * @param string $word
     * @param integer $score
     * @param integer $studentId
     * @return void
     */
    public function update(string $word, int $score, int $studentId): void
    {
        $word = strtolower($word);

        // Skip if word already in leaderboard
        if (LeaderboardEntry::where('word', $word)->exists()) {
            return;
        }

        // If less than 10, insert
        if (LeaderboardEntry::count() < 10) {
            LeaderboardEntry::create([
                'word' => $word,
                'score' => $score,
                'student_id' => $studentId,
            ]);
            return;
        }

        // Get current lowest scoring entry
        $lowest = LeaderboardEntry::orderBy('score', 'asc')->first();

        if ($score > $lowest->score) {
            $lowest->update([
                'word' => $word,
                'score' => $score,
                'student_id' => $studentId,
            ]);
        }
    }

    /**
     * Get top 10 entries sorted descendingly based on score
     *
     * @return LeaderboardEntry
     */
    public function getTopEntries()
    {
        return LeaderboardEntry::with('student')
            ->orderByDesc('score')
            ->limit(10)
            ->get(['word', 'score', 'student_id', 'updated_at']);
    }
}
