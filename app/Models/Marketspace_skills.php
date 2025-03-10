<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Marketspace_skills extends Model
{
	 protected $table = 'marketspace_skills';
     protected  $fillable = [
        'id',
     ];

     public function brand()
     {
        return $this->belongsTo('App\Brand','brand_id');
     }
     public function product_type()
     {
        return $this->belongsTo('App\Product_type','product_type_id');
     }
     public function category_type()
     {
        return $this->belongsTo('App\Category_type','category_type_id');
     }
     public function product()
     {
        return $this->belongsTo('App\Product','product_id');
     }
     public function productdetails($id)
     {
         $product =Product::find($id);
         return $product->name;
     }

}
