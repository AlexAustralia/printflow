<?php namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Customer;

class CustomerContact extends Model {
	protected $fillable = array(
		'first_name',
		'last_name',
		'phone',
		'mobile',
		'email',
		'primary_person'
	);

	//
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }


}
