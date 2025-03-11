<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Oppertunity_product extends Model
{
    public function oppertunityProduct()
	{
		return $this->belongsTo('App\Models\Product','product_id');
	}
	public function oppertunityProductIb()
	{
		return $this->belongsTo('App\Models\Ib','ib_id');
	}

	public function oppertunity()
    {
    	return $this->belongsTo('App\Models\Oppertunity','oppertunity_id','id');
    }
}
