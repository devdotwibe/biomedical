<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\PmDetails;
use App\ContractProduct;
use App\Task_comment;

class Service extends Model
{
    protected $table = 'services';
    protected $fillable = [
        'service_type', 'internal_ref_no', 'external_ref_no', 'user_id', 'contact_person_id', 'equipment_id','equipment_serial_no', 'equipment_status_id', 'machine_status_id', 'call_details', 'engineer_id', 'created_by'
    ];

    function productPM($pm)
    {
   	    return $this->productPMList->where('pm', $pm)->first();
    }

    function productPMList()
    {
   	    return $this->hasMany('App\PmDetails',  'service_id', 'id' );
    }
    public function pmContract(){
        return $this->belongsTo('App\Contract', 'id' , 'service_id');
    }

    public function pmDetails()
    {
        return $this->hasMany(PmDetails::class);
    }

    public static function pmDetailsFeedback($id)
    {
        return PmDetails::whereIn('id', Task::where('service_id', $id)
                     ->where('staff_status', 'Approved')
                     ->pluck('pm_detail_id')
                     ->toArray());
    }

    public static function pmDetailsApproved($id)
    {
        $pm_proved_count = PmDetails::whereIn('id', Task::where('service_id', $id)
                     ->where('staff_status', 'Approved')
                     ->pluck('pm_detail_id')
                     ->toArray())->count();

        $pm_all_count = PmDetails::where('service_id',$id)->count();

        if($pm_proved_count == $pm_all_count)
        {
            return true;
        }
        else
        {
            return false;
        }
        
    }

    function serviceUser()
    {
   	    return $this->belongsTo('App\User', 'user_id', 'id' )->withTrashed();
    }

    public static function userdistrict($id){

        $district=District::where('id', '=', $id)->first();
        if($district){
            return $district->name;
        }
        else{
            return '';
        }
    }
    public static function userstate($id){

        $state=State::where('id', '=', $id)->first();
        if($state){
            return $state->name;
        }
        else{
            return '';
        }
    }

    function serviceProduct()
    {
   	    return $this->belongsTo('App\Product', 'equipment_id', 'id' );
    }
    function serviceContactPerson()
    {
   	    return $this->belongsTo('App\Contact_person', 'contact_person_id', 'id' );
    }
    function serviceEngineer()
    {
   	    return $this->belongsTo('App\Staff', 'engineer_id', 'id' );
    }
    function serviceMachineStatus()
    {
   	    return $this->belongsTo('App\MachineStatus', 'machine_status_id', 'id' );
    }
    function serviceIb()
    {
   	    return $this->belongsTo('App\Ib', 'equipment_serial_no', 'equipment_serial_no' );
    }
    function serviceServiceType()
    {
   	    return $this->belongsTo('App\ServiceType', 'service_type', 'id' );
    }
    function serviceEquipmentStatus()
    {
   	    return $this->belongsTo('App\EquipmentStatus', 'equipment_status_id', 'id' );
    }
    function serviceTechSupport()
    {
   	    return $this->hasMany('App\ServiceTechnicalSupport', 'service_id', 'id' );
    }
    function serviceFeedback()
    {
   	    return $this->hasMany('App\ServiceFeedback', 'service_id', 'id' );
    }
    function serviceCreatedBy()
    {
   	    return $this->belongsTo('App\Staff', 'created_by', 'id' );
    }
    function serviceTaskComment()
    {
   	    return $this->hasMany('App\Task_comment', 'service_id', 'id' );
    }

    public function contractProducts()
   {
    return $this->hasMany(ContractProduct::class);
   }

   public function task_comments()
   {
       return $this->hasMany(Task_comment::class, 'service_id'); // Assuming Task_comment has a foreign key 'service_id'
   }



}
