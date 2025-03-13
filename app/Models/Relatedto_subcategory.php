<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relatedto_subcategory extends Model
{
	 protected $table = 'relatedto_subcategory';
    protected  $fillable = [
        'name','relatedto_category_id','status'
    ];
}
