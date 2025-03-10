<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OppertunityOrder extends Model
{
	public function oppertunity()
    {
    	return $this->belongsTo('App\Oppertunity','oppertunity_id','id');
    }
}
