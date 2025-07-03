<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    /**
     * Get all puzzles of the student
     *
     * @return hasMany
     */
    public function puzzles()
    {
        return $this->hasMany(Puzzle::class);
    }

    /**
     * Get leaderboard entries of the user
     *
     * @return hasMany
     */
    public function leaderboardEntries()
    {
        return $this->hasMany(LeaderboardEntry::class);
    }
}
