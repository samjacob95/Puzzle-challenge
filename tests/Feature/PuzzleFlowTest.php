<?php
namespace Tests\Feature;

use App\Enums\PuzzleStatus;
use App\Models\Puzzle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PuzzleFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_and_submit_flow()
    {
        // Create student and start puzzle
        $response = $this->postJson('/api/puzzle/start', ['name' => 'Chloe']);
        $response->assertStatus(200);
        $puzzle = $response->json('puzzle');

        // Manually set known string for test
        Puzzle::find($puzzle['id'])->update(['original_string' => 'foxfoxfox', 'remaining_string' => 'foxfoxfox']);

        // Submit valid word
        $submit = $this->postJson("/api/puzzle/{$puzzle['id']}/submit", ['word' => 'fox']);
        $submit->assertStatus(200)
               ->assertJson([
                   'valid' => true,
                   'score' => 3,
               ]);

        // Submit invalid word
        $submitBad = $this->postJson("/api/puzzle/{$puzzle['id']}/submit", ['word' => 'qwerty']);
        $submitBad->assertJson([
            'valid' => false
        ]);

        // End puzzle
        $end = $this->postJson("/api/puzzle/{$puzzle['id']}/end");
        $end->assertStatus(200)
            ->assertJsonStructure([
                'final_score',
                'valid_words_remaining'
            ]);
    }

    public function test_puzzle_auto_ends_when_letters_run_out()
    {
        // Start puzzle with known letters
        $response = $this->postJson('/api/puzzle/start', ['name' => 'James']);
        $puzzle = $response->json('puzzle');

        // Manually reduce remaining_string for testing
        Puzzle::find($puzzle['id'])->update([
            'original_string' => 'fox',
            'remaining_string' => 'fox'
        ]);

        // Submit 'fox' (uses all letters)
        $submit = $this->postJson("/api/puzzle/{$puzzle['id']}/submit", ['word' => 'fox']);
        $submit->assertStatus(200)
            ->assertJson([
                'valid' => true,
                'remaining_letters' => ''
            ]);

        // Reload puzzle from DB
        $puzzleFresh = Puzzle::find($puzzle['id']);

        // Assert status is 'ended' or still active (based on business rule)
        $this->assertEquals('', $puzzleFresh->remaining_string);

        // Optional: If your logic automatically ends puzzle on empty string
        // You can add this assertion
        $this->assertEquals(PuzzleStatus::COMPLETED->value, $puzzleFresh->status);
    }
}
