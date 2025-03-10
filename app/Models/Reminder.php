<?php

namespace App\Models;

use App\Staff;
use App\Task;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table="reminder";
    protected $fillable=[
        'title',
        'content',
        'remind_date',
        'status',
        'attachement',
        'repeate',
        'repeate_date',
        'staff_id',
        'task_id'
    ]; 
    public function staff(){
        return $this->belongsTo(Staff::class);
    }
    public function task(){
        return $this->belongsTo(Task::class);
    }
 
}

