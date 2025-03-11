<?php

namespace App\Http\Controllers\staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Oppertunity_product;
use App\Models\StaffTarget;
use Carbon\Carbon;
use App\Product;
use App\Brand;
use App\Contact_person;
use App\Contract;
use App\ContractProduct;
use App\CoordinatorPermission;
use App\Dailyclosing_expence;
use App\District;
use App\Hosdeparment;
use App\Ib;
use App\Modality;
use App\Models\ReportPermission;
use App\User;
use App\MsaContract;
use App\Oppertunity;
use App\PmDetails;
use App\Quotehistory;
use App\SalesContract;
use App\Service;
use App\Staff;
use App\StaffCategory;
use App\State;
use App\Task;
use App\Task_comment;
use App\Task_comment_expence;
use Carbon\CarbonPeriod;

class StaffReportController extends Controller
{
    protected $guard = 'staff';
    
    public function __construct(){

    }
 
    public function index(Request $request){

        if($request->ajax()){

            $staffm=Staff::where("id",">","0");

            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }
            
            $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                $modality = "";

                if(!empty($request->modality))
                {
                    $modality = $request->modality;
                }

                $state="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }

                $accessoriesbrandids   = StaffTarget::where('sale_product_type', 'accessories')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')]);

                if(!empty($modality))
                {
                    $accessoriesbrandids->whereIn('modality_id',$modality);
                }

                $accessoriesbrandids = $accessoriesbrandids->groupBy('brand_id')->pluck('brand_id')->all();


                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $accessoriesbrandids = $brand_id;
                }

                $accessoriesachives = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {$qry->where('staff_id', $staff_id)
                    ->whereBetween("won_date", [$fromdate, $todate])
                    ->whereNotIn('commission_status', ['New Orders', "Initial Check", 'Technical Approval'])
                    ->where('deal_stage', 8);

                    if (!empty($state)) {

                        $qry->where('state', $state);
                    }

                    if (!empty($district)) {

                        $qry->where('district', $district);
                    }

                })
                    ->whereIn('product_id', Product::where('category_type_id', 3)
                    ->whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
                    ->sum('sale_amount');


                $accessoriescommeted = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {
                    $qry->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate])
                    ->where('deal_stage', "!=", 8)
                    ->where('order_forcast_category', 5);

                    if (!empty($state)) {

                        $qry->where('state', $state);
                    }

                    if (!empty($district)) {

                        $qry->where('district', $district);
                    }

                })
                    ->whereIn('product_id', Product::where('category_type_id', 1)
                    ->whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
                    ->sum('sale_amount');

                $accessoriestargets = StaffTarget::where('sale_product_type', 'accessories')->where('staff_id', $staff_id)->where(function ($qry) use ($fromdate, $todate) {
                    $current = Carbon::parse($fromdate->format("Y-m-d"));
                    while ($current->lte($todate)) {
                        $qry->orWhere(function ($iqry) use ($current) {
                            $iqry->where('target_year', $current->format('Y'))->where("target_month", $current->format("m"));
                        });
                        $current->addMonth();
                    }
                });

                if($accessoriesbrandids)
                {
                    $accessoriestargets->whereIn("brand_id",$accessoriesbrandids);
                }

                if(!empty($modality))
                {
                    $accessoriestargets->whereIn('modality_id',$modality);
                }

                $accessoriestargets = $accessoriestargets->sum('target_amount');


                // $commission = Oppertunity_product::where('approve_status','Y')
                
                // ->whereHas('oppertunity',function($qry)use($staff_id,$fromdate,$todate){ $qry
                //     ->where('staff_id',$staff_id)
                //     ->whereBetween("won_date", [$fromdate, $todate])
                //     ->where('deal_stage',8); 
                //         }
                //     )
                //     ->whereIn('product_id',Product::where('category_type_id',3)

                //     ->whereIn('brand_id',$accessoriesbrandids)->pluck('id')

                //     ->all())->sum('commission');


                $products_mspids = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {
                    $qry->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);
       
                    if (!empty($state)) {

                        $qry->where('state', $state);
                    }

                    if (!empty($district)) {

                        $qry->where('district', $district);
                    }
                    
                })
                ->whereIn('product_id', Product::whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
                ->where('approve_status','Y')
                ->pluck('product_id');
        

                $products = Product::whereIn('id',$products_mspids)->get();

                $msp =0;

                $commission = 0;

                foreach($products as $product)
                {
                    $msp += optional($product->productmsp()->latest()->first())->pro_msp ?? 0;

                    $unit_price = optional($product->productmsp()->latest()->first())->cost??0;
                    $trans_cost = optional($product->productmsp()->latest()->first())->trans_cost??10;
                    $customs_cost = optional($product->productmsp()->latest()->first())->customs_cost??10;
                    $other_cost = optional($product->productmsp()->latest()->first())->other_cost??1;
                    $profit = optional($product->productmsp()->latest()->first())->profit??15;
                    $quote = optional($product->productmsp()->latest()->first())->quote??20;
                    $online = optional($product->productmsp()->latest()->first())->percent_online??15;
                    $discount = optional($product->productmsp()->latest()->first())->discount??1;
                    $incentive = optional($product->productmsp()->latest()->first())->incentive??0;

                    $total_per = $trans_cost + $customs_cost + $other_cost;

                    $total_peramount = ($unit_price * $total_per) / 100;

                    $tot_price = $unit_price + $total_peramount;
                    $tot_price = round($tot_price, 2);

                    $propse_val = ($tot_price * $profit) / 100;
                    $propse_val = round($propse_val, 2);

                    $propse_val = $tot_price + $propse_val;

                    $discount_tot = optional($product->productmsp()->latest()->first())->discount_price??0;

                    $online_tot = optional($product->productmsp()->latest()->first())->online_price??0;

                    $prop_total = $propse_val - $tot_price;
                    $incentive_amount = ($incentive * $prop_total) / 10;

                    $incentive_amount = round($incentive_amount, 2) * 1;

                    $commission += $incentive_amount;
                }

                $msp =  round($msp, 2);

                $lost_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {
                        $qry->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate])
                        ->where('deal_stage',6)  ;

                        if (!empty($state)) {

                            $qry->where('state', $state);
                        }

                        if (!empty($district)) {

                            $qry->where('district', $district);
                        }
                        
                    })
                    ->whereIn('product_id', Product::where('category_type_id', 1)
                    ->whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
                    ->sum('sale_amount');

                $accessoriescommission = round($commission, 2);


                $accessoriestargets = max(0, $accessoriestargets);

                $accessoriesachives = max(0, $accessoriesachives);

                $accessoriescommeted = max(0, $accessoriescommeted);

                $accessoriescommission = max(0, $accessoriescommission);
                
                $lost_oppertunity = max(0, $lost_oppertunity);
                
                
                $data[]=[
                    'staff'=>$stf,
                    'accessories'=>[
                    'target'=>$accessoriestargets,
                    'achives'=>$accessoriesachives,
                    'commeted'=>$accessoriescommeted,
                    'todo'       => strval(max(0, $accessoriestargets - $accessoriesachives)),
                    'msp'=>$msp,
                    'commission'=> $accessoriescommission,
                    'lost'=>$lost_oppertunity,
                    ]
                ];
            }
            return response()->json($data);
            
        }

        $staff_is = session('STAFF_ID');

        $brandis = StaffTarget::where('staff_id', $staff_is)->groupBy('brand_id')->pluck('brand_id')->all();

        $brand = Brand::whereIn('id',$brandis)->get();

        $modality = Modality::get();

        $states = State::get();

        $districts = District::get();

        $staffs_per = CoordinatorPermission::where('type', 'report')->where('staff_id',$staff_is)->first();
  
        $report_per = ReportPermission::where('permission_id',$staffs_per->id)->pluck('staff_id')->unique()->toArray();

        $staff_reports = Staff::whereIn('id',$report_per)->where('status', 'Y')->orderBy('name', 'asc')->get();


        $staff_category = StaffCategory::get();


        return view('staff.report.index',compact('staff_category','districts','states','brand','modality','staff_reports'));
    }

    public function staff_equip_sales(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");

            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

            $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }

                $staff_id=$stf->id;


                $modality = "";

                if(!empty($request->modality))
                {
                    $modality = $request->modality;
                }

                $state="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }

                $equipmentbrandids   = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')]);

                if(!empty($modality))
                {
                    $equipmentbrandids->whereIn('modality_id',$modality);
                }

                $equipmentbrandids = $equipmentbrandids->groupBy('brand_id')->pluck('brand_id')->all();


                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $equipmentbrandids = $brand_id;
                }

                $equipmentsachives = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {$qry->where('staff_id', $staff_id)
                    ->whereBetween("won_date", [$fromdate, $todate])
                    ->whereNotIn('commission_status', ['New Orders', "Initial Check", 'Technical Approval'])
                    ->where('deal_stage', 8);

                    if (!empty($state)) {

                        $qry->where('state', $state);
                    }

                    if (!empty($district)) {

                        $qry->where('district', $district);
                    }

                })
                    ->whereIn('product_id', Product::where('category_type_id', 1)
                    ->whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
                    ->sum('sale_amount');


                $equipmentcommeted = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {
                    $qry->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate])
                    ->where('deal_stage', "!=", 8)
                    ->where('order_forcast_category', 5);

                    if (!empty($state)) {

                        $qry->where('state', $state);
                    }

                    if (!empty($district)) {

                        $qry->where('district', $district);
                    }

                })
                    ->whereIn('product_id', Product::where('category_type_id', 1)
                    ->whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
                    ->sum('sale_amount');

                $equipmenttargets = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->where(function ($qry) use ($fromdate, $todate) {
                    $current = Carbon::parse($fromdate->format("Y-m-d"));
                    while ($current->lte($todate)) {
                        $qry->orWhere(function ($iqry) use ($current) {
                            $iqry->where('target_year', $current->format('Y'))->where("target_month", $current->format("m"));
                        });
                        $current->addMonth();
                    }
                });

                if($equipmentbrandids)
                {
                    $equipmenttargets->whereIn("brand_id",$equipmentbrandids);
                }

                if(!empty($modality))
                {
                    $equipmenttargets->whereIn('modality_id',$modality);
                }

                $equipmenttargets = $equipmenttargets->sum('target_amount');


                // $commission = Oppertunity_product::where('approve_status','Y')
                
                // ->whereHas('oppertunity',function($qry)use($staff_id,$fromdate,$todate){ $qry
                //     ->where('staff_id',$staff_id)
                //     ->whereBetween("won_date", [$fromdate, $todate])
                //     ->where('deal_stage',8); 
                //         }
                //     )
                //     ->whereIn('product_id',Product::where('category_type_id',1)

                //     ->whereIn('brand_id',$equipmentbrandids)->pluck('id')

                //     ->all())->sum('commission');

                
                $products_mspids = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {
                    $qry->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);
       
                    if (!empty($state)) {

                        $qry->where('state', $state);
                    }

                    if (!empty($district)) {

                        $qry->where('district', $district);
                    }
                    
                })
                ->whereIn('product_id', Product::whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
                ->where('approve_status','Y')
                ->pluck('product_id');
        

                $products = Product::whereIn('id',$products_mspids)->get();

                $msp =0;

                $commission =0;

                foreach($products as $product)
                {
                    $msp += optional($product->productmsp()->latest()->first())->pro_msp ?? 0;

                    $unit_price = optional($product->productmsp()->latest()->first())->cost??0;
                    $trans_cost = optional($product->productmsp()->latest()->first())->trans_cost??10;
                    $customs_cost = optional($product->productmsp()->latest()->first())->customs_cost??10;
                    $other_cost = optional($product->productmsp()->latest()->first())->other_cost??1;
                    $profit = optional($product->productmsp()->latest()->first())->profit??15;
                    $quote = optional($product->productmsp()->latest()->first())->quote??20;
                    $online = optional($product->productmsp()->latest()->first())->percent_online??15;
                    $discount = optional($product->productmsp()->latest()->first())->discount??1;
                    $incentive = optional($product->productmsp()->latest()->first())->incentive??0;

                    $total_per = $trans_cost + $customs_cost + $other_cost;

                    $total_peramount = ($unit_price * $total_per) / 100;

                    $tot_price = $unit_price + $total_peramount;
                    $tot_price = round($tot_price, 2);

                    $propse_val = ($tot_price * $profit) / 100;
                    $propse_val = round($propse_val, 2);

                    $propse_val = $tot_price + $propse_val;

                    $discount_tot = optional($product->productmsp()->latest()->first())->discount_price??0;

                    $online_tot = optional($product->productmsp()->latest()->first())->online_price??0;

                    $prop_total = $propse_val - $tot_price;
                    $incentive_amount = ($incentive * $prop_total) / 10;

                    $incentive_amount = round($incentive_amount, 2) * 1;

                    $commission += $incentive_amount;
                }

                $msp =  round($msp, 2);


                // $lost_oppertunity = Oppertunity::where('deal_stage','6')
                //                 ->where('staff_id',$staff_id)
                //                 ->whereBetween("won_date", [$fromdate, $todate])
                //                 ->whereIn('brand_id',$equipmentbrandids)->count();

                $lost_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {
                        $qry->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate])
                        ->where('deal_stage',6)  ;

                        if (!empty($state)) {

                            $qry->where('state', $state);
                        }

                        if (!empty($district)) {

                            $qry->where('district', $district);
                        }
                        
                    })
                    ->whereIn('product_id', Product::where('category_type_id', 1)
                    ->whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
                    ->sum('sale_amount');

                $equipmentcommission = round($commission, 2);


                $equipmenttargets = max(0, $equipmenttargets);

                $equipmentsachives = max(0, $equipmentsachives);

                $equipmentcommeted = max(0, $equipmentcommeted);

                $equipmentcommission = max(0, $equipmentcommission);
                
                $lost_oppertunity = max(0, $lost_oppertunity);
                
                
                $data[]=[
                    'staff'=>$stf,
                    'equipment'=>[
                    'target'=>$equipmenttargets,
                    'achives'=>$equipmentsachives,
                    'commeted'=>$equipmentcommeted,
                    'todo'       => strval(max(0, $equipmenttargets - $equipmentsachives)),
                    'msp'=>$msp,
                    'commission'=> $equipmentcommission,
                    'lost'=>$lost_oppertunity,
                    ]
                ];
            }
            return response()->json($data);
        }
    }


    public function staff_msa_service(Request $request)
    {


        if($request->ajax()){

                $staffm=Staff::where("id",">","0");

                if(!empty($request->engineer_id)){

                    $staffm->whereIn('id',$request->engineer_id);
                }
                else
                {
                    $staffm->where('id',$request->staff);
                    
                }

                $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }

            $i = $fromdate->diffInMonths($todate);

            $categories[] = (object) [
                'start_date'  => $fromdate,
                'end_date'    => $todate,
                'months_diff' => $i,
            ];
                    

            $staff_id=$stf->id;
        
            $today         = now()->startOfDay();
        
            $ibCount = Ib::where('staff_id', $staff_id)->count();

            $state="";

            if(!empty($request->state))
            {
                $state = $request->state;
            }

            $district ="";

            if(!empty($request->district))
            {
                $district = $request->district;
            }


            $warrantyIbCount = ContractProduct::whereIn('contract_id', function ($query) {
                $query->select('id')
                    ->from('contracts')
                    ->where('contract_type', 'Warranty');
            })
                ->whereIn('oppertunity_id', function ($query) use ($staff_id,$fromdate,$todate,$state,$district) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);

                        if (!empty($state)) {

                            $query->where('state', $state);
                        }

                        if (!empty($district)) {

                            $query->where('district', $district);
                        }
        
                })
                ->count();

            $today              = now()->startOfDay()->format('Y-m-d');
            $warrantyendIbCount = ContractProduct::whereIn('contract_id', function ($query) use ($today) {
                $query->select('id')
                    ->from('contracts')
                    ->where('contract_type', 'Warranty')
                    ->whereDate('contract_end_date', '<', $today);
            })
                ->whereIn('oppertunity_id', function ($query) use ($staff_id,$fromdate,$todate,$district) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);

                        if (!empty($state)) {

                            $query->where('state', $state);
                        }

                        if (!empty($district)) {

                            $query->where('district', $district);
                        }
                })
                ->count();

             $msa_revenue = ContractProduct::whereIn('contract_id', function ($query) use ($fromdate,$todate) {
                $query->select('id')
                    ->from('contracts')
                    // ->whereDate('contract_end_date', '<', $today)
                    ->whereBetween("contract_end_date", [$fromdate, $todate]);
            })
                ->whereIn('oppertunity_id', function ($query) use ($staff_id,$state,$fromdate,$todate,$district) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);

                        if (!empty($state)) {

                            $query->where('state', $state);
                        }

                        if (!empty($district)) {

                            $query->where('district', $district);
                        }
                })
                ->sum('revanue');


                

            $adjustedIbCount = $ibCount - $warrantyIbCount + $warrantyendIbCount;

            // achieves
            $achievedCount = 0;

            $createdCount = MsaContract::whereHas('msaoppertunity', function ($query) use ($staff_id,$fromdate,$todate,$state,$district) {

                $query->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);

                if (!empty($state)) {

                    $query->where('state', $state);
                }

                if (!empty($district)) {

                    $query->where('district', $district);
                }
            })
                ->where('contract_status', 'created')
                ->count();

            $warrantyCount = MsaContract::whereHas('msaoppertunity', function ($query) use ($staff_id,$fromdate,$todate,$state,$district) {
                $query->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);

                if (!empty($state)) {

                    $query->where('state', $state);
                }

                if (!empty($district)) {

                    $query->where('district', $district);
                }

            })
                ->where('contract_status', 'created')
                ->whereIn('id', function ($query) use ($staff_id) {
                    $query->select('msa_contract_id')
                        ->from('contracts')
                        ->where('contract_type', 'Warranty');
                })
                
                ->count();

            $today = now()->startOfDay()->format('Y-m-d');

            $warrantyendCount = MsaContract::whereHas('msaoppertunity', function ($query) use ($staff_id,$fromdate,$todate,$state,$district) {
                $query->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);

                if (!empty($state)) {

                    $query->where('state', $state);
                }

                if (!empty($district)) {

                    $query->where('district', $district);
                }

            })
                ->where('contract_status', 'created')
                ->whereIn('id', function ($query) use ($staff_id, $today) {
                    $query->select('msa_contract_id')
                        ->from('contracts')
                        ->where('contract_type', '!=', 'Warranty')
                        ->whereDate('contract_end_date', '<', $today);
                })
               
                ->count();

            if ($warrantyCount > 0) {
                $achievedCount = $createdCount - $warrantyCount;
            }

            if ($warrantyendCount > 0) {
                $achievedCount -= $warrantyendCount;
            }



            $contractcount = [];
            foreach ($categories as $catmonths) {
                if ($catmonths->end_date != 'infinity') {
                    $contracts = MsaContract::with('msacontract')->whereHas('msaoppertunity', function ($query) use($state,$fromdate,$todate,$staff_id,$district) {
                        $query->where('contract_status', 'created')->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);

                        if (!empty($state)) {

                            $query->where('state', $state);
                        }

                         if (!empty($district)) {

                            $query->where('district', $district);
                        }

                    })
                        ->whereIn('id', function ($query) use ($catmonths) {
                            $query->select('msa_contract_id')
                                ->from('contracts')
                                ->whereDate('contract_end_date', '>=', $catmonths->start_date)
                                ->whereDate('contract_end_date', '<=', $catmonths->end_date);
                        })
                       
                        ->count();


                        $msa_commeted = MsaContract::with('msacontract')->whereHas('msaoppertunity', function ($query) use($state,$fromdate,$todate,$staff_id,$district) {

                            $query->where('contract_status', 'created')
                            ->where('staff_id', $staff_id)
                            ->whereBetween("won_date", [$fromdate, $todate])
                            ->where('deal_stage', "!=", 8)
                            ->where('order_forcast_category', 5);

                            if (!empty($state)) {

                                $query->where('state', $state);
                            }
                            if (!empty($district)) {

                                $query->where('district', $district);
                            }

                        })
                            ->whereIn('id', function ($query) use ($catmonths) {
                                $query->select('msa_contract_id')
                                    ->from('contracts')
                                    ->whereDate('contract_end_date', '>=', $catmonths->start_date)
                                    ->whereDate('contract_end_date', '<=', $catmonths->end_date);
                            })
                           
                            ->count();


                } else {
                    $contracts = MsaContract::with('msacontract')->whereHas('msaoppertunity', function ($query) use($state,$fromdate,$todate,$staff_id,$district) {
                        $query->where('contract_status', 'created')
                        ->where('staff_id', $staff_id)
                        ->whereBetween("won_date", [$fromdate, $todate]);

                        if (!empty($state)) {

                            $query->where('state', $state);
                        }

                        if (!empty($district)) {

                            $query->where('district', $district);
                        }

                    })
                        ->whereIn('id', function ($query) use ($catmonths) {
                            $query->select('msa_contract_id')
                                ->from('contracts')
                                ->whereDate('contract_end_date', '<=', $catmonths->start_date);
                        })
                       
                        ->count();

                        $msa_commeted = MsaContract::with('msacontract')->whereHas('msaoppertunity', function ($query) use($state,$fromdate,$todate,$staff_id,$district) {
                            $query->where('contract_status', 'created')
                            ->where('staff_id', $staff_id)
                            ->whereBetween("won_date", [$fromdate, $todate])
                            ->where('deal_stage', "!=", 8)
                            ->where('order_forcast_category', 5);

                            if (!empty($state)) {

                                $query->where('state', $state);
                            }

                            if (!empty($district)) {

                                $query->where('district', $district);
                            }

                        })
                        ->whereIn('id', function ($query) use ($catmonths) {
                            $query->select('msa_contract_id')
                                ->from('contracts')
                                ->whereDate('contract_end_date', '<=', $catmonths->start_date);
                        })
                    
                            ->count();
                }

                $contractcount[] = (object) [
                    'period' => $catmonths->months_diff,
                    'count'  => $contracts,
                ];
            }

            $renewal = [];
            $expired = [];
            $hbs     = [];

            foreach ($contractcount as $contract) {
                if ($contract->period == -2 || $contract->period == -1) {
                    $renewal[] = " {$contract->period} mon : $contract->count";
                } elseif ($contract->period >= 1 && $contract->period <= 6) {
                    $expired[] = " {$contract->period} mon : $contract->count";
                } elseif ($contract->period == 7) {
                    $hbs[] = " 6+ : $contract->count";
                }
            }

            $staff = Staff::find($staff_id);

            $data[]=[
                'staff'=>$stf,
                'mas_contract'=>[
                'target'=> strval(max(0, $adjustedIbCount)),
                'achives' => strval(max(0, $achievedCount)),
                'todo'       => strval(max(0, $adjustedIbCount - $achievedCount)),
                'cr_percentage'=> 'cr',
                'revenue'=> $msa_revenue,
                "commeted"=>$msa_commeted,
                'hbs'        => implode(", ", $hbs),
                'expired'    => implode(", ", $expired),
                ]
            ];

            }
        
        return response()->json($data);

        }
    }

    
    public function staff_sales_parts(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");

             if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }


            $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                $modality = "";

                if(!empty($request->modality))
                {
                    $modality = $request->modality;
                }


                // $equipmentbrandids   = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])->groupBy('brand_id')->pluck('brand_id')->all();
                // $accessoriesbrandids = StaffTarget::where('sale_product_type', 'accessories')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])->groupBy('brand_id')->pluck('brand_id')->all();

                $brandids = StaffTarget::where('staff_id', $staff_id)
                ->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])
                ->when(!empty($modality), function($query) use ($modality) {
                    return $query->whereIn('modality_id', $modality);
                })
                ->groupBy('brand_id')
                ->pluck('brand_id')
                ->toArray();
            

                // if(!empty($request->brand_id))
                // {
                //     $brand_id = $request->brand_id;

                //     $brandids = $brand_id;
                // }

                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }


                $part_brand_ids = Product::where('category_type_id', 2)
                                ->whereIn('brand_id', $brandids)
                                ->pluck('brand_id')->toArray();

                


                // $equipmentstargets = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->where(function ($qry) use ($fromdate, $todate) {
                //     $current = Carbon::parse($fromdate->format("Y-m-d"));
                //     while ($current->lte($todate)) {
                //         $qry->orWhere(function ($iqry) use ($current) {
                //             $iqry->where('target_year', $current->format('Y'))->where("target_month", $current->format("m"));
                //         });
                //         $current->addMonth();
                //     }
                // })->sum('target_amount');

                $sales_part_achives = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state, $district)
                     {$qry->where('staff_id', $staff_id)
                    ->whereBetween("won_date", [$fromdate, $todate])
                    ->whereNotIn('commission_status', ['New Orders', "Initial Check", 'Technical Approval'])
                    ->where('deal_stage', 8);

                        if (!empty($state)) {

                            $qry->where('state', $state);
                        }

                        if (!empty($district)) {

                            $qry->where('district', $district);
                        }

                    })
                    ->whereIn('product_id', Product::where('category_type_id', 2)
                    ->whereIn('brand_id', $brandids)->pluck('id')->all())
                    ->sum('sale_amount');


                $sales_part_commeted = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {
                    $qry->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate])
                        ->where('deal_stage', "!=", 8)
                        ->where('order_forcast_category', 5);

                        if (!empty($state)) {

                            $qry->where('state', $state);
                        }

                        if (!empty($district)) {

                            $qry->where('district', $district);
                        }

                    })
                    ->whereIn('product_id', Product::where('category_type_id', 2)
                    ->whereIn('brand_id', $brandids)->pluck('id')->all())
                    ->sum('sale_amount');

                $sales_part_targets = StaffTarget::where('staff_id', $staff_id)->where(function ($qry) use ($fromdate, $todate) {
                    $current = Carbon::parse($fromdate->format("Y-m-d"));
                    while ($current->lte($todate)) {
                        $qry->orWhere(function ($iqry) use ($current) {
                            $iqry->where('target_year', $current->format('Y'))->where("target_month", $current->format("m"));
                        });
                        $current->addMonth();
                    }
                });

                if(!empty($modality))
                {
                    $sales_part_targets->whereIn('modality_id',$modality);
                }
                
            
                $sales_part_targets = $sales_part_targets->whereIn('brand_id', $part_brand_ids)
                ->sum('target_amount');

                // $products = Product::whereIn('brand_id',$accessoriesbrandids);


                $commissions = SalesContract::whereIn('oppertunity_id', function ($query) use ($staff_id,$fromdate,$todate,$state,$district) {
                    $query->select('id')
                        ->from('oppertunities')
                        ->where('staff_id', $staff_id)
                        ->whereBetween("won_date", [$fromdate, $todate]);

                        if (!empty($state)) {

                            $query->where('state', $state);
                        }

                        if (!empty($district)) {

                            $query->where('district', $district);
                        }

                })->get()
                    ->where('contract_status', 'verified');
        
                // $commission = 0;
        
                // foreach ($commissions as $salescontract) {
        
                //     $contract         = Contract::find($salescontract->contract_id);
                //     $contractproducts = ContractProduct::where('contract_id', $contract->id)
                //     ->whereIn('equipment_id', Product::where('category_type_id', 2)
                //     ->whereIn('brand_id', $brandids)->pluck('id')->all())->get();
        
                //     foreach ($contractproducts as $value) {
        
                //         $unit_price   = optional($value->equipment->productmsp()->latest()->first())->cost ?? 0;
                //         $trans_cost   = optional($value->equipment->productmsp()->latest()->first())->trans_cost ?? 10;
                //         $customs_cost = optional($value->equipment->productmsp()->latest()->first())->customs_cost ?? 10;
                //         $other_cost   = optional($value->equipment->productmsp()->latest()->first())->other_cost ?? 1;
                //         $profit       = optional($value->equipment->productmsp()->latest()->first())->profit ?? 15;
                //         $quote        = optional($value->equipment->productmsp()->latest()->first())->quote ?? 20;
                //         $online       = optional($value->equipment->productmsp()->latest()->first())->percent_online ?? 15;
                //         $discount     = optional($value->equipment->productmsp()->latest()->first())->discount ?? 1;
                //         $incentive    = optional($value->equipment->productmsp()->latest()->first())->incentive ?? 0;
        
                //         $total_per = $trans_cost + $customs_cost + $other_cost;
        
                //         $total_peramount = ($unit_price * $total_per) / 100;
        
                //         $tot_price = $unit_price + $total_peramount;
                //         $tot_price = round($tot_price, 2);
        
                //         $propse_val = ($tot_price * $profit) / 100;
                //         $propse_val = round($propse_val, 2);
        
                //         $propse_val = $tot_price + $propse_val;
        
                //         $discount_tot = optional($value->equipment->productmsp()->latest()->first())->discount_price ?? 0;
        
                //         $online_tot = optional($value->equipment->productmsp()->latest()->first())->online_price ?? 0;
        
                //         $prop_total       = $propse_val - $tot_price;
                //         $incentive_amount = ($incentive * $prop_total) / 10;
        
                //         $commission += $incentive_amount;
                //     }
                // }

                // $commission = Oppertunity_product::where('approve_status','Y')
                
                // ->whereHas('oppertunity',function($qry)use($staff_id,$fromdate,$todate){ $qry
                //     ->where('staff_id',$staff_id)
                //     ->whereBetween("won_date", [$fromdate, $todate])
                //     ->where('deal_stage',8); 
                //         }
                //     )
                //     ->whereIn('product_id',Product::where('category_type_id',2)

                //     ->whereIn('brand_id',$brandids)->pluck('id')

                //     ->all())->sum('commission');
        

                    $products_mspids = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) {
                        $qry->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate]);
           
                        if (!empty($state)) {
    
                            $qry->where('state', $state);
                        }
    
                        if (!empty($district)) {
    
                            $qry->where('district', $district);
                        }
                        
                    })
                    ->whereIn('product_id', Product::whereIn('brand_id',$brandids)->pluck('id')->all())
                    ->where('approve_status','Y')
                    ->pluck('product_id')->toArray();
            
                $products = Product::where('category_type_id', 2)->whereIn('id',$products_mspids)->get();

                $msp =0;

                $commission=0;

                foreach($products as $product)
                {
                    $msp += optional($product->productmsp()->latest()->first())->pro_msp ?? 0;

                    $unit_price = optional($product->productmsp()->latest()->first())->cost??0;
                    $trans_cost = optional($product->productmsp()->latest()->first())->trans_cost??10;
                    $customs_cost = optional($product->productmsp()->latest()->first())->customs_cost??10;
                    $other_cost = optional($product->productmsp()->latest()->first())->other_cost??1;
                    $profit = optional($product->productmsp()->latest()->first())->profit??15;
                    $quote = optional($product->productmsp()->latest()->first())->quote??20;
                    $online = optional($product->productmsp()->latest()->first())->percent_online??15;
                    $discount = optional($product->productmsp()->latest()->first())->discount??1;
                    $incentive = optional($product->productmsp()->latest()->first())->incentive??0;

                    $total_per = $trans_cost + $customs_cost + $other_cost;

                    $total_peramount = ($unit_price * $total_per) / 100;

                    $tot_price = $unit_price + $total_peramount;
                    $tot_price = round($tot_price, 2);

                    $propse_val = ($tot_price * $profit) / 100;
                    $propse_val = round($propse_val, 2);

                    $propse_val = $tot_price + $propse_val;

                    $discount_tot = optional($product->productmsp()->latest()->first())->discount_price??0;

                    $online_tot = optional($product->productmsp()->latest()->first())->online_price??0;

                    $prop_total = $propse_val - $tot_price;
                    $incentive_amount = ($incentive * $prop_total) / 10;

                    $incentive_amount = round($incentive_amount, 2) * 1;

                    $commission += $incentive_amount;
                }

                $msp =  round($msp, 2);


                // $lost_oppertunity = Oppertunity::where('deal_stage','6')
                //                 ->where('staff_id',$staff_id)
                //                 ->whereBetween("won_date", [$fromdate, $todate])
                //                 ->whereIn('brand_id',$equipmentbrandids)->count();

                $lost_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state, $district) {

                        $qry->where('staff_id', $staff_id)->whereBetween("won_date", [$fromdate, $todate])
                        ->where('deal_stage',6);

                        if (!empty($state)) {

                            $qry->where('state', $state);
                        }

                        if (!empty($district)) {

                            $qry->where('district', $district);
                        }

                    })
                    ->whereIn('product_id', Product::where('category_type_id', 2)
                    ->whereIn('brand_id', $brandids)->pluck('id')->all())
                    ->sum('sale_amount');

                $sales_part_commission = round($commission, 2);


                $sales_part_targets = max(0,  $sales_part_targets);

                $sales_part_achives = max(0,  $sales_part_achives);

                $sales_part_commeted = max(0,  $sales_part_commeted);

                $sales_part_commission = max(0,  $sales_part_commission);
                
                $lost_oppertunity = max(0, $lost_oppertunity);
                
                
                $data[]=[
                    'staff'=>$stf,
                    'equipment'=>[
                    'target'=>$sales_part_targets,
                    'achives'=>$sales_part_achives,
                    'commeted'=>$sales_part_commeted,
                    'todo'       => strval(max(0, $sales_part_targets - $sales_part_achives)),
                    'msp'=>$msp,
                    'commission'=> $sales_part_commission,
                    'lost'=>$lost_oppertunity,
                    ]
                ];
            }
            return response()->json($data);
        }
    }
    
    public function oppertunity_accesseries(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");

            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

           $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;

                $modality = "";

                if(!empty($request->modality))
                {
                    $modality = $request->modality;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }


                // $equipmentbrandids   = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])->groupBy('brand_id')->pluck('brand_id')->all();
        

                $accessoriesbrandids = StaffTarget::where('sale_product_type', 'accessories')
                    ->where('staff_id', $staff_id)
                    ->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')]);

                if (!empty($modality)) {
                    $accessoriesbrandids->whereIn('modality_id', $modality);
                }

                $accessoriesbrandids = $accessoriesbrandids->groupBy('brand_id')
                    ->pluck('brand_id')
                    ->toArray();



                $brandids = StaffTarget::where('staff_id', $staff_id)
                    ->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')]);
                
                if (!empty($modality)) {
                    $brandids->whereIn('modality_id', $modality);
                }
                
                // Ensure you correctly retrieve a flat array of brand IDs
                $brandids = $brandids->groupBy('brand_id')
                    ->pluck('brand_id')
                    ->toArray();
                    

                
                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $accessoriesbrandids = $brand_id;
                }

                $state="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $part_brand_ids = Product::where('category_type_id', 2)
                ->whereIn('brand_id', $brandids) // $brandids should be a simple array now
                ->pluck('brand_id')
                ->toArray();

                

                $total_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                     {$qry->where('staff_id', $staff_id)
                    ->whereBetween("won_date", [$fromdate, $todate])->where('type',1);

                        if (!empty($state)) {

                            $qry->where('state', $state);
                        }

                        if (!empty($district)) {

                            $qry->where('district', $district);
                        }

                   
                    })
                    ->whereIn('product_id', Product::
                    whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
                    ->sum('sale_amount');


                $new_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
               ->whereBetween("won_date", [$fromdate, $todate])->where('type',1)
                ->whereHas('oppertunityquote', function ($query) {

                    $query->where('approved_status', '!=', 'Y');
                });

                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }

                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

              
               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
               ->sum('sale_amount');


                $Committed_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
               ->whereBetween("won_date", [$fromdate, $todate])
               ->where('deal_stage', "!=", 8)->where('type',1)
               ->where('order_forcast_category', 5);
               
                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }

                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

              
               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
               ->sum('sale_amount');

             
                $Committed_with_risk_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
               ->whereBetween("won_date", [$fromdate, $todate])
               ->where('deal_stage', "!=", 8)->where('type',1)
               ->where('order_forcast_category', 4);
               
                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }

                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

              
               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
               ->sum('sale_amount');

            
                $open_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
               ->whereBetween("won_date", [$fromdate, $todate])
               ->where('deal_stage', "!=", 8)->where('type',1)
               ->where('order_forcast_category', 2);
               
                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }

                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

              
               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
               ->sum('sale_amount');


                $upside_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
               ->whereBetween("won_date", [$fromdate, $todate])
               ->where('deal_stage', "!=", 8)->where('type',1)
               ->where('order_forcast_category', 3);
               
                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }

                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

              
               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $accessoriesbrandids)->pluck('id')->all())
               ->sum('sale_amount');

                    $total_oppertunity = round($total_oppertunity, 2);

                    $new_oppertunity = round($new_oppertunity, 2);

                $data[]=[
                    'staff'=>$stf,
                    'opp_accesseries'=>[
                    'total_oppertunity'=>max(0,  $total_oppertunity),
                    'new_oppertunity'=> max(0,  $new_oppertunity),
                    'Committed_nos'=> max(0,  $Committed_nos),
                    'Committed_with_risk_nos' =>  max(0,  $Committed_with_risk_nos),
                    'open_nos'=> max(0,  $open_nos),
                    'upside_nos'=>  max(0,  $upside_nos),
                  
                    ]
                ];
            }
            return response()->json($data);
        }
    }

    public function oppertunity_equipment(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");

            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

             $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;

                $modality = Modality::pluck('id')->all();

                if(!empty($request->modality))
                {
                    $modality = $request->modality;
                }


                $state = "";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }

                // $equipmentbrandids   = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])->groupBy('brand_id')->pluck('brand_id')->all();
                $equipmentbrandids = StaffTarget::whereIn('modality_id',$modality)->where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])->groupBy('brand_id')->pluck('brand_id')->all();

                $brandids            = StaffTarget::whereIn('modality_id',$modality)->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])
                                        ->groupBy('brand_id')
                                        ->pluck('brand_id')->all();

                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $equipmentbrandids = $brand_id;
                }

                $part_brand_ids = Product::where('category_type_id',2)->whereIn('brand_id',$brandids)->pluck('brand_id')->all();
                

                $total_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                     {$qry->where('staff_id', $staff_id)
                    ->whereBetween("won_date", [$fromdate, $todate])->where('type',1);

                        if (!empty($state)) {

                            $qry->where('state', $state);
                        }
                        
                        if (!empty($district)) {

                            $qry->where('district', $district);
                        }

                    })
                    ->whereIn('product_id', Product::
                    whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
                    ->sum('sale_amount');


                $new_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
               ->whereBetween("won_date", [$fromdate, $todate])
               ->where('type',1)
                ->whereHas('oppertunityquote', function ($query) {

                    $query->where('approved_status', '!=', 'Y');
                });

                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }
                   
                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
               ->sum('sale_amount');


                $Committed_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                ->whereBetween("won_date", [$fromdate, $todate])
                ->where('deal_stage', "!=", 8)
                ->where('type',1)->where('order_forcast_category', 5);
                

                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }
                   
                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
               ->sum('sale_amount');

             
                $Committed_with_risk_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                ->whereBetween("won_date", [$fromdate, $todate])
                ->where('deal_stage', "!=", 8)
                ->where('type',1)->where('order_forcast_category', 4);
                

                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }
                   
                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
               ->sum('sale_amount');

            
                $open_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                ->whereBetween("won_date", [$fromdate, $todate])
                ->where('deal_stage', "!=", 8)
                ->where('type',1)->where('order_forcast_category', 2);
                

                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }
                   
                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
               ->sum('sale_amount');


                $upside_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                ->whereBetween("won_date", [$fromdate, $todate])
                ->where('deal_stage', "!=", 8)
                ->where('type',1)->where('order_forcast_category', 3);
                

                   if (!empty($state)) {

                       $qry->where('state', $state);
                   }
                   
                   if (!empty($district)) {

                       $qry->where('district', $district);
                   }

               })
               ->whereIn('product_id', Product::
               whereIn('brand_id', $equipmentbrandids)->pluck('id')->all())
               ->sum('sale_amount');


                    $total_oppertunity = round($total_oppertunity, 2);

                    $new_oppertunity = round($new_oppertunity, 2);

                $data[]=[
                    'staff'=>$stf,
                    'opp_equipment'=>[
                    'total_oppertunity'=>max(0,  $total_oppertunity),
                    'new_oppertunity'=> max(0,  $new_oppertunity),
                    'Committed_nos'=> max(0,  $Committed_nos),
                    'Committed_with_risk_nos' =>  max(0,  $Committed_with_risk_nos),
                    'open_nos'=> max(0,  $open_nos),
                    'upside_nos'=>  max(0,  $upside_nos),
                  
                    ]
                ];
            }
            return response()->json($data);
        }
    }


    public function oppertunity_parts(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");

            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

            $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;



                $modality = Modality::pluck('id')->all();

                if(!empty($request->modality))
                {
                    $modality = $request->modality;
                }

                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }


                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }

                // $equipmentbrandids   = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])->groupBy('brand_id')->pluck('brand_id')->all();
                // $equipmentbrandids = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])->groupBy('brand_id')->pluck('brand_id')->all();

                $brandids            = StaffTarget::whereIn('modality_id',$modality)->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])
                                        ->groupBy('brand_id')
                                        ->pluck('brand_id')->all();

                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $brandids = $brand_id;
                }

                $part_brand_ids = Product::where('category_type_id',2)->whereIn('brand_id',$brandids)->pluck('brand_id')->all();
                

                $total_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                 {$qry->where('staff_id', $staff_id)
                    ->whereBetween("won_date", [$fromdate, $todate])->where('type',1);

                        if (!empty($state)) {

                            $qry->where('state', $state);
                        }

                        if (!empty($district)) {

                            $qry->where('district', $district);
                        }
                   
                    })
                    ->whereIn('product_id', Product::where('category_type_id',2)->
                    whereIn('brand_id', $brandids)->pluck('id')->all())
                    ->sum('sale_amount');

           
                $new_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('type',1)->whereHas('oppertunityquote', function ($query) {

                        $query->where('approved_status', '!=', 'Y');
                    });

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');

                $Committed_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('deal_stage', "!=", 8)->where('type',1)
                   ->where('order_forcast_category', 5);

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');

             
                $Committed_with_risk_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('deal_stage', "!=", 8)->where('type',1)
                   ->where('order_forcast_category', 4);

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');

            
                $open_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('deal_stage', "!=", 8)->where('type',1)
                   ->where('order_forcast_category', 2);

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');


                $upside_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('deal_stage', "!=", 8)->where('type',1)
                   ->where('order_forcast_category', 3);

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');


                    $total_oppertunity = round($total_oppertunity, 2);

                    $new_oppertunity = round($new_oppertunity, 2);

                $data[]=[
                    'staff'=>$stf,
                    'opp_parts'=>[
                    'total_oppertunity'=>max(0,  $total_oppertunity),
                    'new_oppertunity'=> max(0,  $new_oppertunity),
                    'Committed_nos'=> max(0,  $Committed_nos),
                    'Committed_with_risk_nos' =>  max(0,  $Committed_with_risk_nos),
                    'open_nos'=> max(0,  $open_nos),
                    'upside_nos'=>  max(0,  $upside_nos),
                  
                    ]
                ];
            }
            return response()->json($data);
        }
    }

    
    public function oppertunity_msa_staff(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");


            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

             $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                // $equipmentbrandids   = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])->groupBy('brand_id')->pluck('brand_id')->all();
                // $equipmentbrandids = StaffTarget::where('sale_product_type', 'equipments')->where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])->groupBy('brand_id')->pluck('brand_id')->all();

                $brandids            = StaffTarget::where('staff_id', $staff_id)->whereBetween("target_year", [$fromdate->format('Y'), $todate->format('Y')])
                                        ->groupBy('brand_id')
                                        ->pluck('brand_id')->all();

                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $brandids = $brand_id;
                }

                // $part_brand_ids = Product::where('category_type_id',2)->whereIn('brand_id',$brandids)->pluck('brand_id')->all();
                
                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }


                $total_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district) 
                {$qry->where('staff_id', $staff_id)
                    ->whereBetween("won_date", [$fromdate, $todate])->where('type',2);
                   
                    if (!empty($state)) {

                        $qry->where('state', $state);
                    }

                    if (!empty($district)) {

                        $qry->where('district', $district);
                    }

                })->sum('sale_amount');
                   
                    
                $new_oppertunity = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('type',2)->whereHas('oppertunityquote', function ($query) {

                        $query->where('approved_status', '!=', 'Y');
                    });

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');

                $Committed_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('deal_stage', "!=", 8)->where('type',2)
                   ->where('order_forcast_category', 5);

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');

             
                $Committed_with_risk_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('deal_stage', "!=", 8)->where('type',2)
                   ->where('order_forcast_category', 4);

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');

            
                $open_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('deal_stage', "!=", 8)->where('type',2)
                   ->where('order_forcast_category', 2);

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');


                $upside_nos = Oppertunity_product::whereHas('oppertunity', function ($qry) use ($staff_id, $fromdate, $todate,$state,$district)
                {$qry->where('staff_id', $staff_id)
                   ->whereBetween("won_date", [$fromdate, $todate])
                   ->where('deal_stage', "!=", 8)->where('type',2)
                   ->where('order_forcast_category', 3);

                       if (!empty($state)) {

                           $qry->where('state', $state);
                       }

                       if (!empty($district)) {

                           $qry->where('district', $district);
                       }
                  
                   })
                ->whereIn('product_id', Product::where('category_type_id',2)->
                whereIn('brand_id', $brandids)->pluck('id')->all())
                ->sum('sale_amount');


                    $total_oppertunity = round($total_oppertunity, 2);

                    $new_oppertunity = round($new_oppertunity, 2);

                $data[]=[
                    'staff'=>$stf,
                    'opp_parts'=>[
                    'total_oppertunity'=>max(0,  $total_oppertunity),
                    'new_oppertunity'=> max(0,  $new_oppertunity),
                    'Committed_nos'=> max(0,  $Committed_nos),
                    'Committed_with_risk_nos' =>  max(0,  $Committed_with_risk_nos),
                    'open_nos'=> max(0,  $open_nos),
                    'upside_nos'=>  max(0,  $upside_nos),
                  
                    ]
                ];
            }
            return response()->json($data);
        }
    }


    public function special_task(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");


            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

             $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $brandids = $brand_id;
                }
                
                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }


               $no_of_task_assigned = Task::where('assigns',$staff_id)
                                ->whereBetween("created_at", [$fromdate, $todate]);

                if(!empty($state))
                {
                    $no_of_task_assigned->where('state_id');
                }

                if(!empty($district))
                {
                    $no_of_task_assigned->where('district_id');
                }
               
                                
                $no_of_task_assigned = $no_of_task_assigned->count();


                $no_of_partisipate = Task::where('assigns',$staff_id)
                                ->whereBetween("created_at", [$fromdate, $todate])
                                ->where('staff_status','Approved');

                if(!empty($state))
                {
                    $no_of_partisipate->where('state_id');
                }

                if(!empty($district))
                {
                    $no_of_partisipate->where('district_id');
                }
                 
                $no_of_partisipate = $no_of_partisipate->count();


                if (($no_of_partisipate ?? 0) > 0 && ($no_of_task_assigned ?? 0) > 0) {

                    $archeive = ($no_of_partisipate / $no_of_task_assigned) * 100;

                } else {

                    $archeive = 0;
                }
                
                $archeive = round($archeive,2);

                $data[]=[
                    'staff'=>$stf,
                    'special_task'=>[
                    'no_of_task_assigned'=>max(0,  $no_of_task_assigned),
                    'no_of_partisipate'=>max(0,$no_of_partisipate),
                    'archeive'=>max(0,$archeive),
             
                    ]
                ];
            }
            return response()->json($data);
        }
    }

    public function quick_links(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");


            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

             $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $brandids = $brand_id;
                }
                
                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }


                $no_of_user = Oppertunity::where('staff_id', $staff_id)
                ->whereBetween('won_date', [$fromdate, $todate]);
            

                if(!empty($state))
                {
                    $no_of_user->where('state_id');
                }

                if(!empty($district))
                {
                    $no_of_user->where('district_id');
                }
               
                $no_of_user = $no_of_user->distinct('user_id')
                ->pluck('user_id')
                ->toArray();

                $user_count = User::whereIn('id',$no_of_user)->count();


                $no_of_ib = Ib::where('staff_id', $staff_id)
                ->whereBetween('created_at', [$fromdate, $todate]);
            

                $no_of_ib = $no_of_ib->count();

                $contract_persons = Contact_person::whereIn('user_id',$no_of_user)
                                    ->distinct('department')
                                    ->pluck('department')
                                    ->toArray();

                $no_of_departments = Hosdeparment::whereIn('id',$contract_persons)->count();
             

                $no_of_visits = Task::where('assigns',$staff_id)

                            ->whereBetween("created_at", [$fromdate, $todate])
                            ->where('service_task_method','visit')->count();

                $no_of_calls = Task_comment::where('added_by_id',$staff_id)
                            ->whereBetween("created_at", [$fromdate, $todate])
                            ->where('call_status','Y')->count();
                
                // $archeive = round($archeive,2);

                $quote_opp = Oppertunity::where('staff_id', $staff_id)
                    ->whereBetween('won_date', [$fromdate, $todate]);
                

                    if(!empty($state))
                    {
                        $quote_opp->where('state_id');
                    }

                    if(!empty($district))
                    {
                        $quote_opp->where('district_id');
                    }
                
                    $quote_opp = $quote_opp->pluck('id');
                    
                $no_of_quote = Quotehistory::whereIn('oppertunity_id',$quote_opp)
                                            ->where('quote_status','receive')->count();


                $data[]=[
                    'staff'=>$stf,
                    'quick_links'=>[
                    'no_of_user'=>max(0,  $user_count),
                    'no_of_departments'=>max(0,$no_of_departments),
                    'no_of_ib'=>max(0,$no_of_ib),
                    'no_of_visits'=>max(0,$no_of_visits),
                    'no_of_calls'=>max(0,$no_of_calls),
                    'no_of_quote'=>max(0,$no_of_quote),
           
                    ]
                ];
            }
            return response()->json($data);
        }
    }


    public function staff_corrective(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");


            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

             $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $brandids = $brand_id;
                }
                
                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }

                $no_of_calls = Task::with('taskService')->where('assigns',$staff_id)
                ->whereBetween('created_at', [$fromdate, $todate])
                ->whereHas('taskService',function($query)
                {
                    $query->where('service_type',1);
                });

                if(!empty($state))
                {
                   $no_of_calls->where('state_id',$state);
                }

                if(!empty($district))
                {
                   $no_of_calls->where('district_id',$district);
                }
                
                $no_of_calls = $no_of_calls->where('service_task_method','call')->count();
                
               
                $total_calls_closed = Task::with('taskService')->where('assigns',$staff_id)
                ->whereBetween('created_at', [$fromdate, $todate])
                ->whereHas('taskService',function($query)
                {
                    $query->where('service_type',1);
                });


                if(!empty($state))
                {
                   $total_calls_closed->where('state_id',$state);
                }

                if(!empty($district))
                {
                   $total_calls_closed->where('district_id',$district);
                }
                
                $total_calls_closed= $total_calls_closed->where('service_task_method','call')
                ->where('call_status','Y')->where('task_closed','Y')->count();


                $total_calls_pending = Task::with('taskService')->where('assigns',$staff_id)
                ->whereBetween('created_at', [$fromdate, $todate])
                ->whereHas('taskService',function($query)
                {
                    $query->where('service_type',1);
                });
                
                if(!empty($state))
                {
                   $total_calls_pending->where('state_id',$state);
                }

                if(!empty($district))
                {
                   $total_calls_pending->where('district_id',$district);
                }

                $total_calls_pending= $total_calls_pending->where('service_task_method','call')
                ->where('call_status','N')->count();


                $total_calls_ageing = Task::with('taskService')->where('assigns',$staff_id)
                ->whereBetween('created_at', [$fromdate, $todate])
                ->whereHas('taskService',function($query)
                {
                    $query->where('service_type',1);
                })->where('service_task_method','call');

                if(!empty($state))
                {
                   $total_calls_ageing->where('state_id',$state);
                }

                if(!empty($district))
                {
                   $total_calls_ageing->where('district_id',$district);
                }

                $total_calls_ageing = $total_calls_ageing->where('call_status','N')->whereDate('due_date','<',$todate)->count();

                // $archeive = round($archeive,2);


                $data[]=[
                    'staff'=>$stf,
                    'staff_corrective'=>[
                    'no_of_calls'=>max(0,  $no_of_calls),
                    'total_calls_closed'=>max(0,$total_calls_closed),
                    'total_calls_pending'=>max(0,$total_calls_pending),
                    'total_calls_ageing'=>max(0,$total_calls_ageing),
                    ]
                ];
            }
            return response()->json($data);
        }
    }


    public function staff_pm(Request $request)
    {


        if($request->ajax()){

            $staffm=Staff::where("id",">","0");


            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

             $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $brandids = $brand_id;
                }
                
                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }

                // $no_of_calls_task = Task::with('taskService')->where('assigns',$staff_id)
                // ->whereBetween('created_at', [$fromdate, $todate])
                // ->whereHas('taskService',function($query)
                // {
                //     $query->where('service_type',2);
                // });

                // if(!empty($state))
                // {
                //    $no_of_calls_task->where('state_id',$state);
                // }

                // if(!empty($district))
                // {
                //    $no_of_calls_task->where('district_id',$district);
                // }
                
                // $no_of_calls_task = $no_of_calls_task->where('service_task_method','call')->count();
                

                $no_of_calls = Task_comment::where('added_by_id',$staff_id)
                            ->whereBetween("created_at", [$fromdate, $todate])
                            ->whereHas('taskCommentService',function($query)
                            {
                                $query->where('service_type',2);
                            })
                            ->where('call_status','Y')->count();

                $no_of_calls_closed = Task_comment::where('added_by_id',$staff_id)
                ->whereBetween("created_at", [$fromdate, $todate])
                ->whereHas('taskCommentService',function($query)
                {
                    $query->where('service_type',2);
                })
                ->where('call_status','Y')->where('status','Y')->count();


                $no_of_calls_pending = Task_comment::where('added_by_id',$staff_id)
                ->whereBetween("created_at", [$fromdate, $todate])
                ->whereHas('taskCommentService',function($query)
                {
                    $query->where('service_type',2);
                })
                ->where('call_status','Y')->where('status','N')->count();


                $un_assigned_pm = PmDetails::with('service')->whereHas('service',function($query) use($staff_id,$fromdate,$todate)
                                    {
                                        $query->where('created_by',$staff_id)
                                        ->whereBetween("created_at", [$fromdate, $todate])
                                        ->where('service_type',2);
                                    })->whereNull('engineer_name')->count();
    

                
                $total_calls_ageing = PmDetails::with('service')->whereHas('service',function($query) use($fromdate,$todate)
                {
                    $query->where('service_type',2)
                    ->whereBetween("created_at", [$fromdate, $todate]);

                })->where('engineer_name',$staff_id)
                ->whereBetween("visiting_date", [$fromdate, $todate])

                ->count();

                // $archeive = round($archeive,2);


                $data[]=[
                    'staff'=>$stf,
                    'staff_pm'=>[
                    'no_of_calls'=>max(0,  $no_of_calls),
                    'no_of_calls_closed'=>max(0,$no_of_calls_closed),
                    'no_of_calls_pending'=>max(0,$no_of_calls_pending),
                    'un_assigned_pm'=>max(0,$un_assigned_pm),
                    'total_calls_ageing'=>max(0,$total_calls_ageing),
                    ]
                ];
            }
            return response()->json($data);
        }
    }

    

    public function staff_installation(Request $request)
    {

        if($request->ajax()){

            $staffm=Staff::where("id",">","0");


            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

             $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $brandids = $brand_id;
                }
                
                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }


                $no_of_calls = Task_comment::where('added_by_id',$staff_id)
                            ->whereBetween("created_at", [$fromdate, $todate])
                            ->whereHas('taskCommentService',function($query)
                            {
                                $query->where('service_type',2);
                            })
                            ->where('call_status','Y')->count();

                $no_of_calls_closed = Task_comment::where('added_by_id',$staff_id)
                ->whereBetween("created_at", [$fromdate, $todate])
                ->whereHas('taskCommentService',function($query)
                {
                    $query->where('service_type',2);
                })
                ->where('call_status','Y')->where('status','Y')->count();


                $no_of_calls_pending = Task_comment::where('added_by_id',$staff_id)
                ->whereBetween("created_at", [$fromdate, $todate])
                ->whereHas('taskCommentService',function($query)
                {
                    $query->where('service_type',2);
                })
                ->where('call_status','Y')->where('status','N')->count();


                $un_assigned_pm = PmDetails::with('service')->whereHas('service',function($query) use($staff_id,$fromdate,$todate)
                                    {
                                        $query->where('created_by',$staff_id)
                                        ->whereBetween("created_at", [$fromdate, $todate])
                                        ->where('service_type',2);
                                    })->whereNull('engineer_name')->count();
    

                
                $total_calls_ageing = PmDetails::with('service')->whereHas('service',function($query) use($fromdate,$todate)
                {
                    $query->where('service_type',2)
                    ->whereBetween("created_at", [$fromdate, $todate]);

                })->where('engineer_name',$staff_id)
                ->whereBetween("visiting_date", [$fromdate, $todate])

                ->count();

                // $archeive = round($archeive,2);


                $data[]=[
                    'staff'=>$stf,
                    'staff_pm'=>[
                    'no_of_calls'=>max(0,  $no_of_calls),
                    'no_of_calls_closed'=>max(0,$no_of_calls_closed),
                    'no_of_calls_pending'=>max(0,$no_of_calls_pending),
                    'un_assigned_pm'=>max(0,$un_assigned_pm),
                    'total_calls_ageing'=>max(0,$total_calls_ageing),
                    ]
                ];
            }
            return response()->json($data);
        }
    }


    public function staff_expense(Request $request)
    {

        if($request->ajax()){

            $staffm=Staff::where("id",">","0");


            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

             $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $brandids = $brand_id;
                }
                
                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }


                $dateRange = CarbonPeriod::create($fromdate, $todate)->toArray();
                $dates = array_map(function($date) {
                    return $date->format('Y-m-d');
                }, $dateRange);

                $travel_exp = Dailyclosing_expence::where('expence_cat','travel')->where('staff_id',$staff_id)

                ->whereIn('start_date', $dates) 
                ->get();

                $tot_price=0;

                foreach ($travel_exp as $item) {
                    $current_price = 0;
                
                    switch (strtolower($item->travel_type)) {
                        case 'bike':
                            $today_time = strtotime("2022-06-01");
                
                            if(!empty($item->start_date))
                            {
                                $bike_rate = (strtotime($item->start_date) < $today_time)
                                ? setting('BIKE_RATE_BEFORE_MAY')
                                : setting('BIKE_RATE');
                            }
                            else
                            {
                                $bike_rate =0;
                            }
                            
                
                            $total_meter = intval($item->end_meter_reading??0) - intval($data->start_meter_reading??0);
                            $current_price = $total_meter * intval($bike_rate);
                            break;
                
                        case 'car':

                            $total_meter = intval($item->end_meter_reading??0) - intval($data->start_meter_reading??0);
                            $current_price = $total_meter * 5;
                            break;
                
                        default:
                            $current_price = intval(0)??0;
                            break;
                    }
                
                    $tot_price += $current_price;
                }
                

                $data[]=[
                    'staff'=>$stf,
                    'staff_travel_exp'=>[
                    'tot_price_exp'=>$tot_price,
                    'no_of_calls_closed'=>0,
                    'no_of_calls_pending'=>0,
                    'un_assigned_pm'=>0,
                    'total_calls_ageing'=>0,
                    ]
                ];
            }

            return response()->json($data);
        }
    }


    public function staff_work_update(Request $request)
    {

        if($request->ajax()){

            $staffm=Staff::where("id",">","0");


            if(!empty($request->engineer_id)){

                $staffm->whereIn('id',$request->engineer_id);
            }
            else
            {
                $staffm->where('id',$request->staff);
                
            }

             $data=[];
            $period=$request->period??"";
            foreach($staffm->get() as $stf){
                switch ($period) {
                   
                    case 'this_quarter':
                        $fromdate = Carbon::now()->startOfQuarter();
                        $todate   = Carbon::now()->endOfQuarter();
                        break;

                    case 'last_quarter':
                        $fromdate = Carbon::now()->subQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->subQuarter()->endOfQuarter();
                        break;
                    case 'next_quarter':
                        $fromdate = Carbon::now()->addQuarter()->startOfQuarter();
                        $todate   = Carbon::now()->addQuarter()->endOfQuarter();
                        break;
                    case 'thisyear':
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfYear();
                        break;
                    case 'preyear':
                        $fromdate = Carbon::now()->subYear()->startOfYear();
                        $todate   = Carbon::now()->subYear()->endOfYear();
                        break;
                    case 'nextyear':
                        $fromdate = Carbon::now()-addYear()->startOfYear();
                        $todate   = Carbon::now()-addYear()->endOfYear();
                        break;

                    case 'this_month':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'last_month':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->subMonth()->endOfMonth();
                        break;

                    case 'next_month':
                        $fromdate = Carbon::now()->addMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_this':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->endOfMonth();
                        break;

                    case 'this_month_next':
                        $fromdate = Carbon::now()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;

                    case 'last_month_next':
                        $fromdate = Carbon::now()->subMonth()->startOfMonth();
                        $todate   = Carbon::now()->addMonth()->endOfMonth();
                        break;
                
                    case 'last_week':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->subWeek()->endOfWeek(); 
                        break;
                    
                    case 'this_week':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'next_week':
                        $fromdate = Carbon::now()->addWeek()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;

                    case 'last_week_this':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->endOfWeek();
                        break;
                    
                    case 'this_week_next':
                        $fromdate = Carbon::now()->startOfWeek();
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                    
                    case 'last_week_next':
                        $fromdate = Carbon::now()->subWeek()->startOfWeek(); 
                        $todate   = Carbon::now()->addWeek()->endOfWeek();
                        break;
                        
    
    

                    default:
                        $fromdate = Carbon::now()->startOfYear();
                        $todate   = Carbon::now()->endOfMonth();
                        break;
                }
                $staff_id=$stf->id;


                if(!empty($request->brand_id))
                {
                    $brand_id = $request->brand_id;

                    $brandids = $brand_id;
                }
                
                $state ="";

                if(!empty($request->state))
                {
                    $state = $request->state;
                }

                $district ="";

                if(!empty($request->district))
                {
                    $district = $request->district;
                }


                $travel_exp = Task_comment_expence::where('staff_id',$staff_id)
                ->get();
                

                $tot_price_exp=0;

                foreach($travel_exp as $data)
                {

                    switch (strtolower($data->travel_type)) {
                        case 'bike':
                            // $today_time=strtotime("2022-06-01");
                            $today_time = strtotime(date('Y-m-d')); 
                            if (strtotime($data->start_date) < $today_time)
                             {
                                  $bike_rate=setting('BIKE_RATE_BEFORE_MAY'); 

                            }else{
                                $bike_rate=setting('BIKE_RATE');
                            }
                            $total_meter=intval($data->end_meter_reading)-intval($data->start_meter_reading);
                            $tot_price=$total_meter*intval($bike_rate);
                            break;
                        case 'car':  
                            $total_meter=intval($data->end_meter_reading)-intval($data->start_meter_reading);
                            $tot_price=$total_meter*5;  
                        
                        default:
                            $tot_price=$data->travel_end_amount;
                            break;
                    }

                    $tot_price_exp += $tot_price;

                }

                // $archeive = round($archeive,2);


                $data[]=[
                    'staff'=>$stf,
                    'staff_travel_exp'=>[
                    'tot_price_exp'=>$travel_exp,
                    'no_of_calls_closed'=>max(0,$no_of_calls_closed),
                    'no_of_calls_pending'=>max(0,$no_of_calls_pending),
                    'un_assigned_pm'=>max(0,$un_assigned_pm),
                    'total_calls_ageing'=>max(0,$total_calls_ageing),
                    ]
                ];
            }
            return response()->json($data);
        }
    }



    public function staff_report_district(Request $request)
    {
        $state = $request->state;

        $district = District::where('state_id',$state)->pluck('id');

        return response()->json(['district'=>$district]);
    }

    public function staff_report_category(Request $request)
    {
        $category_id = $request->category_id;
        
        $staff_id = $request->staff_id;
        
        $staffs_per = CoordinatorPermission::where('type', 'report')->where('staff_id',$staff_id)->first();
  
        $report_per = ReportPermission::where('permission_id',$staffs_per->id)->pluck('staff_id')->unique()->toArray();


        $staff = Staff::whereIn('id',$report_per)->where('category_id',$category_id)->get();

        return response()->json(['staff'=>$staff]);
        
    }

    public function staff_report_modality(Request $request)
    {
        $brand_id = $request->brand_id;

        $modality = Modality::whereIn('brand_id',$brand_id)->get();

        return response()->json(['modality'=>$modality]);
        
    }

}