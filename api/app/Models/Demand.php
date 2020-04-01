<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Class Entity
 * @mixin Eloquent
 * @package App\Models
 * @property int id
 * @property string title
 * @property string text
 * @property Entity $entity
 */
class Demand extends Model
{

    use Searchable;

    protected $fillable = ['title', 'text', 'quantity', 'entity_id'];
    protected $hidden = ['entity_id'];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'city' => $this->entity->city,
            'state' => $this->entity->state,
        ];
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
