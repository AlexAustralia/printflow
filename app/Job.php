<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = array(
        'quote_requests_id',
        'quote_request_items_id'
    );
}
