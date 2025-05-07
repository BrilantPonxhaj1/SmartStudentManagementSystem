<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'university_id',
        'department_id',
        'code',
        'name',
        'description',
        'credits',
        'type',
    ];

    /**
     * Apply the tenant scope so every query is automatically
     * filtered by the current user’s university (and department).
     */
    protected static function booted(): void
    {
        static::addGlobalScope(new TenantScope);
    }

    /**
     * The university that offers this subject.
     */
    public function university(): BelongsTo
    {
        return $this->belongsTo(University::class);
    }

    /**
     * The department that owns this subject.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * All course‐offering instances of this subject (each semester/section).
     */
    public function courseOfferings(): HasMany
    {
        return $this->hasMany(CourseOffering::class);
    }

    /**
     * All literature/resources linked to this subject.
     */
    public function literature(): HasMany
    {
        return $this->hasMany(Literature::class);
    }

    /**
     * All enrollments in this subject, via its offerings.
     */
    public function enrollments(): HasManyThrough
    {
        return $this->hasManyThrough(
            Enrollment::class,
            CourseOffering::class,
            'subject_id',         // Foreign key on course_offerings
            'course_offering_id', // Foreign key on enrollments
            'id',                 // Local key on subjects
            'id'                  // Local key on course_offerings
        );
    }
}
