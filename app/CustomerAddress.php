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

    public function customer_name()
    {
        return $this->hasOne('App\Customer', 'id', 'customer_id');
    }
}
