<?php
namespace Tests\Unit;

use App\Services\PuzzleService;
use App\Services\WordValidatorService;
use PHPUnit\Framework\TestCase;

class PuzzleServiceTest extends TestCase
{
    public function test_consume_letters()
    {
        $validator = $this->createMock(WordValidatorService::class);
        $service = new PuzzleService($validator);

        $available = 'dgeftoikbvxuaa';
        $word = 'fox';

        $result = $service->consumeLetters($word, $available);

        $this->assertFalse(str_contains($result, 'f'));
        $this->assertFalse(str_contains($result, 'o'));
        $this->assertFalse(str_contains($result, 'x'));

        $this->assertEquals(strlen($available) - strlen($word), strlen($result));
    }
}
