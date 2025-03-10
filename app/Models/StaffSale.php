<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffSale extends Model
{
    protected $fillable=[
        'staff_id',
        'user_id',
        'category_id',
        'contact_person_id',
        'comment',
        'engineer_name',
        'customer_name',
        'address',
        'care_area',
        'contact_person_name',
        'contact_person_phone',
        'contact_person_designation',
        'status',
        'approval'
    ];
    public function customer()
    {
    	return $this->belongsTo(\App\User::class,"user_id","id");
    }
    public function staff()
    {
    	return $this->belongsTo(\App\Staff::class,"staff_id","id");
    }
    public function careArea(){
        return $this->belongsTo(\App\Category::class,"category_id","id");
    }
    public function productType(){
        return $this->hasMany(\App\Models\StaffSaleType::class);
    }
}

