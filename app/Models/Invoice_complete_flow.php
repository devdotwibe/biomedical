<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice_complete_flow extends Model
{
    protected $table = 'invoice_complete_flow';
    protected  $fillable = [
        'id','type_name','dispatch','delivery'
     ];

}
