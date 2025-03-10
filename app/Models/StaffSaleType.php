<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSaleType extends Model
{
    protected $table = 'staff_sales_type';
    protected $fillable=[
        'staff_sale_id',
        'product_type_id',
        'name',
        'sale_type'
    ];
}