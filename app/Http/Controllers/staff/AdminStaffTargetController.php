<?php

namespace App\Http\Controllers\staff;

use App\Brand;
use App\District;
use App\Models\OppertunityAttachement;
use App\Models\StaffPaidTarget;
use App\Models\StaffTarget;
use App\Models\StaffTargetCommission;
use App\Oppertunity;
use App\Oppertunity_product;
use App\Product;
use App\Staff;
use App\State;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Modality;
use App\StaffCategory;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
class AdminStaffTargetController extends Controller
{

    public function filterreport(Request $request){
        $year=empty($request->year)?date('Y'):$request->year;
        $minYear=StaffTarget::min('target_year');
        if(!(!empty($minYear)&&$minYear>0)){
            $minYear=date("Y");
        }


        $staff_target=StaffTarget:: where("target_year",$year);

        if((!empty($request->staff)&&is_numeric($request->staff))||!empty($coordinator)&&is_numeric($coordinator)) {

            if(!empty($request->staff)) {
                $staff_target->where("staff_id", $request->staff);
            }


        }
        if(!empty($request->equipmenaccessory)){
            $staff_target->where("sale_product_type",$request->equipmenaccessory);
        }

        if(!empty($request->brand)){
            $staff_target->where("brand_id",$request->brand);
        }
        $period=$request->period??"all"; 
        $mstart=0;
        $mend=0;
        switch ($period) {
            case '1st Quarter':
                $mstart=Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->format('m');
                $mend=Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->format('m');
               
                break;

            case '2nd Quarter':
                $mstart=Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->format('m');
                $mend=Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->format('m');
               
                break;

            case '3rd Quarter':
                $mstart=Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->format('m');
                $mend=Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->format('m');
               
                break;
                
            case '4th Quarter':
                $mstart=Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->format('m');
                $mend=Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->format('m');
               
                break;
            
            
            default:
                $mstart=Carbon::parse("$year-01-01")->format('m');
                $mend=Carbon::parse("$year-12-31")->format('m');
               
                break;
        }
        $staff_target->whereBetween("target_month",[$mstart,$mend]);
        $staffs=[];
        foreach(Staff::whereIn("id",$staff_target->select("staff_id"))->get() as $stf){
            for ($i=$mstart; $i <=$mend ; $i++) { 
                $mn=Carbon::parse(sprintf("%02d-%02d-01",$year,$i))->format('M');
                $stf->$mn=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",$i)->sum('target_amount');
            }
            $stf->total=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->whereBetween("target_month",[$mstart,$mend])->sum('target_amount');
            array_push($staffs,$stf);
        }
        $brands=Brand::orderBy('name','ASC')->get();
        return view("staff.staff_target.filterreport",compact('year','minYear','staffs','brands','mstart','mend'));
    }
    public function targetReport(Request $request){
        if($request->ajax()){
            $equipments=[];
            $accessories=[];

            // other
            $equipments_target=[];
            $equipments_target_total=0;
            $accessories_target=[];
            $accessories_target_total=0;

            // other
            $equipments_other=[];
            $equipments_other_total=0;
            $accessories_other=[];
            $accessories_other_total=0;

            // commitrisk
            $equipments_commitrisk=[];
            $equipments_commitrisk_total=0;
            $accessories_commitrisk=[];
            $accessories_commitrisk_total=0;


            // commited
            $equipments_commited=[];
            $equipments_commited_total=0;
            $accessories_commited=[];
            $accessories_commited_total=0;

            // won
            $equipments_won=[];
            $equipments_won_total=0;
            $accessories_won=[];
            $accessories_won_total=0;

            // new_order
            $equipments_new_order=[];
            $equipments_new_order_total=0;
            $accessories_new_order=[];
            $accessories_new_order_total=0;

            // initialcheck
            $equipments_initialcheck=[];
            $equipments_initialcheck_total=0;
            $accessories_initialcheck=[];
            $accessories_initialcheck_total=0;

            // ta
            $equipments_ta=[];
            $equipments_ta_total=0;
            $accessories_ta=[];
            $accessories_ta_total=0;


            // fa
            $equipments_fa=[];
            $equipments_fa_total=0;
            $accessories_fa=[];
            $accessories_fa_total=0;

            // billing
            $equipments_billing=[];
            $equipments_billing_total=0;
            $accessories_billing=[];
            $accessories_billing_total=0;

            // despatch
            $equipments_despatch=[];
            $equipments_despatch_total=0;
            $accessories_despatch=[];
            $accessories_despatch_total=0;


            // documentation
            $equipments_documentation=[];
            $equipments_documentation_total=0;
            $accessories_documentation=[];
            $accessories_documentation_total=0;

            // supplyissue
            $equipments_supplyissue=[];
            $equipments_supplyissue_total=0;
            $accessories_supplyissue=[];
            $accessories_supplyissue_total=0;


            // paymentfollow
            $equipments_paymentfollow=[];
            $equipments_paymentfollow_total=0;
            $accessories_paymentfollow=[];
            $accessories_paymentfollow_total=0;

            // audit
            $equipments_audit=[];
            $equipments_audit_total=0;
            $accessories_audit=[];
            $accessories_audit_total=0;
            $staff=$request->staff;
            $coordinator=$request->coordinator;
            $year=$request->year??date("Y");

            $equipments_brand=StaffTarget::where('sale_product_type','equipments')->where("target_year",$year);
            $accessories_brand=StaffTarget::where('sale_product_type','accessories')->where("target_year",$year);

                if((!empty($staff)&&is_numeric($staff))||!empty($coordinator)&&is_numeric($coordinator)) {

                    if(!empty($staff)) {
                        $equipments_brand->where("staff_id", $staff);
                        $accessories_brand->where("staff_id", $staff);

                    }


                }
                if(!empty($request->equipmenaccessory)){
                    $equipments_brand->where("sale_product_type",$request->equipmenaccessory);
                    $accessories_brand->where("sale_product_type",$request->equipmenaccessory);
                }

                if(!empty($request->brand)){
                    $equipments_brand->where("brand_id",$request->brand);
                    $accessories_brand->where("brand_id",$request->brand);
                }
                $period=$request->period??"all";
                $customer=$request->customer;

                if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $equipments_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->format('m')]);
                            $accessories_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->format('m')]);
                            break;

                        case '2nd Quarter':
                            $equipments_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->format('m')]);
                            $accessories_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->format('m')]);
                            break;

                        case '3rd Quarter':
                            $equipments_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->format('m')]);
                            $accessories_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->format('m')]);
                            break;
                            
                        case '4th Quarter':
                            $equipments_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->format('m')]);
                            $accessories_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->format('m')]);
                            break;
                        
                        
                        default:
                        $equipments_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->format('m'),Carbon::parse("$year-12-31")->format('m')]);
                        $accessories_brand->whereBetween("target_month",[Carbon::parse("$year-01-01")->format('m'),Carbon::parse("$year-12-31")->format('m')]);
                            break;
                    }
                }

                $customeregion=User::whereNotNull("business_name");
                if(count($request->state??[])>0){
                    $customeregion->whereIn("state_id",$request->state??[]);
                }
                if(count($request->district??[])>0){
                    $customeregion->whereIn("district_id",$request->district??[]);
                }
                foreach (Brand::where(function($qry)use($equipments_brand,$staff,$coordinator,$period,$customer,$year,$customeregion){
                    $qry->whereIn("id",$equipments_brand->groupBy('brand_id')->pluck('brand_id'));
                    $qry->orWhereIn("id",Product::where('category_type_id',1)->whereIn('id',Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){


                        if(!empty($staff)){
                            $qry->where("staff_id",$staff);
                        }
                        if(!empty($coordinator)){
                            $qry->where("coordinator_id",$coordinator);
                        }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                                    if(!empty($period)){
                                switch ($period) {
                                    case '1st Quarter':
                                        $qry->where(function($iqry)use($year){
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                            });
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                                                    $lqry->where(function($ilqry){
                                                        $ilqry->orWhere(function($iilqry){
                                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                        });
                                                        $ilqry->orWhere('status_type','order_forcast_category');
                                                    });
                                                })->whereNotIn('deal_stage',[6,7,8]);
                                            });
                                        });
                                        
                                        break;
            
                                    case '2nd Quarter':
                                        $qry->where(function($iqry)use($year){
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                            });
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()]);
                                                    $lqry->where(function($ilqry){
                                                        $ilqry->orWhere(function($iilqry){
                                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                        });
                                                        $ilqry->orWhere('status_type','order_forcast_category');
                                                    });
                                                })->whereNotIn('deal_stage',[6,7,8]);
                                            });
                                        });
                                        
                                        break;
            
                                    case '3rd Quarter':
                                        $qry->where(function($iqry)use($year){
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                            });
                                           
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()]);
                                                    $lqry->where(function($ilqry){
                                                        $ilqry->orWhere(function($iilqry){
                                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                        });
                                                        $ilqry->orWhere('status_type','order_forcast_category');
                                                    });
                                                })->whereNotIn('deal_stage',[6,7,8]);
                                            });
                                        });
                                        break;
                                        
                                    case '4th Quarter':
                                        $qry->where(function($iqry)use($year){
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                            });
                                            
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()]);
                                                    $lqry->where(function($ilqry){
                                                        $ilqry->orWhere(function($iilqry){
                                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                        });
                                                        $ilqry->orWhere('status_type','order_forcast_category');
                                                    });
                                                })->whereNotIn('deal_stage',[6,7,8]);
                                            });
                                        });
                                        break;
                                    
                                    
                                    default:
                                    $qry->where(function($iqry)use($year){
                                        $iqry->orWhere(function($iiqry)use($year){
                                            $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                        });
                                        
                                        $iqry->orWhere(function($iiqry)use($year){
                                            $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                                                $lqry->where(function($ilqry){
                                                    $ilqry->orWhere(function($iilqry){
                                                        $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                    });
                                                    $ilqry->orWhere('status_type','order_forcast_category');
                                                });
                                            })->whereNotIn('deal_stage',[6,7,8]);
                                        });
                                    });
                                        break;
                                }
                            }
                    })->select('product_id'))->select('brand_id'));
                })->get() as $brand) {

                    $brand_id=$brand->id;
                    
                    array_push($equipments,["id"=>$brand->id,"name"=>$brand->name]);
                    $total=0;

            $targetac=StaffTarget::where('sale_product_type','equipments')->where("brand_id",$brand->id)->where("target_year",$year);
            if(!empty($period)){
                switch ($period) {
                    case '1st Quarter':
                        $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->format('m')]);
                        break;

                    case '2nd Quarter':
                        $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->format('m')]);
                        break;

                    case '3rd Quarter':
                        $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->format('m')]);
                        break;
                        
                    case '4th Quarter':
                        $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->format('m')]);
                        break;
                    
                    
                    default:
                    $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->format('m'),Carbon::parse("$year-12-31")->format('m')]);
                        break;
                }
            }

            if(!empty($staff)){
                $targetac->where("staff_id",$staff);
            }
            $total=$targetac->sum('target_amount');
                    
                    array_push($equipments_target,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_target_total+=intval($total);

                    
                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ 
                    
                        $qry->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(count($customer??[])>0){
                        $qry->whereIn("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            
                                $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                                    $lqry->where(function($ilqry){
                                        $ilqry->orWhere(function($iilqry){
                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                        });
                                        $ilqry->orWhere(function($iilqry){
                                            $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                                        });
                                    });
                                })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
                            
                            // $qry->whereBetween("es_order_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            
                                $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()]);
                                    $lqry->where(function($ilqry){
                                        $ilqry->orWhere(function($iilqry){
                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                        });
                                        $ilqry->orWhere(function($iilqry){
                                            $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                                        });
                                    });
                                })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
                         
                            break;

                        case '3rd Quarter':
                            
                                $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()]);
                                    $lqry->where(function($ilqry){
                                        $ilqry->orWhere(function($iilqry){
                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                        });
                                        $ilqry->orWhere(function($iilqry){
                                            $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                                        });
                                    });
                                })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
                        
                            break;
                            
                        case '4th Quarter':
                            
                                $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()]);
                                    $lqry->where(function($ilqry){
                                        $ilqry->orWhere(function($iilqry){
                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                        });
                                        $ilqry->orWhere(function($iilqry){
                                            $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                                        });
                                    });
                                })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
                           
                            break;
                        
                        
                        default:
                        
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                                $lqry->where(function($ilqry){
                                    $ilqry->orWhere(function($iilqry){
                                        $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                    });
                                    $ilqry->orWhere(function($iilqry){
                                        $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                                    });
                                });
                            })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
              
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                array_push($equipments_other,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_other_total+=intval($total);


                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ 
                        $qry->where('order_forcast_category',4);

                        if(!empty($staff)){
                            $qry->where("staff_id",$staff);
                        }
                        if(!empty($coordinator)){
                            $qry->where("coordinator_id",$coordinator);
                        }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                            $qry->where("user_id",$customer);
                        }
                        if(!empty($period)){
                            switch ($period) {
                                case '1st Quarter':
                                    $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                            $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                                            $lqry->where("status",4)->where("status_type","order_forcast_category");
                                        })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                                    
                                    // $qry->whereBetween("es_order_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                                    break;
        
                                case '2nd Quarter':
                                    $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                            $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()]);
                                            $lqry->where("status",4)->where("status_type","order_forcast_category");
                                        })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                                    
                                    break;
        
                                case '3rd Quarter':
                                    $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                            $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()]);
                                            $lqry->where("status",4)->where("status_type","order_forcast_category");
                                        })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                                   
                                    break;
                                    
                                case '4th Quarter':
                                    $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                            $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()]);
                                            $lqry->where("status",4)->where("status_type","order_forcast_category");
                                        })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                                  
                                    break;
                                
                                
                                default:
                                $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                        $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                                        $lqry->where("status",4)->where("status_type","order_forcast_category");
                                    })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                           
                                    break;
                            }
                        }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                array_push($equipments_commitrisk,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_commitrisk_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->where('order_forcast_category',5);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                         
                            // $qry->whereBetween("es_order_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                
                            break;

                        case '3rd Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                          
                            break;
                            
                        case '4th Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                         
                            break;
                        
                        default:
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                         
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_commited,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_commited_total+=intval($total);


                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_won,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_won_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","New Orders")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_new_order,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_new_order_total+=intval($total);


                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Initial Check")->whereIn('deal_stage',[6,7,8]);


                    if(!empty($staff)){
                        $qry->where("staff_id",$staff);
                    }
                    if(!empty($coordinator)){
                        $qry->where("coordinator_id",$coordinator);
                    }
                    $qry->whereIn("user_id",$customeregion->select("id"));
                        if(count($customer??[])>0){
                        $qry->whereIn("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_initialcheck,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_initialcheck_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Technical Approval")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_ta,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_ta_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Finance Approval")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_fa,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_fa_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Billing")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_billing,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_billing_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Dispatch")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_despatch,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_despatch_total+=intval($total);


                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Documentation")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_documentation,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_documentation_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Supply Issue")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_supplyissue,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_supplyissue_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Payment Follow Up")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_paymentfollow,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_paymentfollow_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Audit")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(count($customer??[])>0){
                                    $qry->whereIn("user_id",$customer);
                                }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($equipments_audit,["id"=>$brand->id,"total"=>intval($total)]);
                    $equipments_audit_total+=intval($total);

                }

            foreach (Brand::where(function($qry)use($accessories_brand,$staff,$coordinator,$period,$customer,$year,$customeregion){
                    $qry->whereIn("id",$accessories_brand->groupBy('brand_id')->pluck('brand_id'));
                    $qry->orWhereIn("id",Product::where('category_type_id',3)->whereIn('id',Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){


                        if(!empty($staff)){
                            $qry->where("staff_id",$staff);
                        }
                        if(!empty($coordinator)){
                            $qry->where("coordinator_id",$coordinator);
                        }
                                    $qry->whereIn("user_id",$customeregion->select("id"));
                                 if(!empty($customer)){
                                    $qry->where("user_id",$customer);
                                }
                                    if(!empty($period)){
                                switch ($period) {
                                    case '1st Quarter':
                                        $qry->where(function($iqry)use($year){
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                            });
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                                                    $lqry->where(function($ilqry){
                                                        $ilqry->orWhere(function($iilqry){
                                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                        });
                                                        $ilqry->orWhere('status_type','order_forcast_category');
                                                    });
                                                })->whereNotIn('deal_stage',[6,7,8]);
                                            });
                                        });
                                        
                                        break;
            
                                    case '2nd Quarter':
                                        $qry->where(function($iqry)use($year){
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                            });
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()]);
                                                    $lqry->where(function($ilqry){
                                                        $ilqry->orWhere(function($iilqry){
                                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                        });
                                                        $ilqry->orWhere('status_type','order_forcast_category');
                                                    });
                                                })->whereNotIn('deal_stage',[6,7,8]);
                                            });
                                        });
                                        
                                        break;
            
                                    case '3rd Quarter':
                                        $qry->where(function($iqry)use($year){
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                            });
                                           
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()]);
                                                    $lqry->where(function($ilqry){
                                                        $ilqry->orWhere(function($iilqry){
                                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                        });
                                                        $ilqry->orWhere('status_type','order_forcast_category');
                                                    });
                                                })->whereNotIn('deal_stage',[6,7,8]);
                                            });
                                        });
                                        break;
                                        
                                    case '4th Quarter':
                                        $qry->where(function($iqry)use($year){
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                            });
                                            
                                            $iqry->orWhere(function($iiqry)use($year){
                                                $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()]);
                                                    $lqry->where(function($ilqry){
                                                        $ilqry->orWhere(function($iilqry){
                                                            $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                        });
                                                        $ilqry->orWhere('status_type','order_forcast_category');
                                                    });
                                                })->whereNotIn('deal_stage',[6,7,8]);
                                            });
                                        });
                                        break;
                                    
                                    
                                    default:
                                    $qry->where(function($iqry)use($year){
                                        $iqry->orWhere(function($iiqry)use($year){
                                            $iiqry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()])->whereIn('deal_stage',[6,7,8]);
                                        });
                                        
                                        $iqry->orWhere(function($iiqry)use($year){
                                            $iiqry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                                $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                                                $lqry->where(function($ilqry){
                                                    $ilqry->orWhere(function($iilqry){
                                                        $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                                    });
                                                    $ilqry->orWhere('status_type','order_forcast_category');
                                                });
                                            })->whereNotIn('deal_stage',[6,7,8]);
                                        });
                                    });
                                        break;
                                }
                            }
                                })->select('product_id'))->select('brand_id'));
                })->get() as $brand) {

                    $brand_id=$brand->id;

            array_push($accessories, ["id"=>$brand->id,"name"=>$brand->name]);


            $total=0;
            $targetac=StaffTarget::where('sale_product_type','accessories')->where("brand_id",$brand->id)->where("target_year",$year);
            if(!empty($period)){
                switch ($period) {
                    case '1st Quarter':
                        $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->format('m')]);
                        break;

                    case '2nd Quarter':
                        $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->format('m')]);
                        break;

                    case '3rd Quarter':
                        $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->format('m')]);
                        break;
                        
                    case '4th Quarter':
                        $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->format('m'),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->format('m')]);
                        break;
                    
                    
                    default:
                    $targetac->whereBetween("target_month",[Carbon::parse("$year-01-01")->format('m'),Carbon::parse("$year-12-31")->format('m')]);
                        break;
                }
            }

            if(!empty($staff)){
                $targetac->where("staff_id",$staff);
            }
            $total=$targetac->sum('target_amount');
            array_push($accessories_target,["id"=>$brand->id,"total"=>intval($total)]);
            $accessories_target_total+=intval($total);


            $total=0;
            $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){
                $qry->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);


    if(!empty($staff)){
        $qry->where("staff_id",$staff);
    }
    if(!empty($coordinator)){
        $qry->where("coordinator_id",$coordinator);
    }
                $qry->whereIn("user_id",$customeregion->select("id"));
                if(!empty($customer)){
                $qry->where("user_id",$customer);
            }
                if(!empty($period)){
            switch ($period) {
                case '1st Quarter':
                    $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                            $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            $lqry->where(function($ilqry){
                                $ilqry->orWhere(function($iilqry){
                                    $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                });
                                $ilqry->orWhere(function($iilqry){
                                    $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                                });
                            });
                        })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
                  
                    // $qry->whereBetween("es_order_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                    break;

                case '2nd Quarter':
                    $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                            $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()]);
                            $lqry->where(function($ilqry){
                                $ilqry->orWhere(function($iilqry){
                                    $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                });
                                $ilqry->orWhere(function($iilqry){
                                    $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                                });
                            });
                        })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
                   
                    break;

                case '3rd Quarter':
                    $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                            $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()]);
                            $lqry->where(function($ilqry){
                                $ilqry->orWhere(function($iilqry){
                                    $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                });
                                $ilqry->orWhere(function($iilqry){
                                    $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                                });
                            });
                        })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
            
                    break;
                    
                case '4th Quarter':
                    $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                            $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()]);
                            $lqry->where(function($ilqry){
                                $ilqry->orWhere(function($iilqry){
                                    $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                                });
                                $ilqry->orWhere(function($iilqry){
                                    $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                                });
                            });
                        })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
            
                    break;
                
                
                default:
                $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                        $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                        $lqry->where(function($ilqry){
                            $ilqry->orWhere(function($iilqry){
                                $iilqry->whereNotIn("status",[6,7,8])->where("status_type","deal_stage");
                            });
                            $ilqry->orWhere(function($iilqry){
                                $iilqry->whereNotIn("status",[4,5])->where("status_type","order_forcast_category");
                            });
                        });
                    })->whereNotIn('order_forcast_category',[4,5])->whereNotIn('deal_stage',[6,7,8]);
        
                    break;
            }
        }
            })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
            array_push($accessories_other,["id"=>$brand->id,"total"=>intval($total)]);
            $accessories_other_total+=intval($total);


                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->where('order_forcast_category',4);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",4)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                     
                            // $qry->whereBetween("es_order_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",4)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                        
                            break;

                        case '3rd Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",4)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                        
                            break;
                            
                        case '4th Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",4)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                           
                            break;
                        
                        default:
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                                    $lqry->where("status",4)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',4)->whereNotIn('deal_stage',[6,7,8]);
                            
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_commitrisk,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_commitrisk_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->where('order_forcast_category',5);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                            
                            // $qry->whereBetween("es_order_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-2)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                            
                            break;

                        case '3rd Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-3)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                            
                            break;
                            
                        case '4th Quarter':
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-4)->endOfQuarter()->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                            
                            break;
                        
                        default:
                            $qry->whereHas('oppertunityStatusLog',function($lqry)use($year){
                                    $lqry->whereBetween("created_at",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                                    $lqry->where("status",5)->where("status_type","order_forcast_category");
                                })->where('order_forcast_category',5)->whereNotIn('deal_stage',[6,7,8]);
                            
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_commited,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_commited_total+=intval($total);


                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_won,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_won_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","New Orders")->whereIn('deal_stage',[6,7,8]);


                    if(!empty($staff)){
                        $qry->where("staff_id",$staff);
                    }
                    if(!empty($coordinator)){
                        $qry->where("coordinator_id",$coordinator);
                    }
                    $qry->whereIn("user_id",$customeregion->select("id"));
                    if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_new_order,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_new_order_total+=intval($total);


                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Initial Check")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_initialcheck,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_initialcheck_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Technical Approval")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_ta,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_ta_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Finance Approval")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_fa,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_fa_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Billing")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_billing,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_billing_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Dispatch")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_despatch,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_despatch_total+=intval($total);


                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Documentation")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_documentation,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_documentation_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Supply Issue")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_supplyissue,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_supplyissue_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Payment Follow Up")->whereIn('deal_stage',[6,7,8]);


            if(!empty($staff)){
                $qry->where("staff_id",$staff);
            }
            if(!empty($coordinator)){
                $qry->where("coordinator_id",$coordinator);
            }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_paymentfollow,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_paymentfollow_total+=intval($total);

                    $total=0;
                    $total=Oppertunity_product::whereHas('oppertunity',function($qry)use($year,$staff,$coordinator,$period,$customer,$customeregion){ $qry->whereNotNull('won_date')->where("commission_status","Audit")->whereIn('deal_stage',[6,7,8]);


                        if(!empty($staff)){
                            $qry->where("staff_id",$staff);
                        }
                        if(!empty($coordinator)){
                            $qry->where("coordinator_id",$coordinator);
                        }
                        $qry->whereIn("user_id",$customeregion->select("id"));
                        if(!empty($customer)){
                        $qry->where("user_id",$customer);
                    }
                        if(!empty($period)){
                    switch ($period) {
                        case '1st Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                            break;

                        case '2nd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                            break;

                        case '3rd Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                            break;
                            
                        case '4th Quarter':
                            $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                            break;
                        
                        
                        default:
                        $qry->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                            break;
                    }
                }
                    })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand_id)->select('id'))->sum(DB::raw('coalesce(sale_amount,0)*coalesce(quantity,0)'));
                    array_push($accessories_audit,["id"=>$brand->id,"total"=>intval($total)]);
                    $accessories_audit_total+=intval($total);

                }
            
            return response()->json([
                "equipments"=>$equipments,
                "accessories"=>$accessories,
                "report"=>[
                    "target"=>[
                        "equipments"=>$equipments_target,
                        "accessories"=>$accessories_target,
                        "total"=>$equipments_target_total+$accessories_target_total,
                        "total_equipments"=>$equipments_target_total,
                        "total_accessories"=>$accessories_target_total,
                    ],
                    "other"=>[
                        "equipments"=>$equipments_other,
                        "accessories"=>$accessories_other,
                        "total"=>$equipments_other_total+$accessories_other_total,
                        "total_equipments"=>$equipments_other_total,
                        "total_accessories"=>$accessories_other_total,
                    ],
                    "commited-risk"=>[
                        "equipments"=>$equipments_commitrisk,
                        "accessories"=>$accessories_commitrisk,
                        "total"=>$equipments_commitrisk_total+$accessories_commitrisk_total,
                        "total_equipments"=>$equipments_commitrisk_total,
                        "total_accessories"=>$accessories_commitrisk_total,
                    ],
                    "commited"=>[
                        "equipments"=>$equipments_commited,
                        "accessories"=>$accessories_commited,
                        "total"=>$equipments_commited_total+$accessories_commited_total,
                        "total_equipments"=>$equipments_commited_total,
                        "total_accessories"=>$accessories_commited_total,
                    ],
                    "won"=>[
                        "equipments"=>$equipments_won,
                        "accessories"=>$accessories_won,
                        "total"=>$equipments_won_total+$accessories_won_total,
                        "total_equipments"=>$equipments_won_total,
                        "total_accessories"=>$accessories_won_total,
                    ],
                    "new-order"=>[
                        "equipments"=>$equipments_new_order,
                        "accessories"=>$accessories_new_order,
                        "total"=>$equipments_new_order_total+$accessories_new_order_total,
                        "total_equipments"=>$equipments_new_order_total,
                        "total_accessories"=>$accessories_new_order_total,
                    ],
                    "initial-check"=>[
                        "equipments"=>$equipments_initialcheck,
                        "accessories"=>$accessories_initialcheck,
                        "total"=>$equipments_initialcheck_total+$accessories_initialcheck_total,
                        "total_equipments"=>$equipments_initialcheck_total,
                        "total_accessories"=>$accessories_initialcheck_total,
                    ],
                    "ta"=>[
                        "equipments"=>$equipments_ta,
                        "accessories"=>$accessories_ta,
                        "total"=>$equipments_ta_total+$accessories_ta_total,
                        "total_equipments"=>$equipments_ta_total,
                        "total_accessories"=>$accessories_ta_total,
                    ],
                    "fa"=>[
                        "equipments"=>$equipments_fa,
                        "accessories"=>$accessories_fa,
                        "total"=>$equipments_fa_total+$accessories_fa_total,
                        "total_equipments"=>$equipments_fa_total,
                        "total_accessories"=>$accessories_fa_total,
                    ],
                    "billing"=>[
                        "equipments"=>$equipments_billing,
                        "accessories"=>$accessories_billing,
                        "total"=>$equipments_billing_total+$accessories_billing_total,
                        "total_equipments"=>$equipments_billing_total,
                        "total_accessories"=>$accessories_billing_total,
                    ],
                    "despatch"=>[
                        "equipments"=>$equipments_despatch,
                        "accessories"=>$accessories_despatch,
                        "total"=>$equipments_despatch_total+$accessories_despatch_total,
                        "total_equipments"=>$equipments_despatch_total,
                        "total_accessories"=>$accessories_despatch_total,
                    ],
                    "documentation"=>[
                        "equipments"=>$equipments_documentation,
                        "accessories"=>$accessories_documentation,
                        "total"=>$equipments_documentation_total+$accessories_documentation_total,
                        "total_equipments"=>$equipments_documentation_total,
                        "total_accessories"=>$accessories_documentation_total,
                    ],
                    "supply-issues"=>[
                        "equipments"=>$equipments_supplyissue,
                        "accessories"=>$accessories_supplyissue,
                        "total"=>$equipments_supplyissue_total+$accessories_supplyissue_total,
                        "total_equipments"=>$equipments_supplyissue_total,
                        "total_accessories"=>$accessories_supplyissue_total,
                    ],
                    "payment-followup"=>[
                        "equipments"=>$equipments_paymentfollow,
                        "accessories"=>$accessories_paymentfollow,
                        "total"=>$equipments_paymentfollow_total+$accessories_paymentfollow_total,
                        "total_equipments"=>$equipments_paymentfollow_total,
                        "total_accessories"=>$accessories_paymentfollow_total,
                    ],
                    "audit"=>[
                        "equipments"=>$equipments_audit,
                        "accessories"=>$accessories_audit,
                        "total"=>$equipments_audit_total+$accessories_audit_total,
                        "total_equipments"=>$equipments_audit_total,
                        "total_accessories"=>$accessories_audit_total,
                    ],
                ]
            ]);
        }
        $engineers=Staff::where("status","Y")->orderBy('name','ASC')->get();
        $states =  State::all();
        $brands = Brand::all();
        return view('staff.staff_target.report',compact('engineers','states','brands'));
    }
    // ajax select2
    public function customerlist(Request $request){
        $customer=User::select("id","business_name as text");
        
        if(count($request->state??[])>0){
            $customer->whereIn("state_id",$request->state??[]);
        }
        if(isset($request->district)&&$request->district>0){
            $customer->where("district_id", $request->district);
        }

        if(!empty($request->term)){
            $customer->where("business_name","like",'%'.$request->term.'%');
        }
        $result=[];
        $result["count_filtered"]=$customer->count();

        // if(($request->page??1)>1){
            $result['results']=$customer->skip((($request->page??1)-1)*10)->take(10)->get();
        // }else{
        //     $result['results']=[["id"=>0,"text"=>"Select an Customer"]];
        //     foreach($customer->skip(0)->take(10)->get() as $row){
        //         array_push($result['results'],$row);
        //     }
        // }
        return response()->json($result);
    }
    public function getDistrict(Request $request){
        $district=District::select("id","name as text");
  
        if(isset($request->state)&&$request->state>0){
            $district->where("state_id", $request->state);
        }

        if(!empty($request->term)){
            $district->where("business_name","like",'%'.$request->term.'%');
        }
        $result=[];
        $result["count_filtered"]=$district->count();
        $result['results']=$district->skip((($request->page??1)-1)*10)->take(10)->get();
        return response()->json($result);
    }
    public function targetCommission(Request $request){
        $order=empty($request->order)?"desc":$request->order;
        $sort=empty($request->sort)?"id":$request->sort;
        $page=empty($request->page)?1:$request->page;
        $staff=empty($request->staff)?0:$request->staff;
        $data=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date');
        
        $period=$request->period;
        $status=$request->status;
        $complete=$request->complete;
        $commission_status=$request->commission_status;
        $year=$request->year??date('y');
        if(!empty($period)){
            switch ($period) {
                case '1st Quarter':
                    $data->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters(Carbon::parse("$year-01-01")->quarter-1)->endOfQuarter()->toDateString()]);
                    break;

                case '2nd Quarter':
                    $data->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-2))->endOfQuarter()->toDateString()]);
                    break;

                case '3rd Quarter':
                    $data->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-3))->endOfQuarter()->toDateString()]);
                    break;
                    
                case '4th Quarter':
                    $data->whereBetween("won_date",[Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->startOfQuarter()->toDateString(),Carbon::parse("$year-01-01")->subQuarters((Carbon::parse("$year-01-01")->quarter-4))->endOfQuarter()->toDateString()]);
                    break;
                
                
                default:
                $data->whereBetween("won_date",[Carbon::parse("$year-01-01")->toDateString(),Carbon::parse("$year-12-31")->toDateString()]);
                    break;
            }
        }
        if($staff>0){
            $data->where('staff_id',$staff);
            
            if(!empty($request->status)){
                switch($request->status){
                    case "Won":$data->where('deal_stage',8);break;
                    case "Cancel":$data->where('deal_stage',7);break;
                    case "Lost":$data->where('deal_stage',6);break;
                    default:break;
                }
            }
        }
        if(!empty($request->commission_status)){
            $data->where("commission_status",$request->commission_status);
        }
        if(!empty($request->complete)){
            $data->where("complete_status","Y");
        }
        if(!empty($request->product_type)||!empty($request->brand)){
            $product_type=$request->product_type;
            $brand=$request->brand;
            $product=Product::select("id");
            if(!empty($request->product_type)&&$request->product_type==1){
                $product->where('category_type_id',1);
            }
            if(!empty($request->product_type)&&$request->product_type==2){
                $product->where('category_type_id',2);
            }
            if(!empty($request->brand)&&$request->brand>0){
                $product->where('brand_id',$request->brand);
            }
            $data->whereHas('oppertunityOppertunityProduct',function($qry)use($product){
                $qry->whereIn('product_id',$product);
            });
        }
        $data=$data->whereIn('deal_stage',[6,7,8])->orderBy($sort,$order)->paginate(25);
        $staffs=Staff::orderBy('name','asc')->get();

        
        $neworder=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $initialcheck=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $technichal=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $final=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $billing=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $dispatch=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $documentation=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $supply=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $paymentfollow=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);
        $audit=Oppertunity::has('oppertunityOppertunityProduct')->whereNotNull('won_date')->whereIn('deal_stage',[6,7,8]);

        $statuslist=[
            ["name"=>'New Orders',"display"=>'New Orders','count'=>$neworder->where('commission_status',"New Orders")->count()],
            ["name"=>"Initial Check","display"=>'Initial Check','count'=>$initialcheck->where('commission_status',"Initial Check")->count()],
            ["name"=>'Technical Approval',"display"=>'Technical Approval','count'=>$technichal->where('commission_status',"Technical Approval")->count()],
            ["name"=>'Finance Approval',"display"=>'Finance Approval','count'=>$final->where('commission_status',"Finance Approval")->count()],
            ["name"=>'Billing',"display"=>'Billing','count'=>$billing->where('commission_status',"Billing")->count()],
            ["name"=>'Dispatch',"display"=>'Dispatch','count'=>$dispatch->where('commission_status',"Dispatch")->count()],
            ["name"=>'Documentation',"display"=>'Documentation','count'=>$documentation->where('commission_status',"Documentation")->count()],
            ["name"=>'Supply Issue',"display"=>'Supply Issue','count'=>$supply->where('commission_status',"Supply Issue")->count()],
            ["name"=>'Payment Follow Up',"display"=>'Payment Follow Up','count'=>$paymentfollow->where('commission_status',"Payment Follow Up")->count()],
            ["name"=>"Audit","display"=>"Audit",'count'=>$audit->where('commission_status',"Audit")->count()]
        ];  
        return view('staff.staff_target.commission',compact('statuslist','data','order','sort','staff','page','staffs','period','status','complete','commission_status'));
    }
    public function changeTargetCommissionStatus(Request $request,$staff,$oppertunity){
        $opportunity=Oppertunity::find($oppertunity);
        $opportunity->commission_status=empty($request->status)?"New Orders":$request->status;
        $opportunity->save();
        return response()->json($opportunity);
    }
    public function completestatus(Request $request){
        $this->validate($request,[
            "id"=>"required",
            "status"=>"required",
        ]);
        $opportunity=Oppertunity::find($request->id);
        $opportunity->complete_status=($request->status=="Y")?"Y":"N";
        $opportunity->save();
        return response()->json($opportunity);
    }
    public function getOpurtunityCommission(Request $request,$staff,$id){
        $item=Oppertunity::find($id);
        return response()->json([
            "id"=>$id,
            "item"=>$item,
            "staff"=>$staff,
            "view"=>view('staff.staff_target.approveCommission',compact("id","item","staff"))->render()
        ]);
    }
    public function getOpurtunityCommissionApproved(Request $request,$staff,$id){
        $item=Oppertunity::find($id);
        return response()->json([
            "id"=>$id,
            "item"=>$item,
            "staff"=>$staff,
            "view"=>view('staff.staff_target.paidApproveCommission',compact("id","item","staff"))->render()
        ]);
    }
    public function addOpurtunityAttaches(Request $request){
        $this->validate($request,[
            'oppertunity_id'=>"required|numeric|min:1",
        ]);
        $item=Oppertunity::find($request->oppertunity_id);

        $imageName = time() . $request->fair_doc->getClientOriginalName();
        $imageName = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path = storage_path();
        $img_path = $request->fair_doc->storeAs('public/comment', $imageName);
        $path = $path . '/app/' . $img_path;
        chmod($path, 0777);
    }
    public function getOpurtunityAttaches(Request $request,$id){
        $item=Oppertunity::find($id);
        return response()->json(OppertunityAttachement::where('oppertunity_id',$item->id)->get());
    }
    public function addTargetCommission(Request $request,$id){
        $staff=Staff::find($id);
        if($staff==null){
            throw ValidationException::withMessages(["staff","selected staff not fount"]);
        }
        foreach (Oppertunity_product::whereHas('oppertunity',function($qry)use($staff){
            $qry->where('staff_id',$staff->id)->where('deal_stage',">=",6);
        })->where('approve_status',"N")->get() as $row) {
            $filedname="commission_".$row->id;
            if(isset($request->$filedname)&& ($request->$filedname * 1) > 0){
                $msp=\App\Msp::where('product_id',$row->product_id)->orderBy('id','DESC')->first();
                $protypecat=!empty($row->oppertunityProduct)&&!empty($row->oppertunityProduct->category_type_id)?(\App\Category_type::where('id',$row->oppertunityProduct->category_type_id)->orderBy('id','DESC')->first()):null;
                if(!empty($row->oppertunityProduct)){
                    if($row->oppertunityProduct->tax_percentage>0){
                        $row->tax_percentage=$row->oppertunityProduct->tax_percentage;

                    }
                    $row->unit_price=$row->oppertunityProduct->unit_price;
                }
                if(!empty($protypecat)){
                    $row->incentive=$protypecat->staff_commision;
                    $row->coordinator_incentive=$protypecat->coordinator_commision;
                }

                if(!empty($msp)){
                    $row->pro_quote_price=$msp->pro_quote_price;
                    $row->cost=$msp->cost;
                    $row->total_cost=$msp->total_cost;
                    $row->pro_msp=$msp->pro_msp;
                }
                $row->approve_status="Y";
                $row->commission=$request->$filedname;
                if(!empty($row->oppertunity)&&!empty($row->oppertunity->coordinator_id)){
                    $coordinatorfiledname="coordinator_commission_".$row->id;
                    if(isset($request->$coordinatorfiledname)&& ($request->$coordinatorfiledname * 1) > 0){
                        $row->coordinator_commission=$request->$coordinatorfiledname;
                    }
                }
                $row->approve_at=Carbon::now();
                $row->save();
            }
        }
        $request->session()->flash('success', 'Commission updated Successfully');

  		return redirect()->route('staff.staff.target.commission.index',['staff'=>$staff->id]);

    }

    public function approveTargetCommission(Request $request,$id){
        $staff=Staff::find($id);
        if($staff==null){
            throw ValidationException::withMessages(["staff","selected staff not fount"]);
        }
        foreach (Oppertunity_product::whereHas('oppertunity',function($qry)use($staff){
            $qry->where('staff_id',$staff->id)->where('deal_stage',">=",6);
        })->where('approve_status',"Y")->where('paid_status',"N")->get() as $row) {
            $filedname="commission_".$row->id;
            if(isset($request->$filedname)&&$request->$filedname=="Yes"){
                $row->paid_status="Y";
                $row->paid_at=Carbon::now();
                $row->save();
            }
        }
        $request->session()->flash('success', 'Commission updated Successfully');

  		return redirect()->route('staff.staff.target.commission.index',['staff'=>$staff->id]);

    }
    public function index(Request $request){
        $year=empty($request->year)?date('Y'):$request->year;
        $minYear=StaffTarget::min('target_year');
        if(!(!empty($minYear)&&$minYear>0)){
            $minYear=date("Y");
        }

        // $staffs=[];
        // foreach(Staff::all() as $stf){
        //     $stf->jan=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",1)->sum('target_amount');
        //     $stf->feb=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",2)->sum('target_amount');
        //     $stf->mar=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",3)->sum('target_amount');
        //     $stf->apr=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",4)->sum('target_amount');
        //     $stf->may=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",5)->sum('target_amount');
        //     $stf->jun=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",6)->sum('target_amount');
        //     $stf->jul=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",7)->sum('target_amount');
        //     $stf->aug=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",8)->sum('target_amount');
        //     $stf->sep=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",9)->sum('target_amount');
        //     $stf->oct=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",10)->sum('target_amount');
        //     $stf->nov=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",11)->sum('target_amount');
        //     $stf->dec=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",12)->sum('target_amount');
        //     $stf->total=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->sum('target_amount');
        //     array_push($staffs,$stf);
        // }

        $staff_category = StaffCategory::with('categorystaff')->get();

        $staffs=[];

        foreach($staff_category as $stff_cat)
        {
           
           foreach( $stff_cat->categorystaff()->get() as $stf)
           {
                if(!isset($staffs[$stff_cat->name])){

                    $staffs[$stff_cat->name]=[];
                }

                $stf->jan=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",1)->sum('target_amount');
                $stf->feb=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",2)->sum('target_amount');
                $stf->mar=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",3)->sum('target_amount');
                $stf->apr=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",4)->sum('target_amount');
                $stf->may=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",5)->sum('target_amount');
                $stf->jun=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",6)->sum('target_amount');
                $stf->jul=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",7)->sum('target_amount');
                $stf->aug=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",8)->sum('target_amount');
                $stf->sep=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",9)->sum('target_amount');
                $stf->oct=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",10)->sum('target_amount');
                $stf->nov=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",11)->sum('target_amount');
                $stf->dec=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->where("target_month",12)->sum('target_amount');
                $stf->total=StaffTarget::where('staff_id',$stf->id)->where("target_year",$year)->sum('target_amount');

                $staffs[$stff_cat->name][]=$stf;
           }
        }


        $brands=Brand::orderBy('name','ASC')->get();

        $modality = Modality::get();

        return view("staff.staff_target.index",compact('year','minYear','staffs','brands','modality'));
    }
    public function staffdata(Request $request){
        $minYear=StaffTarget::min('target_year');
        $this->validate($request,[
            'id'=>"required|numeric|min:1",
            'year'=>"required|numeric|min:$minYear"
        ]);
        $staff=Staff::find($request->id);
        $year=$request->year;
        $equipments=[];
        $equipments_brand_ids=StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->groupBy('brand_id')->pluck('brand_id')->all();
        foreach (StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->groupBy('brand_id')->pluck('brand_id')->all() as $brand_id) {
            $brand=Brand::find($brand_id);
            array_push($equipments,[
                "id"=>$brand->id,
                "name"=>$brand->name,
                "value"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("brand_id",$brand_id)->sum('target_amount'),
                "achived"=>[
                    "value"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-12-31")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->where('brand_id',$brand->id)->pluck('id')->all())->sum('sale_amount'),
                ],
                "comission"=>[
                    "value"=>0 
                ],
                "paid"=>[
                    "value"=>0,
                ]
            ]);
        }
        $accessories=[];
        $accessories_brand_ids=StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->groupBy('brand_id')->pluck('brand_id')->all();
        foreach ($accessories_brand_ids as $brand_id) {
            $brand=Brand::find($brand_id);
            array_push($accessories,[
                "id"=>$brand->id,
                "name"=>$brand->name,
                "value"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("brand_id",$brand_id)->sum('target_amount'),
                "achived"=>[
                    "value"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-12-31")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->where('brand_id',$brand->id)->pluck('id')->all())->sum('sale_amount'),
                ],
                "comission"=>[
                    "value"=>0 
                ],
                "paid"=>[
                    "value"=>0,
                ]
            ]);
        }
        return response()->json([
            "success"=>true,
            "equipments"=>[
                'start'=>StaffTarget::where('status','TimePeriod')->where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->min('target_month'),
                'end'=>StaffTarget::where('status','TimePeriod')->where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->max('target_month'),
                "jan"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",1)->sum('target_amount'),
                "feb"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",2)->sum('target_amount'),
                "mar"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",3)->sum('target_amount'),
                "apr"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",4)->sum('target_amount'),
                "may"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",5)->sum('target_amount'),
                "jun"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",6)->sum('target_amount'),
                "jul"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",7)->sum('target_amount'),
                "aug"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",8)->sum('target_amount'),
                "sep"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",9)->sum('target_amount'),
                "oct"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",10)->sum('target_amount'),
                "nov"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",11)->sum('target_amount'),
                "dec"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",12)->sum('target_amount'),
                "total"=>StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->sum('target_amount'),
                "achived"=>[
                    "jan"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-01-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "feb"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-02-01")->startOfMonth(),Carbon::parse("$year-02-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "mar"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-03-01")->startOfMonth(),Carbon::parse("$year-03-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "apr"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-04-01")->startOfMonth(),Carbon::parse("$year-04-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "may"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-05-01")->startOfMonth(),Carbon::parse("$year-05-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "jun"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-06-01")->startOfMonth(),Carbon::parse("$year-06-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "jul"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-07-01")->startOfMonth(),Carbon::parse("$year-07-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "aug"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-08-01")->startOfMonth(),Carbon::parse("$year-08-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "sep"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-09-01")->startOfMonth(),Carbon::parse("$year-09-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "oct"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-10-01")->startOfMonth(),Carbon::parse("$year-10-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "nov"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-11-01")->startOfMonth(),Carbon::parse("$year-11-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "dec"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-12-01")->startOfMonth(),Carbon::parse("$year-12-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "total"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-12-31")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum('sale_amount')
                ],
                "comission"=>[
                    "jan"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-01-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','01')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "feb"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-02-01")->startOfMonth(),Carbon::parse("$year-02-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','02')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "mar"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-03-01")->startOfMonth(),Carbon::parse("$year-03-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','03')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "apr"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-04-01")->startOfMonth(),Carbon::parse("$year-04-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','04')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "may"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-05-01")->startOfMonth(),Carbon::parse("$year-05-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','05')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "jun"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-06-01")->startOfMonth(),Carbon::parse("$year-06-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','06')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "jul"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-07-01")->startOfMonth(),Carbon::parse("$year-07-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','07')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "aug"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-08-01")->startOfMonth(),Carbon::parse("$year-08-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','08')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "sep"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-09-01")->startOfMonth(),Carbon::parse("$year-09-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','09')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "oct"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-10-01")->startOfMonth(),Carbon::parse("$year-10-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','10')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "nov"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-11-01")->startOfMonth(),Carbon::parse("$year-11-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','11')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "dec"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-12-01")->startOfMonth(),Carbon::parse("$year-12-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','12')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                    "total"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-12-31")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('target_amount'),
                ],
                "paid"=>[
                    "jan"=>StaffPaidTarget::where('target_month','01')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "feb"=>StaffPaidTarget::where('target_month','02')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "mar"=>StaffPaidTarget::where('target_month','03')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "apr"=>StaffPaidTarget::where('target_month','04')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "may"=>StaffPaidTarget::where('target_month','05')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "jun"=>StaffPaidTarget::where('target_month','06')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "jul"=>StaffPaidTarget::where('target_month','07')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "aug"=>StaffPaidTarget::where('target_month','08')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "sep"=>StaffPaidTarget::where('target_month','09')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "oct"=>StaffPaidTarget::where('target_month','10')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "nov"=>StaffPaidTarget::where('target_month','11')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "dec"=>StaffPaidTarget::where('target_month','12')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                    "total"=>StaffPaidTarget::where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','equipments')->sum('paid_amount'),
                ],
                'brands'=>$equipments
            ],
            "accessories"=>[
                'start'=>StaffTarget::where('status','TimePeriod')->where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->min('target_month'),
                'end'=>StaffTarget::where('status','TimePeriod')->where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->max('target_month'),
                "jan"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",1)->sum('target_amount'),
                "feb"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",2)->sum('target_amount'),
                "mar"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",3)->sum('target_amount'),
                "apr"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",4)->sum('target_amount'),
                "may"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",5)->sum('target_amount'),
                "jun"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",6)->sum('target_amount'),
                "jul"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",7)->sum('target_amount'),
                "aug"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",8)->sum('target_amount'),
                "sep"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",9)->sum('target_amount'),
                "oct"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",10)->sum('target_amount'),
                "nov"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",11)->sum('target_amount'),
                "dec"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->where("target_month",12)->sum('target_amount'),
                "total"=>StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->sum('target_amount'),
                "achived"=>[
                    "jan"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-01-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "feb"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-02-01")->startOfMonth(),Carbon::parse("$year-02-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "mar"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-03-01")->startOfMonth(),Carbon::parse("$year-03-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "apr"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-04-01")->startOfMonth(),Carbon::parse("$year-04-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "may"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-05-01")->startOfMonth(),Carbon::parse("$year-05-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "jun"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-06-01")->startOfMonth(),Carbon::parse("$year-06-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "jul"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-07-01")->startOfMonth(),Carbon::parse("$year-07-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "aug"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-08-01")->startOfMonth(),Carbon::parse("$year-08-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "sep"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-09-01")->startOfMonth(),Carbon::parse("$year-09-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "oct"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-10-01")->startOfMonth(),Carbon::parse("$year-10-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "nov"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-11-01")->startOfMonth(),Carbon::parse("$year-11-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "dec"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-12-01")->startOfMonth(),Carbon::parse("$year-12-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount'),
                    "total"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-12-31")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum('sale_amount')
                ],
                "comission"=>[
                    "jan"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-01-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','01')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "feb"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-02-01")->startOfMonth(),Carbon::parse("$year-02-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','02')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "mar"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-03-01")->startOfMonth(),Carbon::parse("$year-03-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','03')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "apr"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-04-01")->startOfMonth(),Carbon::parse("$year-04-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','04')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "may"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-05-01")->startOfMonth(),Carbon::parse("$year-05-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','05')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "jun"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-06-01")->startOfMonth(),Carbon::parse("$year-06-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','06')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "jul"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-07-01")->startOfMonth(),Carbon::parse("$year-07-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','07')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "aug"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-08-01")->startOfMonth(),Carbon::parse("$year-08-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','08')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "sep"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-09-01")->startOfMonth(),Carbon::parse("$year-09-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','09')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "oct"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-10-01")->startOfMonth(),Carbon::parse("$year-10-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','10')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "nov"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-11-01")->startOfMonth(),Carbon::parse("$year-11-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','11')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "dec"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-12-01")->startOfMonth(),Carbon::parse("$year-12-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_month','12')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                    "total"=>Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-01-01")->startOfMonth(),Carbon::parse("$year-12-31")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'))+StaffTargetCommission::where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('target_amount'),
                ],
                "paid"=>[
                    "jan"=>StaffPaidTarget::where('target_month','01')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "feb"=>StaffPaidTarget::where('target_month','02')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "mar"=>StaffPaidTarget::where('target_month','03')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "apr"=>StaffPaidTarget::where('target_month','04')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "may"=>StaffPaidTarget::where('target_month','05')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "jun"=>StaffPaidTarget::where('target_month','06')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "jul"=>StaffPaidTarget::where('target_month','07')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "aug"=>StaffPaidTarget::where('target_month','08')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "sep"=>StaffPaidTarget::where('target_month','09')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "oct"=>StaffPaidTarget::where('target_month','10')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "nov"=>StaffPaidTarget::where('target_month','11')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "dec"=>StaffPaidTarget::where('target_month','12')->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                    "total"=>StaffPaidTarget::where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type','accessories')->sum('paid_amount'),
                ],
                'brands'=>$accessories
            ]
        ]);
    }
    public function staffmonthpaidlist(Request $request){
        $validator = Validator::make($request->all(),[
            "year"=>"required|numeric|min:2022",
            "staff_id"=>"required|numeric|min:0",
            "target_type"=>"required|string",
            "target_month"=>"required|numeric|min:0",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $staff=Staff::find($request->staff_id);
        $year=$request->year;
        $month=$request->target_month;
        $type=$request->target_type;
        return response()->json(StaffPaidTarget::where('target_month',$month)->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type',$type)->get());
    }
    public function staffmonthcommissionlist(Request $request){
        $validator = Validator::make($request->all(),[
            "year"=>"required|numeric|min:2022",
            "staff_id"=>"required|numeric|min:0",
            "target_type"=>"required|string",
            "target_month"=>"required|numeric|min:0",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $staff=Staff::find($request->staff_id);
        $year=$request->year;
        $month=$request->target_month;
        $type=$request->target_type;
        return response()->json(StaffTargetCommission::where('target_month',$month)->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type',$type)->get());
    }
    public function removeBrand(Request $request){

        $validator = Validator::make($request->all(),[
            "year"=>"required|numeric|min:2022",
            "staff_id"=>"required|numeric|min:0",
            "target_type"=>"required|string",
            "brand"=>"required|numeric|min:0",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $brand=Brand::find($request->brand);
        $staff=Staff::find($request->staff_id);
        StaffTarget::where('staff_id',$staff->id)->where("brand_id",$brand->id)->where("target_year",$request->year)->where('sale_product_type',$request->target_type)->delete();
        return response()->json([
            'success' => "Brand Target Saved",
            "staff_id"=>$staff->id,
            "target"=>[
                "jan"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",1)->sum('target_amount'),
                "feb"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",2)->sum('target_amount'),
                "mar"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",3)->sum('target_amount'),
                "apr"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",4)->sum('target_amount'),
                "may"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",5)->sum('target_amount'),
                "jun"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",6)->sum('target_amount'),
                "jul"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",7)->sum('target_amount'),
                "aug"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",8)->sum('target_amount'),
                "sep"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",9)->sum('target_amount'),
                "oct"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",10)->sum('target_amount'),
                "nov"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",11)->sum('target_amount'),
                "dec"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",12)->sum('target_amount'),
                "total"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->sum('target_amount'),
            ]
        ]);
    }
    public function addmonthTarget(Request $request){

        $validator = Validator::make($request->all(),[
            "year"=>"required|numeric|min:2022",
            "target_month"=>"required|numeric|min:1|max:12",
            "staff_id"=>"required|numeric|min:0",
            "target_type"=>"required|string",
            "brand"=>"required|numeric|min:0",
            "brand_target"=>"required|numeric|min:0",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $brand=Brand::find($request->brand);
        $staff=Staff::find($request->staff_id);
        $monthlist=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];

        $saletarget=new StaffTarget;
        $saletarget->sale_product_type=$request->target_type;
        $saletarget->staff_id=$staff->id;
        $saletarget->target_year=$request->year;
        $saletarget->engineer_name	=$staff->name;
        $saletarget->target_month=$request->target_month;
        $saletarget->month_name=$monthlist[$request->target_month-1];
        $saletarget->brand_id=$brand->id;
        $saletarget->brand_name=$brand->name;
        if(isset($request->target_button)&&$request->target_button=="less"){
            $saletarget->target_amount	=round(($request->brand_target*(-1.0)),2);
        }else{
            $saletarget->target_amount	=round(($request->brand_target*1.0),2);
        }
        $saletarget->status	= "MonthTarget";
        $saletarget->save();

        return response()->json([
            'success' => "Brand Target Saved",
            "staff_id"=>$staff->id,
            "target"=>[
                "jan"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",1)->sum('target_amount'),
                "feb"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",2)->sum('target_amount'),
                "mar"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",3)->sum('target_amount'),
                "apr"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",4)->sum('target_amount'),
                "may"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",5)->sum('target_amount'),
                "jun"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",6)->sum('target_amount'),
                "jul"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",7)->sum('target_amount'),
                "aug"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",8)->sum('target_amount'),
                "sep"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",9)->sum('target_amount'),
                "oct"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",10)->sum('target_amount'),
                "nov"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",11)->sum('target_amount'),
                "dec"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",12)->sum('target_amount'),
                "total"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->sum('target_amount'),
            ]
        ]);
    }
    public function addmonthTargetCommission(Request $request){

        $validator = Validator::make($request->all(),[
            "year"=>"required|numeric|min:2022",
            "target_month"=>"required|numeric|min:1|max:12",
            "staff_id"=>"required|numeric|min:0",
            "target_type"=>"required|string",
            "target_amount"=>"required|numeric|min:0",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $staff=Staff::find($request->staff_id);
        $commission=0.0;
        $year=$request->year;
        $month=$request->target_month;
        if($request->target_type=="equipments"){
            $equipments_brand_ids=StaffTarget::where('sale_product_type','equipments')->where('staff_id',$staff->id)->where("target_year",$year)->groupBy('brand_id')->pluck('brand_id')->all();
            $commission = Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year,$month){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-$month-01")->startOfMonth(),Carbon::parse("$year-$month-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'));
            // $commission= QuoteProduct::whereHas('oppertunity',function($qry)use($staff){ $qry->where('staff_id',$staff->id); })->where('status','Won')->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->whereBetween("created_at",[Carbon::parse("$year-$month-01")->startOfMonth(),Carbon::parse("$year-$month-01")->endOfMonth()])->sum(DB::raw('IFNULL(( SELECT (quote_products.product_sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=quote_products.product_id ORDER BY id DESC LIMIT 1),0)'))+QuoteOptionalProduct::whereHas('oppertunity',function($qry)use($staff){ $qry->where('staff_id',$staff->id); })->where('status','Won')->whereIn('product_id',Product::where('category_type_id',1)->whereIn('brand_id',$equipments_brand_ids)->pluck('id')->all())->whereBetween("created_at",[Carbon::parse("$year-$month-01")->startOfMonth(),Carbon::parse("$year-$month-01")->endOfMonth()])->sum(DB::raw('IFNULL(( SELECT (quote_optional_products.sale_amount* incentive*1)/100 from msp WHERE msp.product_id=quote_optional_products.product_id ORDER BY id DESC LIMIT 1),0)'));
        }else{
            $accessories_brand_ids=StaffTarget::where('sale_product_type','accessories')->where('staff_id',$staff->id)->where("target_year",$year)->groupBy('brand_id')->pluck('brand_id')->all();
            $commission = Oppertunity_product::whereHas('oppertunity',function($qry)use($staff,$year,$month){ $qry->where('staff_id',$staff->id)->whereBetween("won_date",[Carbon::parse("$year-$month-01")->startOfMonth(),Carbon::parse("$year-$month-01")->endOfMonth()])->where('deal_stage',8); })->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->sum(DB::raw('IFNULL(( SELECT (oppertunity_products.sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=oppertunity_products.product_id ORDER BY id DESC LIMIT 1),0)'));

            // $commission= QuoteProduct::whereHas('oppertunity',function($qry)use($staff){ $qry->where('staff_id',$staff->id); })->where('status','Won')->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->whereBetween("created_at",[Carbon::parse("$year-$month-01")->startOfMonth(),Carbon::parse("$year-$month-01")->endOfMonth()])->sum(DB::raw('IFNULL(( SELECT (quote_products.product_sale_amount* msp.incentive*1)/100 from msp WHERE msp.product_id=quote_products.product_id ORDER BY id DESC LIMIT 1),0)'))+QuoteOptionalProduct::whereHas('oppertunity',function($qry)use($staff){ $qry->where('staff_id',$staff->id); })->where('status','Won')->whereIn('product_id',Product::where('category_type_id',3)->whereIn('brand_id',$accessories_brand_ids)->pluck('id')->all())->whereBetween("created_at",[Carbon::parse("$year-$month-01")->startOfMonth(),Carbon::parse("$year-$month-01")->endOfMonth()])->sum(DB::raw('IFNULL(( SELECT (quote_optional_products.sale_amount* incentive*1)/100 from msp WHERE msp.product_id=quote_optional_products.product_id ORDER BY id DESC LIMIT 1),0)'));
        }
        $commission=$commission+(StaffTargetCommission::where('target_month',$month)->where('target_year',$year)->where('staff_id',$staff->id)->where('sale_product_type',$request->target_type)->sum('target_amount')*1);

        $saletarget=new StaffTargetCommission;
        $saletarget->sale_product_type=$request->target_type;
        $saletarget->staff_id=$staff->id;
        $saletarget->target_year=$request->year;
        $saletarget->target_month=$request->target_month;
        $saletarget->target_amount	=round(($request->target_amount*1)-$commission,2);
        $saletarget->commission_amount=round(($request->target_amount*1),2);
        $saletarget->target_reason	= isset($request->reason)?$request->reason:"";
        $saletarget->save();

        return response()->json([
            'success' => "Brand Target Saved",
            "staff_id"=>$staff->id,
            "target"=>[
                "jan"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",1)->sum('target_amount'),
                "feb"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",2)->sum('target_amount'),
                "mar"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",3)->sum('target_amount'),
                "apr"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",4)->sum('target_amount'),
                "may"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",5)->sum('target_amount'),
                "jun"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",6)->sum('target_amount'),
                "jul"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",7)->sum('target_amount'),
                "aug"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",8)->sum('target_amount'),
                "sep"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",9)->sum('target_amount'),
                "oct"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",10)->sum('target_amount'),
                "nov"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",11)->sum('target_amount'),
                "dec"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",12)->sum('target_amount'),
                "total"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->sum('target_amount'),
            ]
        ]);
    }

    public function addmonthPaidTarget(Request $request){

        $validator = Validator::make($request->all(),[
            "year"=>"required|numeric|min:2022",
            "target_month"=>"required|numeric|min:1|max:12",
            "staff_id"=>"required|numeric|min:0",
            "target_type"=>"required|string",
            "target_amount"=>"required|numeric|min:0",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $staff=Staff::find($request->staff_id);

        $saletarget=new StaffPaidTarget;
        $saletarget->sale_product_type=$request->target_type;
        $saletarget->staff_id=$staff->id;
        $saletarget->target_year=$request->year;
        $saletarget->target_month=$request->target_month;
        $saletarget->paid_amount	=round(($request->target_amount*1),2);
        $saletarget->save();

        return response()->json([
            'success' => "Brand Target Saved",
            "staff_id"=>$staff->id,
            "target"=>[
                "jan"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",1)->sum('target_amount'),
                "feb"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",2)->sum('target_amount'),
                "mar"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",3)->sum('target_amount'),
                "apr"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",4)->sum('target_amount'),
                "may"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",5)->sum('target_amount'),
                "jun"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",6)->sum('target_amount'),
                "jul"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",7)->sum('target_amount'),
                "aug"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",8)->sum('target_amount'),
                "sep"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",9)->sum('target_amount'),
                "oct"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",10)->sum('target_amount'),
                "nov"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",11)->sum('target_amount'),
                "dec"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",12)->sum('target_amount'),
                "total"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->sum('target_amount'),
            ]
        ]);
    }
    public function updatePeriod(Request $request){

        $validator = Validator::make($request->all(),[
            "year"=>"required|numeric|min:2022",
            "start_month"=>"required|numeric|min:1|max:12",
            "end_month"=>"required|numeric|min:1|max:12",
            "staff_id"=>"required|numeric|min:0",
            "target_type"=>"required|string",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $staff=Staff::find($request->staff_id);

        $targets=[];
        foreach (StaffTarget::where('status','TimePeriod')->where('sale_product_type',$request->target_type)->where('staff_id',$staff->id)->where("target_year",$request->year)->groupBy('brand_id')->pluck('brand_id')->all() as $brand_id) {
            array_push($targets,[
                "id"=>$brand_id,
                "amount"=>round(StaffTarget::where('status','TimePeriod')->where('sale_product_type',$request->target_type)->where('staff_id',$staff->id)->where("target_year",$request->year)->where("brand_id",$brand_id)->sum('target_amount'),2)
            ]);
        }
        $monthlist=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        $count=($request->end_month-$request->start_month)+1;
        StaffTarget::where('status','TimePeriod')->where('sale_product_type',$request->target_type)->where('staff_id',$staff->id)->where("target_year",$request->year)->delete();
        foreach ($targets as $trgt) {
            $brand=Brand::find($trgt["id"]);
            for ($i=$request->start_month; $i <= $request->end_month ; $i++) { 
                $saletarget=new StaffTarget;
                $saletarget->sale_product_type=$request->target_type;
                $saletarget->staff_id=$staff->id;
                $saletarget->target_year=$request->year;
                $saletarget->engineer_name	=$staff->name;
                $saletarget->target_month=$i;
                $saletarget->month_name=$monthlist[$i-1];
                $saletarget->brand_id=$brand->id;
                $saletarget->brand_name=$brand->name;
                $saletarget->target_amount	=round(($trgt["amount"]/$count),2);
                $saletarget->status	= "TimePeriod";
                $saletarget->save();
            }
        }
        return response()->json([
            'success' => "Brand Target Saved",
            "staff_id"=>$staff->id,
            "target"=>[
                "jan"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",1)->sum('target_amount'),
                "feb"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",2)->sum('target_amount'),
                "mar"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",3)->sum('target_amount'),
                "apr"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",4)->sum('target_amount'),
                "may"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",5)->sum('target_amount'),
                "jun"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",6)->sum('target_amount'),
                "jul"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",7)->sum('target_amount'),
                "aug"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",8)->sum('target_amount'),
                "sep"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",9)->sum('target_amount'),
                "oct"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",10)->sum('target_amount'),
                "nov"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",11)->sum('target_amount'),
                "dec"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",12)->sum('target_amount'),
                "total"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->sum('target_amount'),
            ]
        ]);
    }
    public function store(Request $request){

        $validator = Validator::make($request->all(),[
            "year"=>"required|numeric|min:2022",
            "start_month"=>"required|numeric|min:1|max:12",
            "end_month"=>"required|numeric|min:1|max:12",
            "staff_id"=>"required|numeric|min:0",
            "target_type"=>"required|string",
            "brand"=>"required|numeric|min:0",
            "brand_target"=>"required|numeric|min:0",
            "modality"=>"required|numeric|min:0",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ]);
        }
        $staff=Staff::find($request->staff_id);
        $brand=Brand::find($request->brand);

        $modality=!empty($request->modality)?Modality::find($request->modality):null;

        $monthlist=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
        $count=($request->end_month-$request->start_month)+1;
        for ($i=$request->start_month; $i <= $request->end_month ; $i++) { 
            $saletarget=new StaffTarget;
            $saletarget->sale_product_type=$request->target_type;
            $saletarget->staff_id=$staff->id;
            $saletarget->target_year=$request->year;
            $saletarget->engineer_name	=$staff->name;
            $saletarget->target_month=$i;
            $saletarget->month_name=$monthlist[$i-1];
            $saletarget->brand_id=$brand->id;
            $saletarget->brand_id=$brand->id;
            $saletarget->modality_id=optional($modality)->id??null;
            $saletarget->modality_name=optional($modality)->name??null;
            $saletarget->brand_name=$brand->name;
            $saletarget->target_amount	=round(($request->brand_target/$count),2);
            $saletarget->status	= "TimePeriod";
            $saletarget->save();
        }
        return response()->json([
            'success' => "Brand Target Saved",
            "staff_id"=>$staff->id,
            "target"=>[
                "jan"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",1)->sum('target_amount'),
                "feb"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",2)->sum('target_amount'),
                "mar"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",3)->sum('target_amount'),
                "apr"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",4)->sum('target_amount'),
                "may"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",5)->sum('target_amount'),
                "jun"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",6)->sum('target_amount'),
                "jul"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",7)->sum('target_amount'),
                "aug"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",8)->sum('target_amount'),
                "sep"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",9)->sum('target_amount'),
                "oct"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",10)->sum('target_amount'),
                "nov"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",11)->sum('target_amount'),
                "dec"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->where("target_month",12)->sum('target_amount'),
                "total"=>StaffTarget::where('staff_id',$staff->id)->where("target_year",$request->year)->sum('target_amount'),
            ]
        ]);
    }
    public function create(Request $request){
        $staff=Staff::all();
        $state=State::all();
        $district=District::all();
        $company=User::all();
        return view("staff.staff_target.create",compact("staff",'state','district','company'));
    }
    public function show(Request $request,$id)
    {
        $data = StaffTarget::find($id);
        return view("staff.staff_target.show",compact('data'));
    }
    public function destroy(Request $request,$id)
    {
        $data = StaffTarget::find($id);
        $data->delete();
        return response()->json(["success" => "Record deleted.!"]);
    }

}