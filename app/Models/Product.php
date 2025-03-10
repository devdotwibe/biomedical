<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\ContractProduct;

class Product extends Model
{
    protected $fillable = [
        'name',
        'title',
        'category_id',
        'description',
        'part_no',
        'slug',
        'short_title',
        'short_description',
        'image_name',
        'status',
        'modality'
    ];

    public function getbrand()
    {
        return $this->hasOne('App\Brand', 'id', 'brand_id');
    }

    public function getcatgory()
    {
        return $this->hasOne('App\Category', 'id', 'category_id');
    }

    public function productmsp()
    {
        return $this->hasOne('App\Msp', 'product_id', 'id');
    }

    public function productService()
    {
        return $this->hasMany('App\Service', 'equipment_id', 'id');
    }

    public function opportunities()
    {
        return $this->hasMany('App\Oppertunity');
    }

    public function productib()
    {
        return $this->hasMany('App\Ib','equipment_id','id');
    }

    public function contractProducts()
    {
        return $this->hasMany(ContractProduct::class);
    }

    public static function get_product_details($product_id)
    {
        $product_det = Product::where('id', $product_id)->get();
        if (count($product_det) > 0) {
            return $product_det[0]->name;
        }
    }

}
