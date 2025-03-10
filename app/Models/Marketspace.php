<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marketspace extends Model
{
    protected $table = 'marketspace';
    protected  $fillable = [
        'name', 'email', 'password'
     ];
     

     public static function get_staff_name($id){
        
        $market=Marketspace::where('id', '=', $id)->first();
        if($market){
            return $market->phone;
        }
        else{
            return '0';
        }
    }
    public static function get_service_staff_skill($id){
        $marketspace_skill = Marketspace_skills::where('marketspace_id', '=', $id)->get();
        return $marketspace_skill;
    }
    public static function get_service_staff_experiance($id){
        
        $marketspace_experience=Marketspace_experience::where('marketspace_id', '=',$id)->get();
        return $marketspace_experience;
    }

    public static function get_productname($id){
        $product = Product::where('id', '=', $id)->first();
        return $product->name;
    }

    public static function get_service_accept_staff_marketspace($marketspace_id,$product_id){
        $marketspace_service=Marketspace_request_service::
        select('marketspace.name','marketspace.phone',
        'marketspace_request_service.service_staffquote_price','marketspace_request_service.service_approve_date',
        'marketspace_request_service.service_staff_time','marketspace_request_service.service_staff')->
        join('marketspace', 'marketspace.id', '=', 'marketspace_request_service.marketspace_id')
        ->where('marketspace_request_service.marketspace_id', '=',$marketspace_id)
        ->where('marketspace_request_service.product_id', '=',$product_id)
        ->where('marketspace_request_service.status', '=','Accept_staff')
        ->get();
        return $marketspace_service;
    }

    
}
