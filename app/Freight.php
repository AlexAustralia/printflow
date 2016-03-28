<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Freight extends Model
{
    public function freight_items()
    {
        return $this->hasMany('App\FreightItem');
    }
}
