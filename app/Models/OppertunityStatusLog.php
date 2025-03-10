<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OppertunityStatusLog extends Model
{
    protected $table="oppertunity_status_log";
	public function oppertunity()
    {
    	return $this->belongsTo('App\Oppertunity','oppertunity_id','id');
    }
}
