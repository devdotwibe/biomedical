<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class  Transation_staff_updates extends Model
{
    protected $table = 'transation_staff_updates';
    protected  $fillable = [
        'staff_id', 'transaction_id'
     ];

}
