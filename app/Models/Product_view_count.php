<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product_view_count extends Model
{
    protected $table = 'product_view_count';
    protected $fillable = [
        'ip', 'product_id'
    ];
  
    
}
