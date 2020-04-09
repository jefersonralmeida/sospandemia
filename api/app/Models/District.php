<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Class Entity
 *
 * @mixin Eloquent
 * @package App\Models
 * @property int id
 * @property string name
 * @property string uf
 * @property string municipio
 * @property string|null distrito
 * @property int state_id
 * @property State state
 * @property Entity[]|Collection entities
 */
class District extends Model
{

    use Searchable;

    protected $hidden = ['state_id', 'state'];
    protected $appends = ['name', 'uf'];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'municipio' => $this->municipio,
            'distrito' => $this->distrito,
            'state_id' => $this->state_id,
        ];
    }

    public function getNameAttribute()
    {
        return $this->distrito === null ? $this->municipio : "$this->municipio ($this->distrito)";
    }

    public function getUfAttribute()
    {
        return $this->state->uf;
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function entities()
    {
        return $this->hasMany(Entity::class);
    }
}
