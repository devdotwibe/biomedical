<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cms extends Model
{
     protected $fillable = [
        'name', 'title', 'slug','description','status'
     ];
}
