<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteRequestItem extends Model {
    protected $guarded = array('id');
    protected $fillable = array('quote_request_id', 'quantity', 'description', 'price', 'gst', 'total', 'unit_price');

    
}
