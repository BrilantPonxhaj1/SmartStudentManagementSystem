<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'name',
        'code',
        'address',
    ];

    /**
     * A university has many departments.
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * A university has many users (students, professors, admins).
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * (Optional) If you scope subjects directly:
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
