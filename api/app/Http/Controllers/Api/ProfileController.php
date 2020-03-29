<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;

class ProfileController extends Controller
{
    public function info()
    {
        /** @var User $loggedUser */
        $loggedUser = Auth::user();
        $loggedUser->load('entities');
        return $loggedUser;
    }
}
