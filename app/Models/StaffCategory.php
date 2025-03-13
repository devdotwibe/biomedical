<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class StaffCategory extends Model
{
	 protected $table = 'staff_category';
     protected  $fillable = [
        'name', 'id'
     ];

     public function categorystaff()
      {
         return $this->hasMany(Staff::class, 'category_id');
      }
}
