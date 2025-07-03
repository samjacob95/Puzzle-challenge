<?php
namespace Tests\Feature;

use App\Models\Puzzle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PuzzleFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_start_and_submit_flow()
    {
        // Create student and start puzzle
        $response = $this->postJson('/api/puzzle/start', ['name' => 'TestUser']);
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
}
