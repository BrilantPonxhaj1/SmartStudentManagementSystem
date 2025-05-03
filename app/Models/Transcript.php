<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transcript extends Model
{
    use HasFactory;

    protected $table = 'transcripts';

    protected $fillable = [
        'university_id',
        'department_id',
        'student_profile_id',
        'issued_date',
        'cumulative_gpa',
        'details',
        'status',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'cumulative_gpa' => 'decimal:2',
    ];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }
}
