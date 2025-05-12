<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\TenantScope;

class Semester extends Model
{
    use HasFactory;
    protected $table = 'semesters';
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'university_id',
        'name',
        'start_date',
        'end_date',
        'registration_deadline',
        'description',
        'max_courses',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'start_date'            => 'date',
        'end_date'              => 'date',
        'registration_deadline' => 'date',
    ];


    /**
     * The university that this semester belongs to.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * All course‐offering instances in this semester.
     */
    public function courseOfferings()
    {
        return $this->hasMany(CourseOffering::class);
    }

    /**
     * All course registration forms submitted for this semester.
     */
    public function courseForms()
    {
        return $this->hasMany(CourseForm::class);
    }

    /**
     * All tuition payments made in this semester.
     */
    public function tuitionPayments()
    {
        return $this->hasMany(TuitionPayment::class);
    }
}
