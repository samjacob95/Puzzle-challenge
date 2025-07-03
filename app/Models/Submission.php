<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'puzzle_id', 'word', 'score'
    ];

    /**
     * Get assoicated puzzle
     *
     * @return belongsTo
     */
    public function puzzle()
    {
        return $this->belongsTo(Puzzle::class);
    }
}
