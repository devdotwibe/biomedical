<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class District extends Model
{
	 protected $table = 'district';
     protected  $fillable = [
        'name', 'country_id', 'id','state_id'
     ];

   
}
