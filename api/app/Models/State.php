<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class State
 * @mixin Eloquent
 * @package App\Models
 * @property int id
 * @property string uf
 * @property string name
 * @property District[]|Collection $districts
 */
class State extends Model
{

    public function districts()
    {
        return $this->hasMany(District::class);
    }

}
