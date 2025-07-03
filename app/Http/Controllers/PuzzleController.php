<?php
namespace App\Http\Controllers;

use App\Http\Requests\SaveStudentRequest;
use App\Http\Requests\SubmitWordRequest;
use App\Http\Resources\LeaderboardEntryResource;
use App\Models\Puzzle;
use App\Models\Student;
use App\Services\PuzzleService;
use App\Services\LeaderboardService;

class PuzzleController extends Controller
{
    /**
     * @var PuzzleService
     */
    protected PuzzleService $puzzleService;
    /**
     * @var LeaderboardService
     */
    protected LeaderboardService $leaderboardService;

    /**
     * Initialise the class
     *
     * @param PuzzleService $puzzleService
     * @param LeaderboardService $leaderboardService
     */
    public function __construct(PuzzleService $puzzleService, LeaderboardService $leaderboardService)
    {
        $this->puzzleService = $puzzleService;
        $this->leaderboardService = $leaderboardService;
    }

    /**
     * Start a puzzle for a student
     *
     * @param SaveStudentRequest $request
     * @return json
     */
    public function start(SaveStudentRequest $request)
    {
        $student = Student::firstOrCreate(['name' => $request->input('name')]);
        $puzzle = $this->puzzleService->startPuzzle($student->id);

        return response()->json(['puzzle' => $puzzle]);
    }

    /**
     * Submit a word against a puzzle
     *
     * @param SubmitWordRequest $request
     * @param Puzzle $puzzle
     * @return json
     */
    public function submit(SubmitWordRequest $request, Puzzle $puzzle)
    {
        $word = $request->input('word');
        $result = $this->puzzleService->submitWord($puzzle, $word);

        if ($result['valid']) {
            $this->leaderboardService->update($word, $result['score'], $puzzle->student_id);
        }

        return response()->json($result);
    }

    /**
     * End a puzzle
     *
     * @param Puzzle $puzzle
     * @return json
     */
    public function end(Puzzle $puzzle)
    {
        $result = $this->puzzleService->endPuzzle($puzzle);

        return response()->json($result);
    }

    /**
     * Fetch the status of a puzzle
     *
     * @param Puzzle $puzzle
     * @return json
     */
    public function status(Puzzle $puzzle)
    {
        return response()->json([
            'remaining_letters' => $puzzle->remaining_string,
            'score' => $puzzle->submissions()->sum('score'),
        ]);
    }

    /**
     * Fetch the top 10 list of submitted words
     *
     * @return json
     */
    public function leaderboard()
    {
        $entries = $this->leaderboardService->getTopEntries();

        return LeaderboardEntryResource::collection($entries);
    }
}
