<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Category extends Model
{
    protected  $fillable = [
        'name', 'slug', 'status', 'parent_id'
     ];

     public function childs() {

        return $this->hasMany('App\Category','parent_id','id')->orderBy('parent_id', 'asc') ;
     }


     public function productType() {

         return $this->hasMany('App\Product_type','category_id','id');
      }

     // public function downloads() {

     //    return $this->hasMany('App\Downloads','category_id','id')->orderBy('category_id', 'asc')->orderBy('id', 'asc') ;

     // }
}
