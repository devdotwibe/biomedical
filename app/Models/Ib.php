<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ib extends Model
{
    protected $table = 'ib';
    protected $fillable = [
        'internal_ref_no',
        'op_customer',
        'external_ref_no',
        'user_id',
        'department_id',
        'equipment_id',
        'equipment_serial_no',
        'equipment_model_no',
        'equipment_status_id',
        'staff_id',
        'installation_date',
        'warrenty_end_date',
        'supplay_order',
        'invoice_number',
        'invoice_date',
        'description',
    ];

    function ibEquipmentStatus()
    {
   	    return $this->belongsTo('App\Models\EquipmentStatus', 'equipment_status_id', 'id' );
    }
    function ibBrand()
    {
   	    return $this->belongsTo('App\Models\Brand', 'brand_id', 'id' );
    }
    // function ibUser()
    // {
   	//     return $this->belongsTo('App\Models\User', 'user_id', 'id' )->withTrashed();
    // }
        function ibUser()
    {
   	    return $this->belongsTo('App\Models\User', 'user_id', 'id' );
    }
    function ibUserDistrict()
    {
   	    //return $this->hasManyThrough('App\User', 'App\District',  'district_id', 'user_id','district_id', 'id' );
    }
    function ibProduct()
    {
   	    return $this->belongsTo('App\Models\Product', 'equipment_id', 'id' );
    }
    function ibDepartment()
    {
   	    return $this->belongsTo('App\Models\Category', 'department_id', 'id' );
    }
    function ibStaff()
    {
   	    return $this->belongsTo('App\Models\Staff', 'staff_id', 'id' );
    }
    function product()
    {
   	    return $this->belongsTo('App\Models\Product', 'equipment_id', 'id' );
    }
    public static function getibproduct_id($product_id)
    {
        $ib                  = Ib::where('equipment_id',$product_id)->first();
   	    return $ib->equipment_serial_no;
    }

}

