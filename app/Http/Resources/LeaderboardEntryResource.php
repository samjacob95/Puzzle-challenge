<?php
namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardEntryResource extends JsonResource
{
    /**
     * Format the response structure
     *
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'word' => $this->word,
            'score' => $this->score,
            'student_name' => $this->student->name,
            'submitted_on' => Carbon::parse($this->updated_at)->format('Y-m-d')
        ];
    }
}
