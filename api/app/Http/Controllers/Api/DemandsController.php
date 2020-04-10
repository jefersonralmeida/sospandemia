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
use Symfony\Component\Mime\DependencyInjection\AddMimeTypeGuesserPass;

class DemandsController extends Controller
{

    public function search(Request $request)
    {
        $query = $request->query('query');
        $districtId = $request->query('district_id');
        $stateId = $request->query('state_id');

        if (empty($query)) {
            return Demand::with('entity')->paginate();
        }

        $query = sanitizeSearch($query);

        $keys = Demand::search($query)->keys();

        $query = Demand::with('entity')->whereIn('demands.id', $keys);
        if ($districtId !== null) {
            $query
                ->join('entities', 'entities.id', '=', 'demands.entity_id')
                ->where('entities.district_id', '=', $districtId);
        }

        // se passar district_id, ignora o state_id
        if ($districtId === null && $stateId !== null) {
            $query
                ->join('entities', 'entities.id', '=', 'demands.entity_id')
                ->join('districts', 'districts.id', '=', 'entities.district_id')
                ->where('districts.state_id', '=', $stateId);
        }

        $result = $query->paginate();
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
