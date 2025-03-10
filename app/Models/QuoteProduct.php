<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteProduct extends Model
{
    protected $table = 'quote_products';

    function optionalProducts(){
        return $this->hasMany('App\Models\QuoteOptionalProduct','quote_products_id','id');
    }
    function oppertunity(){
        return $this->belongsTo('App\Oppertunity','oppertunity_id','id');
    }

    public function quoteProduct()
	{
		return $this->belongsTo('App\Product','product_id');
	}

}
