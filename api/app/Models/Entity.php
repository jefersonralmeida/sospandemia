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
 * @property string name
 * @property Collection|User[] $users
 * @property Collection|Demand[] $demands
 * @property District $district
 */
class Entity extends Model
{

    protected $fillable = ['cnpj', 'name', 'legal_name', 'description', 'street_address', 'district_id'];

    protected $hidden = ['district_id', 'district'];

    protected $appends = ['city'];


    public function getCityAttribute()
    {
        return "{$this->district->name} - {$this->district->uf}";
    }

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

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
