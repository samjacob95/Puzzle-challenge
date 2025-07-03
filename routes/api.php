<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PuzzleController;

// Puzzle Routes
Route::prefix('puzzle')->group(function () {
    // Start a new puzzle
    Route::post('/start', [PuzzleController::class, 'start']);

    // Submit a word for a given puzzle
    Route::post('/{puzzle}/submit', [PuzzleController::class, 'submit']);

    // Get current puzzle status
    Route::get('/{puzzle}/status', [PuzzleController::class, 'status']);

    // End the puzzle session
    Route::post('/{puzzle}/end', [PuzzleController::class, 'end']);
});

// Leaderboard Route
Route::get('/leaderboard', [PuzzleController::class, 'leaderboard']);
