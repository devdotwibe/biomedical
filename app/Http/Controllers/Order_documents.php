<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Order_documents extends Model
{
	 protected $table = 'order_documents';
     protected  $fillable = [
        'id', 'order_id', 'document_title','document_image'
     ];

   
}
