<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Hosdeparment extends Model
{
	 protected $table = 'hosdeparment';
     protected  $fillable = [
        'name', 'slug', 'status'
     ];

   
}
