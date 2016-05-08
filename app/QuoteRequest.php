<?php namespace App;

use Illuminate\Database\Eloquent\Model;

require("helpers.php");

class QuoteRequest extends Model {
    protected $guarded = array('id');
    protected $fillable = array('customer_id', 'request_date', 'expiry_date', 'ref', 'title', 'summary', 'terms', 'po_number');
	
    public function customer(){
        try{
            return $this->belongsTo('App\Customer');
        }
        catch(Exception $e) {
            return;
        }
    }

    public function qris()
    {
        return $this->hasMany('App\QuoteRequestItem');
    }

    public function quotes()
    {
        return $this->hasMany('App\Quote');
    }

    // Get quote's jobs
    public function job()
    {
        return $this->hasOne('App\Job', 'quote_requests_id', 'id');
    }

    //Get quote
    public function get_quote()
    {
        return $this->belongsTo('App\Quote', 'quote_id', 'id');
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

    public function emails()
    {
        $addresses = [];
        foreach ($this->quotes as $quote) {
            $addresses[] = $quote->supplier->supplier_name . " &lt;" . $quote->supplier->email() . "&gt;";
        }
        return implode(", ", $addresses);
    }

    public function get_status()
    {
        return $this->belongsTo('App\Status', 'status', 'id');
    }

    public function artwork_charges()
    {
        return $this->hasMany('App\ArtworkCharge');
    }

    public function freight_charges()
    {
        return $this->hasMany('App\Freight');
    }
}
