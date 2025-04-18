<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';

    protected $primaryKey = 'user_id';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'role',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
