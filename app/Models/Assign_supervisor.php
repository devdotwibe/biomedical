<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Assign_supervisor extends Model
{
	 protected $table = 'assign_supervisor';
     protected  $fillable = [
        'staff_id', 'supervisor_id', 'id',
     ];

   
}
