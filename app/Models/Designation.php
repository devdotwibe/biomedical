<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Designation extends Model
{
	 protected $table = 'designation';
     protected  $fillable = [
        'name', 'slug', 'status'
     ];

   
}
