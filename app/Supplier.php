<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model {

    protected $fillable = array(
        'supplier_name',
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

    public function supplier_contacts(){
        return $this->hasMany('App\SupplierContact');
    }

    public function primary_person(){
        $contact = $this->supplier_contacts()->where('primary_person', '=', '1')->first();

        if ($contact == null && count($this->supplier_contacts()) > 0){
            //die("No primary contact for ".$this->supplier_name.", returning first contact");
            return $this->supplier_contacts()->first();
        }
    }

    public function email(){
        $c = $this->primary_person();

        if ($c === null){
            return "NO_PRIMARY_PERSON@example.com";
        }

        if ($c->email === null){
            return "NO_EMAIL_FOR_PRIMARY_PERSON@example.com";
        }

        return $c->email;
    }

    // Get the list of products
    public function products()
    {
        return $this->hasMany('App\Product', 'supplier_id', 'id');
    }
}
