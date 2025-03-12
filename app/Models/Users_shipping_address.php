<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Users_shipping_address extends Model
{
	 protected $table = 'users_shipping_address';
     protected  $fillable = [
        'user_id', 'country_id', 'address1','address2','city','state','zip'
     ];

    
}
