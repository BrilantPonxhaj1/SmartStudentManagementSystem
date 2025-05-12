<?php

namespace App\Models;

use App\Models\Traits\TenantScoped;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Scopes\TenantScope;

class Literature extends Model
{
    use HasFactory,TenantScoped;

    /**
     * The table associated with the model.
     * (migration created a singular 'literature' table)
     */
    protected $table = 'literature';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'university_id',
        'department_id',
        'subject_id',
        'title',
        'type',
        'author',
        'link_or_reference',
    ];

    /**
     * Apply tenant scope so queries automatically filter by
     * the current user’s university (and department).
     */
    protected static function booted()
    {
        static::addGlobalScope(new TenantScope);
    }

    /**
     * The university that this literature belongs to.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * The department that this literature belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * The subject that this literature is associated with.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
