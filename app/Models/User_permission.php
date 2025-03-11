<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class User_permission extends Model
{
	 protected $table = 'user_permission';
     protected  $fillable = [
        'staff_id'
     ];

     public function staffs()
     {
        return $this->belongsTo('App\Models\Staff','staff_id');
     }

     public function userstaffcategory()
     {
        return $this->belongsTo('App\Models\StaffCategory','category_id');
     }
   
}
