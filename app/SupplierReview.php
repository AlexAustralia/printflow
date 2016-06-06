<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierReview extends Model
{
    protected $fillable = array('*');

    // Dates Mutators
    public function getDates()
    {
        return ['date_visited'];
    }

}
