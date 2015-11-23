<?php namespace App;

use Illuminate\Database\Eloquent\Model;

require("helpers.php");

class QuoteRequest extends Model {
    protected $guarded = array('id');
    protected $fillable = array('customer_id', 'request_date', 'expiry_date', 'ref', 'title', 'summary', 'terms');
	
    public function customer(){
        return $this->belongsTo('App\Customer');
    }

    public function qris()
    {
        return $this->hasMany('App\QuoteRequestItem');
    }


    // Get quote's jobs
    public function job()
    {
        return $this->hasOne('App\Quote');
    }

    public function first_quote(){
        $quote = $this->quotes->first();
        return $quote;
    }

    public function getRequestDateAttribute($value){
        return fixGetDate($value);
    }

    public function setRequestDateAttribute($value){
        $this->attributes['request_date'] = fixSetDate($value);
    }

    public function getExpiryDateAttribute($value){
        return fixGetDate($value);
    }

    public function setExpiryDateAttribute($value){
        $this->attributes['expiry_date'] = fixSetDate($value);
    }

    public function emails(){
        $addresses = [];
        foreach ($this->quotes as $quote) {
            $addresses[] = $quote->supplier->supplier_name . " &lt;" . $quote->supplier->email() . "&gt;";
        }
        return implode(", ", $addresses);
    }


}
