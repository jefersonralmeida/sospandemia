<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Auth;

class AuthController extends Controller
{

    public function logout()
    {
        Auth::user()->token()->revoke();
        return response()->noContent(204);
    }

}
