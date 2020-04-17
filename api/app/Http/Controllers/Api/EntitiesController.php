<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDemandRequest;
use App\Http\Requests\CreateEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Models\Demand;
use App\Models\Entity;
use App\Models\EntityTypes\EntityTypeContract;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function indexEntityTypes()
    {
        $types = config('entity_types');
        krsort($types);
        return array_map(fn(array $item) => $item['type'], $types);
    }

    public function typeSearch(Request $request, int $type)
    {
        $typeClass = config("entity_types.$type.class");
        if ($typeClass === null) {
            throw new NotFoundHttpException('Tipo de entidade inválido');
        }

        /** @var EntityTypeContract $entityClass */
        $entityType = app($typeClass);

        return $entityType->search($request->query->all());

    }

    public function createDemand(Entity $entity, CreateDemandRequest $request)
    {
        $data = array_merge($request->validated(), ['entity_id' => $entity->id]);
        Demand::create($data);
        return response(null, 201);
    }

    public function info(Entity $entity)
    {
        $entity->load('district');
        return $entity;
    }

    public function create(CreateEntityRequest $request)
    {
        $entity = Entity::create($request->validated());
        $entity->users()->attach(Auth::user()->id);
        return response(null, 201);
    }

    public function update(Entity $entity, UpdateEntityRequest $request)
    {
        $entity->fill($request->validated());
        $entity->save();
        return $entity;
    }

    public function invite(Entity $entity, User $invitee)
    {
        if ($entity->users->contains('id', $invitee->id)) {
            abort(400, 'Este usuário já é membro da entidade');
        }
        $entity->users()->attach($invitee->id);
        return response(null, 204); // no content
    }

    public function leave(Entity $entity)
    {
        if ($entity->users->count() === 1) {
            abort(400, 'Você é o último membro dessa entidade. Não pode sair.');
        }

        $entity->users()->detach(Auth::user()->id);
        return response(null, 204); // return no content
    }

}
