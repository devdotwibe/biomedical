<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class ContractProduct extends Model
{


    protected $table = 'contract_products';


    protected $fillable = [
        'contract_id',
        'service_id',
        'oppertunity_id',
        'equipment_id',
        'equipment_serial_no',
        'equipment_model_no',
        'under_contract',
        'supplay_order',
        'installation_date',
        'warraty_expiry_date',
        'equipment_qty',
        'equipment_amount',
        'equipment_tax',
        'equipment_total',
        'contract_type',
        'amount',
        'tax',
        'amount_tax',
        'machine_status_id',
        'revanue',
        'no_of_pm',
    ];
    function productMachineStatus()
    {
   	    return $this->belongsTo('App\MachineStatus', 'machine_status_id', 'id' );
    }
    function productPM($pm)
    {
   	    return $this->productPMList->where('pm', $pm)->first();
    }

    function productPMList()
    {
   	    return $this->hasMany('App\PmDetails',  'contract_equipment_id', 'id' );
    }

    function productPMIsallAppproved($id)
    {
   	    $all_pms = PmDetails::where('contract_equipment_id',$id)->count();

        $approved_pms =  PmDetails::where('contract_equipment_id',$id)->where('status','Approved')->count();

        if($all_pms == $approved_pms)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function contract()
    {
        return $this->belongsTo('App\Contract','contract_id','id');
    }

    public function service()
    {
        return $this->belongsTo('App\Service','service_id','id');
    }

    public function oppertunity()
    {
        return $this->belongsTo('App\Oppertunity','oppertunity_id','id');
    }

    public function equipment()
    {
        return $this->belongsTo('App\Product','equipment_id','id');
    }

}
