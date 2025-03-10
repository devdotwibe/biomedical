<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Product_type extends Model
{
	 protected $table = 'product_type';
     protected  $fillable = [
        'name', 'id','status'
     ];

  
}
