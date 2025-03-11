<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_image extends Model
{
	 protected $table = 'product_image';
    protected  $fillable = [
        'title', 'image_name', 'product_id'
    ];
}
