<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    public function getUser(): Authenticatable
    {

        return auth()->user();
    }
}

