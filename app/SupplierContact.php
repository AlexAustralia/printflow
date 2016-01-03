<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class SupplierContact extends Model {

    protected $fillable = array(
        'first_name',
        'last_name',
        'phone',
        'mobile',
        'email',
        'primary_person'
    );

}
