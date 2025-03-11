<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Taluk extends Model
{
	 protected $table = 'taluk';
     protected  $fillable = [
        'name', 'country_id', 'id','state_id','district_id'
     ];

   
}
