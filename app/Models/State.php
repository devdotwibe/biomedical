<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class State extends Model
{
	 protected $table = 'state';
     protected  $fillable = [
        'name', 'country_id', 'id'
     ];

   
}
