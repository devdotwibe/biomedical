<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Units extends Model
{
	 protected $table = 'units';
     protected  $fillable = [
        'id','shorname','fullname'
     ];

   
}
