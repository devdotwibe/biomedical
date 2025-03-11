<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Dealer;
use App\Staff;
use App\Http\Requests;
use App\Settings;
use Validator;
class ApiAuthentication extends Controller
{
    
    public function __construct()
    {
    }
    public function set_pin(Request $request)
    {
        $staff=Staff::find(Auth::guard('staff')->id());
        if(isset($request->pin))
        {
            $staff->pin_number = $request->pin;
            $staff->save();
        }
        echo $staff->pin_number;
    }
    public function resetpin(Request $request)
    {
        $rules = array(
            'password' => 'required|min:5' // password can only be alphanumeric and has to be greater than 3 characters
        );
        $customMessages = [
            'password.required' => 'Password is required!',
            'password.min'  => 'Minimum 5 characters required!',            
        ];
        $staff=Staff::find(Auth::guard('staff')->id());
        if($staff==null)
        {
            return response()->json(['error' => "Invalid credintial"]);
        }
        $validator = Validator::make($request->all(), $rules,$customMessages);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        if($staff->password==md5($request->password))
        {
            $staff->pin_number = "";
            $staff->save();
            return response()->json(['success' => "Pin Reseted"]);
        }
        else
        {
            return response()->json(['error' => "Invalid credintial"]);
        }
    }
    public function login(Request $request)
    {
        $rules = array(
            'email'    => 'required', // make sure the email is an actual email
            'password' => 'required|min:5' // password can only be alphanumeric and has to be greater than 3 characters
        );

        $customMessages = [
            'email.required' => 'Email is required!',
            'password.required' => 'Password is required!',
            'password.min'  => 'Minimum 5 characters required!',            
        ];

        $validator = Validator::make($request->all(), $rules,$customMessages);
        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        if(Staff::where('email',$request->email)->where('password',md5($request->password))->exists()) {
            $staff=Staff::where('email',$request->email)->where('password',md5($request->password))->first();
            $result=$staff;
            $staff->api_token = hash('sha256', $staff->email.":".$request->password.":".str_random(30));
            $staff->save();
            Auth::guard('staff')->setUser($staff);
            $result['token']=$staff->api_token; 
            return response()->json(['success' => ['Authenticated'],"email"=>$staff->email,"pin"=>isset($staff->pin_number)?$staff->pin_number:"","token"=>Auth::guard('staff')->user()->api_token]);
        }
        else
        {
            return response()->json(['error' => ['Email and Password are Wrong.'] ,"token"=>Auth::guard('staff')->getTokenForRequest()]);
        }

    }
    public function logout(Request $request) {
        Auth::guard('dealer')->logout();
        return redirect('dealer');
    }
    
    public function changePassword(){
        return view('dealer.change_password');
    }
    
    public function updatePassword(Request $request) {
        $rules = array(
            'current_password'    => 'required', // make sure the email is an actual email
            'password' => 'required|min:5', // password can only be alphanumeric and has to be greater than 3 characters
            'confirm_password' => 'same:password'
            //confirmed
        );
        $customMessages = [
            'current_password.required'  => 'Current is required!',
            'password.required'          => 'Password is required!',
            'password.min'               => 'Minimum 5 characters required!',  
            'confirm_password.same'      => 'Confirm Password is Mis-Match!',            
        ];
        $this->validate($request, $rules, $customMessages);
        
                // return redirect()->back()->with('message', 'Your password successfully updated.');
            
                return redirect()->back()->with('error_message', 'Your current password is not matching.');
          
    }
    
    public function viewSettings(){
        $data = Settings::getAllSettings();
        //print_r($data);
        return view('admin.settings',['data'=>$data]);
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
        return view('dealer.sql');
    }
    
    
    public function check_session()
    {
        $dealer_id = session('DEALER_ID');
        if($dealer_id>0)
        {
            echo '0';
        }
        else{
            echo '1';
        }
    }
    
}
