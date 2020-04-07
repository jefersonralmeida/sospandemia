<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;

class StatesController extends Controller
{

    public function index()
    {
        return State::all();
    }

    public function searchDistricts(State $state, Request $request)
    {

        $query = $request->query('query');

        if (empty($query) || strlen($query) < 4) {
            return [];
        }

        return District::search($query)->where('state_id', $state->id)->orderBy('municipio', 'distrito')->get();

    }

}
