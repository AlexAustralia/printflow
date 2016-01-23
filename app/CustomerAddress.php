<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model {

	protected $fillable = array(
        'customer_id',
        'name',
        'address',
        'city',
        'state',
        'postcode',
        'country',
        'notes'
    );

}
