<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Modality extends Model
{
	 protected $table = 'modality';
     protected  $fillable = [
        'id','name','status'
     ];

  
}