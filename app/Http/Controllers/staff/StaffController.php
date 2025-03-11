<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Requests;
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
use Carbon\Carbon;
class StaffController extends Controller
{
    // protected $guard = 'staff';
    
    public function __construct()
    {
    }
 
    public function index()
    {

        $staff_id = session('STAFF_ID');

        if(isset($staff_id)) {
           
            $hot_pro = Oppertunity::where('es_order_date', '>=', Carbon::today()->toDateString())
            ->where('es_order_date', '<=', Carbon::now()->addDays(7)->toDateString())
            ->where('staff_id',$staff_id)
            ->where('deal_stage', '!=', 6)
            ->where('deal_stage', '!=', 7)
            ->where('deal_stage', '!=', 8)
            ->sum('amount');

            $last_created = Oppertunity::where('created_at', '>=', Carbon::now()->subDays(7)->toDateString())
           
            ->where('staff_id',$staff_id)
            ->get();
            
    
            $last_created_closed = Oppertunity::where('created_at', '>=', Carbon::now()->subDays(14)->toDateString())
            ->where('created_at', '<=', Carbon::today()->toDateString())
            ->where('staff_id',$staff_id)
            ->where('deal_stage', '=', 8)->sum('amount');
    
            $other_opper = Oppertunity::where('created_at', '<=', Carbon::now()->subDays(14)->toDateString())
            ->where('staff_id',$staff_id)
            ->sum('amount');
    
            $stale = Oppertunity::where('es_order_date', '<', Carbon::today()->toDateString())
            ->where('staff_id',$staff_id)
            ->where('deal_stage', '!=', 6)
            ->where('deal_stage', '!=', 7)
            ->where('deal_stage', '!=', 8)->get();
            
        $current=Carbon::now();
        $year=$current->format('Y');
        $brandid=StaffTarget::where('staff_id',$staff_id)->where("target_year",$current->format('Y'))->whereBetween("target_month",[$current->startOfQuarter()->format('m'),$current->endOfQuarter()->format('m')])->groupBy('brand_id')->pluck('brand_id')->all();
        $monthtarget=StaffTarget::where('staff_id',$staff_id)->where("target_year",$current->format('Y'))->whereBetween("target_month",[$current->startOfQuarter()->format('m'),$current->endOfQuarter()->format('m')])->sum('target_amount');
        $monthtargetachive=Oppertunity_product::whereHas('oppertunity',function($qry)use($staff_id,$current){ $qry->where('staff_id',$staff_id)->whereBetween("won_date",[$current->startOfQuarter()->toDateString(),$current->endOfQuarter()->toDateString()])->where('deal_stage',8)->whereNotIn('commission_status',['New Orders',"Initial Check",'Technical Approval']); })->whereIn('product_id',Product::whereIn('category_type_id',[1,3])->whereIn('brand_id',$brandid)->pluck('id')->all())->sum('sale_amount');
        $monthcommision=Oppertunity_product::where('approve_status','Y')->whereHas('oppertunity',function($qry)use($staff_id,$current){ $qry->where('staff_id',$staff_id)->whereBetween("won_date",[$current->startOfQuarter()->toDateString(),$current->endOfQuarter()->toDateString()])->where('deal_stage',8); })->whereIn('product_id',Product::whereIn('category_type_id',[1,3])->whereIn('brand_id',$brandid)->pluck('id')->all())->sum('commission');
        $monthpaid=Oppertunity_product::where('paid_status','Y')->where('approve_status','Y')->whereHas('oppertunity',function($qry)use($staff_id,$current){ $qry->where('staff_id',$staff_id)->whereBetween("won_date",[$current->startOfQuarter()->toDateString(),$current->endOfQuarter()->toDateString()])->where('deal_stage',8); })->whereIn('product_id',Product::whereIn('category_type_id',[1,3])->whereIn('brand_id',$brandid)->pluck('id')->all())->sum('commission');

            return view('staff.dashboard',compact('monthpaid','monthtarget','monthtargetachive','monthcommision','hot_pro','last_created','last_created_closed','other_opper','stale'));
        }
        return view('staff.login');
    }
    
    public function stafflogin(Request $request)
    {
       //echo '6565';exit;
        $rules = array(
                    'username'    => 'required', // make sure the email is an actual email
                    'password' => 'required|min:5' // password can only be alphanumeric and has to be greater than 3 characters
                );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return Redirect::to('staff')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {
            //echo 1;
            $userdata = array(
                'username'     => Input::get('username'),
                'password'  => Input::get('password')
            );
            
            //print_r($userdata);            
            //$credentials = $request->only('username', 'password');            
            
            $username = Input::get('username');
            $password = Input::get('password');
            
            $user = DB::table('staff')->where([
                        ['email', $username],
                        ['password', md5($password)],
                        ['status', 'Y']
                    ])
                    ->first();
            
            if(isset($user->id) && $user->id > 0) {
               
                $request->session()->put('STAFF_ID', $user->id);


                
                return Redirect::to('staff/dashboard');
                // print_r($user);
                 //session('admin', 1);
                 //$request->session()->put('admin', 2);
                 //echo $value = $request->session()->get('admin');
                 //print_r($_SESSION);
            } else {
                //return back()->withErrors(['username' => 'Username or password are wrong.'])
                //        ->withInput(Input::except('password'));
                return redirect()->back()->with('error_message', 'Username or password are wrong.')
                        ->withInput(Input::except('password'));
            }

        }
    }
    
    public function dashboard() {       
        $staff_id = session('STAFF_ID');
        
        $hot_pro = Oppertunity::where('es_order_date', '>=', Carbon::today()->toDateString())
        ->where('es_order_date', '<=', Carbon::now()->addDays(7)->toDateString())
        ->where('staff_id',$staff_id)
        ->where('deal_stage', '!=', 6)
        ->where('deal_stage', '!=', 7)
        ->where('deal_stage', '!=', 8)
        ->sum('amount');

        $last_created = Oppertunity::where('created_at', '>=', Carbon::now()->subDays(7)->toDateString())
      
        ->where('staff_id',$staff_id)
        ->get();

        $last_created_closed = Oppertunity::where('created_at', '>=', Carbon::now()->subDays(14)->toDateString())
        ->where('created_at', '<=', Carbon::today()->toDateString())
        ->where('staff_id',$staff_id)
        ->where('deal_stage', '=', 8)->sum('amount');

        $other_opper = Oppertunity::where('created_at', '<=', Carbon::now()->subDays(14)->toDateString())
        ->where('staff_id',$staff_id)
        ->sum('amount');

        $stale = Oppertunity::where('es_order_date', '<', Carbon::today()->toDateString())
        ->where('staff_id',$staff_id)
        ->where('deal_stage', '!=', 6)
        ->where('deal_stage', '!=', 7)
        ->where('deal_stage', '!=', 8)->get();

        $current=Carbon::now();
        $year=$current->format('Y');
        $brandid=StaffTarget::where('staff_id',$staff_id)->where("target_year",$current->format('Y'))->whereBetween("target_month",[$current->startOfQuarter()->format('m'),$current->endOfQuarter()->format('m')])->groupBy('brand_id')->pluck('brand_id')->all();
        $monthtarget=StaffTarget::where('staff_id',$staff_id)->where("target_year",$current->format('Y'))->whereBetween("target_month",[$current->startOfQuarter()->format('m'),$current->endOfQuarter()->format('m')])->sum('target_amount');
        $monthtargetachive=Oppertunity_product::whereHas('oppertunity',function($qry)use($staff_id,$current){ $qry->where('staff_id',$staff_id)->whereBetween("won_date",[$current->startOfQuarter()->toDateString(),$current->endOfQuarter()->toDateString()])->where('deal_stage',8)->whereNotIn('commission_status',['New Orders',"Initial Check",'Technical Approval']); })->whereIn('product_id',Product::whereIn('category_type_id',[1,3])->whereIn('brand_id',$brandid)->pluck('id')->all())->sum('sale_amount');
        $monthcommision=Oppertunity_product::where('approve_status','Y')->whereHas('oppertunity',function($qry)use($staff_id,$current){ $qry->where('staff_id',$staff_id)->whereBetween("won_date",[$current->startOfQuarter()->toDateString(),$current->endOfQuarter()->toDateString()])->where('deal_stage',8); })->whereIn('product_id',Product::whereIn('category_type_id',[1,3])->whereIn('brand_id',$brandid)->pluck('id')->all())->sum('commission');
        $monthpaid=Oppertunity_product::where('paid_status','Y')->where('approve_status','Y')->whereHas('oppertunity',function($qry)use($staff_id,$current){ $qry->where('staff_id',$staff_id)->whereBetween("won_date",[$current->startOfQuarter()->toDateString(),$current->endOfQuarter()->toDateString()])->where('deal_stage',8); })->whereIn('product_id',Product::whereIn('category_type_id',[1,3])->whereIn('brand_id',$brandid)->pluck('id')->all())->sum('commission');
        return view('staff.dashboard',compact('monthpaid','monthtarget','monthtargetachive','monthcommision','hot_pro','last_created','last_created_closed','other_opper','stale'));
     
    }
    
    public function logout(Request $request) {
        //session(['a' => 'value']);
       // echo session('a');
        session()->flush();
        //auth()->guard('admin')->logout();
        //$request->session()->flush();
       // echo $value = $request->session()->get('ADMIN_ID');
        return redirect('staff');
    }
    
    public function changePassword(){
        return view('staff.change_password');
    }
    
    public function updatePassword(Request $request) {
        $rules = array(
            'current_password'    => 'required', // make sure the email is an actual email
            'password' => 'required|min:5', // password can only be alphanumeric and has to be greater than 3 characters
            'confirm_password' => 'required|min:5|same:password'
            //confirmed
        );
        
        $validator = Validator::make(Input::all(), $rules);
        
        if ($validator->fails()) {
            return redirect()->back()->with('error_message', 'Fields are mandatory');
           // return redirect()->route('admin/change-password', ['error_message' => '']);
//            return Redirect::to('admin/')
//                ->withErrors($validator) // send back all errors to the login form
//                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {
            
            $current_password   = Input::get('current_password');
            $password           = Input::get('password');
            $confirm_password   = Input::get('confirm_password');
            
             $admin_id = session('STAFF_ID');
           

            $user =DB::select("select * from staff where `id`='".$admin_id."' AND password='".md5($current_password)."'"); 
            
            
            if(isset($user[0]->id) && $user[0]->id > 0) {
                 DB::table('staff')
                    ->where('id', $user[0]->id)
                    ->update(['password' => md5($password)]);
                 return redirect()->back()->with('message', 'Your password successfully updated.');
            } else {
                return redirect()->back()->with('error_message', 'Your current password is not matching.');
            }   
            
        }
    }
    
    public function viewSettings(){
        $data = Settings::getAllSettings();
        //print_r($data);
        return view('staff.settings',['data'=>$data]);
    }
    
     public function updateSettings(Request $request) {
        $rules = array(
            //'current_password'    => 'required', // make sure the email is an actual email
            //'password' => 'required|min:5', // password can only be alphanumeric and has to be greater than 3 characters
            // 'confirm_password' => 'required|min:5'
        );
        
        $validator = Validator::make(Input::all(), $rules);     
        
        $flg = 0;
        foreach(Input::all() as $key=>$val) {
            //echo $key.'->'.$val;
            if($val == '') {
                $flg++;
            }
        }

        if ($validator->fails() || $flg > 0) {
            return redirect()->back()->with('error_message', 'All fields are mandatory.');
        } else {            
            foreach(Input::all() as $key=>$val) {
                if($val != '') {
                    Settings::set($key, $val);
                }
            }
            
            //$admin_id = session('ADMIN_ID');
            
            return redirect()->back()->with('message', 'Settings successfully updated.');   
        }
    }
    
    public function create()
    {
    }
    
    public function edit(Request $request, CMS $cms)
    {
    }
    
    public function store(Request $request)
    {
       
    }
    
    public function update(Request $request, CMS $cms)
    {

    }
    
    public function destroy($id)
    {

    }

    public function sql()
    {
        return view('admin.sql');
    }
    
}
