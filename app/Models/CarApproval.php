<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarApproval extends Model
{
    protected $table = 'car_approvals';
    
        function carApprovalStaff(){	
   		return $this->hasOne('App\Staff', 'id', 'staff_id' );
	}
}
