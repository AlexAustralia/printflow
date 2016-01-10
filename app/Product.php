<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = array(
        'name',
        'description',
        'supplier_id',
        'length',
        'height',
        'width',
        'diameter',
        'material',
        'print_options',
        'minimum_order_quantity',
        'sample_available',
        'production_lead_time',
        'unit_price_from',
        'unit_price_to'
    );

    // Get Supplier's Name
    public function supplier()
    {
        return $this->hasOne('App\Supplier', 'id', 'supplier_id');
    }
}
