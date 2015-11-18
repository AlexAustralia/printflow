<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model {

    protected $guarded = array('id');
    protected $fillable = array('quote_request_id', 'supplier_id', 'price', 'gst', 'net_cost', 'net_sell', 'markup');

    public function supplier(){
        return $this->belongsTo('App\Supplier');
    }

    public function quote_request(){
        return $this->belongsTo('App\QuoteRequest');
    }

    public function quote_items(){
        $items = [];

        foreach ($this->quantities() as $qty){
            $items[] = QuoteItem::where('quote_id', '=', $this->id)->where('quantity', '=', $qty)->first();
        }

        return $items;
        //return $this->hasMany('App\QuoteItem');
    }

    public function quantities(){
        $quantities = [];
        foreach  ($this->quote_request->qris()->get() as $qri){
            $quantities[] = $qri->quantity;
        }
        return $quantities;
    }
}
