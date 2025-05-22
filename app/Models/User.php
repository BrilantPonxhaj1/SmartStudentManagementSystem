<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    // Allow mass assignment for these attributes.
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'type',
        'university_id',
        'department_id',
    ];

    // Hide sensitive attributes when serializing.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function studentProfile()
    {
        return $this->hasOne(Student::class);
    }

    public function professorProfile()
    {
        return $this->hasOne(Professor::class);
    }


    public function getPassword(): ?string
    {
        return $this->attributes['password'] ?? null;
    }

    public function getProfileAttribute()
    {
        return $this->studentProfile
            ?? $this->professorProfile
            ?? null;
    }


    public function getUniversityIdAttribute(): ?int
    {
        return $this->profile?->university_id;
    }

    public function getDepartmentIdAttribute(): ?int
    {
        return $this->profile?->department_id;
    }

    public function getType(): ?string
    {
        return $this->attributes['type'] ?? null;
    }
}
