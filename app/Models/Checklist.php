<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    protected $table = 'checklist';
     protected  $fillable = [
        'name', 'id'
     ];
}
