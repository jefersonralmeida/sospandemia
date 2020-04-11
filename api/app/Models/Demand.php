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
        $this->load('entity');
        $this->load('entity.district');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'text' => $this->text,
            'entity' => $this->entity->name,
        ];
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
