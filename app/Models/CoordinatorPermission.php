<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoordinatorPermission extends Model
{
    public function coordinatorstaffs()
	{
		return $this->belongsTo('App\Staff','id','staff_id');
	}

}
