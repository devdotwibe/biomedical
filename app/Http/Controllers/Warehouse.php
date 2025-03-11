<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Warehouse extends Model
{
	 protected $table = 'warehouse';
     protected  $fillable = [
        'name'
     ];

    
}
