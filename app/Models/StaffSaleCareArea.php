<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSaleCareArea extends Model
{
    protected $table = 'staff_sales_care_area';
    protected $fillable=[
        'staff_id',
        'user_id',
        'category_id',
        'status',
    ];
}