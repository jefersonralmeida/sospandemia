<?php

namespace App\Models\Policies;

use App\Models\Demand;
use App\Models\Entity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class EntityPolicy
{
    use HandlesAuthorization;

    public function createDemand(User $user, Entity $entity)
    {
        return $user->entities->contains('id', $entity->id)
            ? Response::allow()
            : Response::deny('Você não é membro dessa entidade.');
    }

    public function update(User $user, Entity $entity)
    {
        return $user->entities->contains('id', $entity->id)
            ? Response::allow()
            : Response::deny('Você não é membro dessa entidade.');
    }

    public function invite(User $user, Entity $entity)
    {
        return $user->entities->contains('id', $entity->id)
            ? Response::allow()
            : Response::deny('Você não é membro dessa entidade.');
    }

    public function leave(User $user, Entity $entity)
    {
        return $user->entities->contains('id', $entity->id)
            ? Response::allow()
            : Response::deny('Você não é membro dessa entidade.');
    }

}
