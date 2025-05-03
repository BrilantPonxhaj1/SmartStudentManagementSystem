<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $table = 'scholarships';

    protected $fillable = [
        'university_id',
        'department_id',
        'student_profile_id',
        'scholarship_name',
        'amount',
        'status',
        'awarded_date',
        'duration',
        'renewable',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'awarded_date' => 'date',
        'renewable' => 'boolean',
    ];

    // Relationships
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
