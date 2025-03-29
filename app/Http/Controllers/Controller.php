<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    public function getUser(): ?User
    {
        $user = auth()->user();
        if($user instanceof User) {
            return $user;
        }
        $userId = auth()->id();
        return User::query()->find($userId);
    }
}

