<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeachingAssignment extends Model
{
    protected $fillable = [
        'course_offering_id',
        'professor_profile_id',
        'role',
        'hours_per_week',
        'office_hours',
    ];

    public function courseOffering()
    {
        return $this->belongsTo(CourseOffering::class);
    }

    public function professor()
    {
        return $this->belongsTo(Professor::class, 'professor_profile_id');
    }
}
