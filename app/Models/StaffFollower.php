<?php

namespace App\Models;

use App\Staff;
use Illuminate\Database\Eloquent\Model;


class StaffFollower extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'staff_task_follower';
    protected  $fillable = [
    'staff_id', 'follower_id', 'status'
    ];
   

}
