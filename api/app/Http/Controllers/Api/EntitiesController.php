<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDemandRequest;
use App\Http\Requests\UpdateDemandRequest;
use App\Models\Demand;
use App\Models\Entity;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EntitiesController extends Controller
{

    /**
     * Show the application dashboard.
     * @param Entity $entity
     * @return LengthAwarePaginator
     */
    public function indexDemands(Entity $entity)
    {
        return $entity->demands()->paginate();
    }

    public function createDemand(Entity $entity, CreateDemandRequest $request)
    {
        $data = array_merge($request->validated(), ['entity_id' => $entity->id]);
        Demand::create($data);
        return response(null, 201);
    }

}
