<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Customercategory extends Model
{
	 protected $table = 'customer_category';
     protected  $fillable = [
        'name', 'slug', 'status'
     ];

   
}
