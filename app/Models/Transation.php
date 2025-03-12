<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
//use App\Category;

class Transation extends Model
{
	 protected $table = 'transation';
     protected  $fillable = [
        'name', 'slug', 'status'
     ];

     
     
     public function allstate() {
      return $this->hasOne('state', 'id', 'state_id');
  }
  public function getcustomer() {
   return $this->hasOne( 'App\User', 'id', 'user_id' );
  }
  public static function getstaff($id) {
   $staff=Staff::where('id',$id)
   ->first();
   if($staff)
   {
      return $staff->name;
   }else{return '';}
   
  }
  public static function getinvoice_productcount($transaction_id,$product_id) {
   $transation_product=Invoice_product::where('transation_id',$transaction_id)
   ->where('product_id',$product_id)
   ->get();
   return count($transation_product);
  }
  public static function getdispatch_verifycount($transaction_id,$product_id) {
  $dispatch_det= DB::table('dispatch')
->select('dispatch.id','dispatch.transation_id','dispatch_product.product_id')
->join('dispatch_product','dispatch_product.dispatch_id','=','dispatch.id')
->where(['dispatch.transation_id' => $transaction_id, 'dispatch_product.product_id' => $product_id])
->get();
   return count($dispatch_det);
  }

  public static function getstaff_update_details($transaction_id,$status) {
   $staff_updates=Transation_staff_updates::where('transation_id',$transaction_id)
   ->where('status',$status)
   ->first();
   return $staff_updates;
  }

  public static function getinvoice_productdetails($transaction_id) {
    
   $transation_product=Invoice_product::where('transation_id',$transaction_id)
   ->get();
   return $transation_product;
  }

  public static function getcreditnote_product($credit_id) {
    
   $credit_product=Credit_product::where('credit_id',$credit_id)
   ->get();
   return $credit_product;
  }
  



    /* public function childs() {
        return $this->hasMany('App\Transation','id')->orderBy('asc') ;
     }*/
}
