<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTargetCommission extends Model
{
    protected $fillable=[
        'staff_id',
        'status',
    ];
    public function staff()
    {
    	return $this->belongsTo(\App\Staff::class,"staff_id","id");
    }
}

