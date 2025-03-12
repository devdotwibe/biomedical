<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class  Transaction_manage_staff extends Model
{
    protected $table = 'transaction_manage_staffs';
    protected  $fillable = [
        'staff_id', 'manage_section'
     ];

}
