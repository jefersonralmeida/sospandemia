<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Entity
 * @mixin Eloquent
 * @package App\Models
 * @property Entity $entity
 */
class Demand extends Model
{
    protected $fillable = ['title', 'text', 'quantity', 'entity_id'];
    protected $hidden = ['entity_id'];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
