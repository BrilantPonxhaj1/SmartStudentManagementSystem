<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TuitionPayments extends Model
{
    use HasFactory;

    protected $table = 'tuition_payments';

    protected $fillable = [
        'university_id',
        'department_id',
        'student_profile_id',
        'semester_id',
        'amount',
        'payment_date',
        'method',
        'status',
        'receipt_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
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
        return $this->belongsTo(Student::class);
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
