<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Transation_pocopy extends Model
{
	 protected $table = 'transation_pocopy';
     protected  $fillable = [
        'id'
     ];

    /* public function childs() {
        return $this->hasMany('App\Transation','id')->orderBy('asc') ;
     }*/
}
