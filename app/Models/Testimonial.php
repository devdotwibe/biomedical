<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Testimonial extends Model
{
	 protected $table = 'testimonial';
     protected  $fillable = [
        'id','name',
     ];

  
}