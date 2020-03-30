<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Entity
 * @package App\Models
 * @property int id
 * @property Collection|Demand[] $demands
 */
class Entity extends Model
{

    /**
     * Users NxM relationship
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function demands()
    {
        return $this->hasMany(Demand::class);
    }
}
