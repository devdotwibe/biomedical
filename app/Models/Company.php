<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Company extends Model
{
	 protected $table = 'company';
     protected  $fillable = [
        'name', 'slug', 'status'
     ];

   
}
