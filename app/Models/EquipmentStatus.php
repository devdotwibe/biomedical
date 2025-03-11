<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentStatus extends Model
{
    protected $table = 'equipment_status';
    protected  $fillable = [
        'name', 'status'
     ];

   
     public static function get_equipment_status($id){
        $eq_det=EquipmentStatus::where('id', '=', $id)->first();
        if($eq_det){
            return $eq_det->name;
        }
        else{
            return '0';
        }
    }

}
