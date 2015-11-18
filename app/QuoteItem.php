<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model {
    protected $guarded = array('id');
    protected $fillable = array('quote_id', 'quantity', 'buy_price', 'buy_price_unit', 
                                'duty', 'freight_cbm', 'freight_cost', 'total_buy_cost', 
                                'markup', 'total_net', 'gst', 'total_inc_gst', 'unit_price_inc_gst');

}
