<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Entity
 * @package App\Models
 */
class Demand extends Model
{

    protected $hidden = ['entity_id'];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
