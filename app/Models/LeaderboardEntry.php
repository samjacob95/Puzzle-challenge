<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaderboardEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'word', 'score', 'student_id'
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
}