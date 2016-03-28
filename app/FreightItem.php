<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreightItem extends Model
{
    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }
}
