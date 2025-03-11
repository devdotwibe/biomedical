<?php

namespace App\Http\Controllers\staff;

use App\Brand;
use App\Country;
use App\CustomerLocation;
use App\District;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
use App\Ib;
use App\Models\StaffTarget;
use App\Settings;
use App\Staff;
use Input;
use Validator;
use Redirect;
use Auth;
use App\Oppertunity;
use App\Oppertunity_product;
use App\Product;
use App\StaffCategory;
use App\State;
use App\Taluk;
use App\User;
use Carbon\Carbon;

class CustomerLocationController extends Controller
{

    public function index(Request $request)
    {

        $staff_category = StaffCategory::with(['categorystaff.customerlocationstaff'])->get();

        $staffs=[];

        foreach($staff_category as $stff_cat)
        {
           
           foreach( $stff_cat->categorystaff as $stf)
           {
                if(!isset($staffs[$stff_cat->name])){

                    $staffs[$stff_cat->name]=[];
                }
                
                $staffs[$stff_cat->name][]=$stf;
           }
        }


        // header('Content-Type: application/json');

        // // Optionally, you can set a response status code (e.g., 200 OK)
        // http_response_code(200);

        // echo json_encode($staffs, JSON_PRETTY_PRINT);  

        // die();

        $country = Country::select('id','name')->whereNotNull('name')->orderBy('name','asc')->get();

        $state = State::select('id','name')->whereNotNull('name')->orderBy('name','asc')->get();

        $district = District::select('id','name','state_id')->whereNotNull('name')->orderBy('name','asc')->get();

        $taluk = Taluk::select('id','name','district_id')->whereNotNull('name')->orderBy('name','asc')->get();

        $customer = User::select('id','business_name','taluk_id')->whereNotNull('name')->orderBy('name','asc')->get();

        $brands = Brand::select('id','name')->whereNotNull('name')->orderBy('name','asc')->get();

        return view('staff.cutomer_location.index',compact('customer','taluk','district','state','staffs','country','brands'));
    }

    public function staff_location_state(Request $request)
    {

        $staff_id = $request->staff_id;

        $country_id = $request->state_id;

        $state = State::select('id','name')->where('country_id',$country_id)
                        ->orderBy('name','asc')->get();
                        
        return response()->json(['staff_id'=>$staff_id,'state'=>$state]);
    }

    public function staff_location_district(Request $request)
    {

        $staff_id = $request->staff_id;

        $state_id = $request->state_id;

        $state = District::select('id','name')->where('state_id',$state_id)
                        ->orderBy('name','asc')->get();
                        
        return response()->json(['staff_id'=>$staff_id,'state'=>$state]);

    }

    public function staff_location_taluk(Request $request)
    {

        $staff_id = $request->staff_id;

        $district_ids = $request->district_id ??[];

        $taluk = Taluk::select('id','name')->whereIn('district_id',$district_ids)
                        ->orderBy('name','asc')->get();
                       
        return response()->json(['staff_id'=>$staff_id,'taluk'=>$taluk]);

    }

    public function staff_location_customer(Request $request)
    {
        
        $staff_id = $request->staff_id;

        $taluk_ids = $request->taluk_id ??[];

        $customer = User::select('id','business_name')->whereIn('taluk_id',$taluk_ids)
                        ->orderBy('business_name','asc')->get();
                       
        return response()->json(['staff_id'=>$staff_id,'customer'=>$customer]);
    }

    public function cusomer_loacation_save(Request $request)
    {

         $customer_location = CustomerLocation::where('staff_id',$request->staff_id)->first();

         if(empty($customer_location))
         {

            $customer_location = new CustomerLocation;

            $customer_location->staff_id = $request->staff_id;

            $customer_location->country_id = $request->country_id;

            $customer_location->state_id = $request->state_id;

            $customer_location->district_id = json_encode( $request->district_id);

            $customer_location->taluk_id = json_encode( $request->taluk_id);

            $customer_location->customer_id = json_encode( $request->customer_id);

            $customer_location->brand_id = json_encode($request->brand_id);

            $customer_location->save(); 

         }
         else
         {
            $customer_location->country_id = $request->country_id;

            $customer_location->state_id = $request->state_id;

            $customer_location->district_id = json_encode( $request->district_id);

            $customer_location->taluk_id = json_encode( $request->taluk_id);

            $customer_location->customer_id = json_encode( $request->customer_id);

            $customer_location->brand_id = json_encode($request->brand_id);

            $customer_location->save();
         }
         
         return response()->json(['success'=>'Succcessfully Updated']);
    }

    public function staff_location_brand(Request $request)
    {

        $staff_id = $request->staff_id;

        $customer_ids = $request->customer_id;


        // $brand = Brand::with(['BrandProduct.productib' => function ($query) use ($customer_ids) {
        //     $query->whereIn('user_id', $customer_ids);
        // }])
        //     ->whereHas('BrandProduct.productib', function ($query) use ($customer_ids) {
        //         $query->whereIn('user_id', $customer_ids);
        //     })
        //     ->select('id', 'name')
        //     ->get();
        $brand = Brand::select('id', 'name')->orderBy('name','asc')->get();
        
                       
        return response()->json(['staff_id'=>$staff_id,'brand'=>$brand]);

    }

    public function undo_action(Request $request)
    {
        $staff_id = $request->staff_id;

        $staff = Staff::with('customerlocationstaff')->find( $staff_id);

        $country = Country::select('id','name')->whereNotNull('name')->orderBy('name','asc')->get();

        $state = State::select('id','name')->whereNotNull('name')->orderBy('name','asc')->get();

        $district = District::select('id','name','state_id')->whereNotNull('name')->orderBy('name','asc')->get();

        $taluk = Taluk::select('id','name','district_id')->whereNotNull('name')->orderBy('name','asc')->get();

        $customer = User::select('id','business_name','taluk_id')->whereNotNull('name')->orderBy('name','asc')->get();

        $brands = Brand::select('id','name')->whereNotNull('name')->orderBy('name','asc')->get();

        $undohtml = view('staff.cutomer_location.undo', compact('customer', 'taluk', 'district', 'state', 'staff', 'country', 'brands'))->render();

        return response()->json(['undohtml' => $undohtml]);
    }

}