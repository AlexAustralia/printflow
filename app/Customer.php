<?php namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\CustomerContact;
//


class Customer extends Model {

    // protected $table = 'customers';

    protected $fillable = array(
        'customer_name',
        'postal_attention',
        'postal_street',
        'postal_city',
        'postal_state',
        'postal_postcode',
        'postal_country',
        'physical_attention',
        'physical_street',
        'physical_city',
        'physical_state',
        'tel_country',
        'tel_area',
        'tel_number',
        'fax_country',
        'fax_area',
        'fax_number',
        'mobile_country',
        'mobile_area',
        'mobile_number',
        'direct_country',
        'direct_area',
        'direct_number',
        'web_address',
        'skype_name',
        'notes'
    );

    public function customer_contacts()
    {
        return $this->hasMany('App\CustomerContact');
    }
    
    public function postal_address()
    {
        return $this->postal_attention . "\n" .
                $this->postal_street . "\n" .
                $this->postal_city . "\n" .
                $this->postal_state . "\n" .
                $this->postal_postcode . "\n" .
                $this->postal_country;
    }


}
