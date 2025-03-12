<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Hosdesignation extends Model
{
	 protected $table = 'hosdesignation';
     protected  $fillable = [
        'name', 'slug', 'status'
     ];

   
}
