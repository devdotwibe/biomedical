<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Brand extends Model
{
	 protected $table = 'brand';
     protected  $fillable = [
        'name', 'slug', 'status'
     ];

    

     function BrandProduct()
     {
           return $this->hasMany('App\Product', 'brand_id', 'id' );
     }

}
