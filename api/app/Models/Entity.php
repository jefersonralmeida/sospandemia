<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Entity
 * @mixin Eloquent
 * @package App\Models
 * @property int id
 * @property string city
 * @property string state
 * @property Collection|User[] $users
 * @property Collection|Demand[] $demands
 */
class Entity extends Model
{

    protected $fillable = ['cnpj', 'name', 'legal_name', 'description', 'street_address', 'city', 'state'];

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
