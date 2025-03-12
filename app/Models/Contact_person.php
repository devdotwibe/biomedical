<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

//use App\Category;

class Contact_person extends Model
{
	 protected $table = 'contact_person';
     protected  $fillable = [
        'name', 'email', 'department','designation','phone','user_id','id'
     ];

     public function designationDetails(){
        return $this->belongsTo(\App\Hosdesignation::class,"designation","id");
     }
     public function departmentDetails(){
        return $this->belongsTo(\App\Hosdeparment::class,"department","id");
     }
     public static function get_designation($id){
      $desig=Designation::where('id', '=', $id)->first();
      if($desig){
          return $desig->name;
      }
      else{
          return '';
      }
  }
    
}
