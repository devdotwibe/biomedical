<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Country extends Model
{
	 protected $table = 'countries';
     protected  $fillable = [
        'name', 'sortname', 'id'
     ];

   
}
