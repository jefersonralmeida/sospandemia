<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Class Entity
 * @package App\Models
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
}
