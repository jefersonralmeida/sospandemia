<?php

namespace App\Models\Policies;

use App\Models\Demand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class DemandPolicy
{
    use HandlesAuthorization;

    protected function change(User $user, Demand $demand): Response
    {
        return $user->entities->contains('id', $demand->entity->id)
            ? Response::allow()
            : Response::deny('Você não é membro da entidade dessa demanda');
    }

    public function update(User $user, Demand $demand): Response
    {
        return $this->change($user, $demand);
    }

    public function delete(User $user, Demand $demand): Response
    {
        return $this->change($user, $demand);
    }
}
