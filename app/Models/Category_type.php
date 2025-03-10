<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Category_type extends Model
{
	 protected $table = 'category_type';
     protected  $fillable = [
        'name', 'id'
     ];

  
}
