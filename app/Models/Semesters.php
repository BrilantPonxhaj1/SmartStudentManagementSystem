<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\TenantScope;

class Semesters extends Model
{
    use HasFactory;

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
     * Apply the tenant scope so every query is automatically
     * filtered by the current user’s university.
     */
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

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
        return $this->hasMany(TuitionPayments::class);
    }
}
