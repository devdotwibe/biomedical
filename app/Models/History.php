<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected  $fillable = [ 'title', 'description', 'image_name','status' ];
}
