<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competition_product extends Model
{
    protected $table = 'competition_product';
     protected  $fillable = [
        'name', 'id','product_id','brand_id','category_id','product_type_id','category_type_id'
     ];
}
