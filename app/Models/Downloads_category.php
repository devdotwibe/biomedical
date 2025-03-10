<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Downloads_category extends Model
{
   protected  $fillable = [
        'name','status', 'parent_id'
     ];

     public function childs() {

        return $this->hasMany('App\Downloads_category','parent_id','id')->orderBy('parent_id', 'asc') ;

     }

     public function downloads() {

        return $this->hasMany('App\Downloads','category_id','id')->orderBy('category_id', 'asc')->orderBy('id', 'asc') ;

     }
}
