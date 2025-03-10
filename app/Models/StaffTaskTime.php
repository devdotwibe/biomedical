<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffTaskTime extends Model
{
    protected $table = 'staff_task_time';
    protected $fillable=[
        'staff_id',
        'task_id',
        'status',
    ];
    public function staff()
    {
    	return $this->belongsTo(\App\Staff::class,"staff_id","id");
    }
    public function task()
    {
    	return $this->belongsTo(\App\Task::class,"task_id","id");
    }
}

