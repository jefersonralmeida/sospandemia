<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;

class ProfileController extends Controller
{
    public function info()
    {
        return Auth::user();
    }
}
