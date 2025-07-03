<?php
namespace App\Models;

use App\Enums\PuzzleStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Puzzle extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'original_string', 'remaining_string', 'status'
    ];

    /**
     * Get associated student details
     *
     * @return belongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get submitted words
     *
     * @return hasMany
     */
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    /**
     * Check if puzzle has active status
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->status === PuzzleStatus::ACTIVE->value;
    }
}