<?php

namespace App\Processors\AdminProcessors;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class AdminProcessor
{
    public function getStudents(): Collection
    {
        return User::query()->where('type', 'student')->get();
    }
}
