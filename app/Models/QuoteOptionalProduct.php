<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteOptionalProduct extends Model
{
    protected $table = 'quote_optional_products';


    function oppertunity(){
        return $this->belongsTo('App\Oppertunity','oppertunity_id','id');
    }
}
