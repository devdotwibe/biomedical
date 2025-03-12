<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePart extends Model
{
    protected $table = 'service_parts';
    protected $fillable = [
        'task_comment_id', 'service_id', 'product_id', 'task_id', 'staff_id', 'status'
    ];
    function servicePartProduct()
    {	
   	    return $this->hasOne('App\Product', 'id', 'product_id' );
    }
    function servicePartStaff()
    {	
   	    return $this->hasOne('App\Staff', 'id', 'staff_id' );
    }
    function servicePartTaskComment()
    {	
   	    return $this->belongsTo('App\Task_comment', 'task_comment_id', 'id' );
    }
}
