<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Demand;
use App\Models\Entity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class DemandsController extends Controller
{

    /**
     * Show the application dashboard.
     * @param Entity $entity
     * @return LengthAwarePaginator
     */
    public function index(Entity $entity)
    {
        return $entity->demands()->paginate();
    }

    public function info(Demand $demand)
    {
        return $demand;
    }

    public function create()
    {

    }

}
