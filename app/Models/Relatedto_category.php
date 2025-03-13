<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Relatedto_category extends Model
{
	 protected $table = 'relatedto_category';
    protected  $fillable = [
        'name','status'
    ];
}
