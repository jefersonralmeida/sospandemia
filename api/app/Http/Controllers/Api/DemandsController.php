<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDemandRequest;
use App\Models\Demand;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DemandsController extends Controller
{

    public function search(Request $request)
    {
        $query = $request->query('query');

        if (empty($query)) {
            return Demand::with('entity')->paginate();
        }

        $result = Demand::search($query)->paginate();
        $result->load('entity');
        return $result;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function index()
    {
        return Demand::paginate();
    }

    /**
     * @param Demand $demand
     * @return Demand
     */
    public function info(Demand $demand)
    {
        return $demand;
    }

    /**
     * @param Demand $demand
     * @param UpdateDemandRequest $request
     * @return Demand
     */
    public function update(Demand $demand, UpdateDemandRequest $request)
    {
        $demand->fill($request->validated());
        $demand->save();
        return $demand;
    }

    /**
     * @param Demand $demand
     * @return ResponseFactory|Response
     * @throws Exception
     */
    public function delete(Demand $demand)
    {
        $demand->delete();
        return response(null, 204); // return deleted
    }

}
