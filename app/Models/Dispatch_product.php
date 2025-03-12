<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Dispatch_product extends Model
{
	 protected $table = 'dispatch_product';
     protected  $fillable = [
        'dispatch_id','product_name','quantity','hsn','source','staff_id','warehouse_id'
     ];

     public function getstaff() {
      return $this->hasOne( 'App\Staff', 'id', 'staff_id' );
     }
     public function getwarehouse() {
      return $this->hasOne( 'App\Warehouse', 'id', 'warehouse_id' );
     }
     
   
     
}
