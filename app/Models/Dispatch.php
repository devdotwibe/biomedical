<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Dispatch extends Model
{
	 protected $table = 'dispatch';
     protected  $fillable = [
        'invoice_id','invoice_no','dispatch_date','verified_staff','courier_id'
     ];

    
    
     
}
