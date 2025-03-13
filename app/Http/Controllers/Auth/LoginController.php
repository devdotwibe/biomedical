<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Auth\AuthenticatesUsers;
use App\Models\Admin;
use App\Models\Oppertunity;
use App\Models\Oppertunity_product;
use App\Models\Product;
use App\Models\Staff;
use App\Models\StaffTarget;
use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/products';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('guest')->except('logout');
    }

    public function login(Request $requet)
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





        $admin_id = session('ADMIN_ID');
        if(isset($admin_id)) {

            $hot_pro = Oppertunity::where('es_order_date', '>=', Carbon::today()->toDateString())
        
        ->where('es_order_date', '<=', Carbon::now()->addDays(7)->toDateString())
        ->where('deal_stage', '!=', 6)
        ->where('deal_stage', '!=', 7)
        ->where('deal_stage', '!=', 8)
        ->sum('amount');

        $last_created = Oppertunity::where('created_at', '>', Carbon::now()->subDays(7)->toDateString())
        
        ->get();

        $last_created_closed = Oppertunity::where('created_at', '>=', Carbon::now()->subDays(14)->toDateString())
        ->where('created_at', '<=', Carbon::today()->toDateString())
        ->where('deal_stage', '=', 8)->sum('amount');

        $other_opper = Oppertunity::where('created_at', '<=', Carbon::now()->subDays(14)->toDateString())
        ->sum('amount');

        $stale = Oppertunity::where('es_order_date', '<', Carbon::today()->toDateString())
        ->where('deal_stage', '!=', 6)
        ->where('deal_stage', '!=', 7)
        ->where('deal_stage', '!=', 8)->get();
       
        // $reminder=Reminder::where('status','!=','completed')->whereDate('remind_date','<=',Carbon::today()->toDateString())->first();
        $current=Carbon::now();
        $year=$current->format('Y');
        $brandid=null;
        $monthtarget=null;
        $monthtargetachive=null;
        $monthcommision=null;
        $monthpaid=null;

        return view('staff.dashboard',compact('monthpaid','monthtarget','monthtargetachive','monthcommision','hot_pro','last_created','last_created_closed','other_opper','stale'));

          
        }





        return view('auth.login');
    }

    public function loginSubmit(Request $request){

        $credentials=$request->validate([
            "email"=>["required",'string'],
            "password"=>["required",'string'],
        ]);
        $this->ensureIsNotRateLimited($request);

        // if (Auth::attempt($credentials))
        // {
        //     RateLimiter::clear($this->throttleKey($request));
        //     $request->session()->regenerate();

        //     return redirect()->intended('/staff/dashboard');
        // }

        $user = Staff::where('email', $credentials['email'])->first();
        
        if ($user && md5($credentials['password']) === $user->password)
        {
            Auth::guard('staff')->login($user);
            
            RateLimiter::clear($this->throttleKey($request));

            $request->session()->regenerate();

            $request->session()->put('STAFF_ID', $user->id);

            return redirect()->intended('/dashboard');
        }

        $admin = Admin::where('name', $credentials['email'])->first();
 
        if($admin && md5($credentials['password']) === $admin->password)
        
        {
            Auth::guard('admin')->login($admin);

            RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();

            $request->session()->put('ADMIN_ID', $admin->id);
         
            return redirect()->intended('/dashboard');
        }

        RateLimiter::hit($this->throttleKey($request));
        throw ValidationException::withMessages([
            'login' => 'The provided credentials do not match our records.',
        ]);
    }


    public function ensureIsNotRateLimited(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }
        event(new Lockout($request));
        $seconds = RateLimiter::availableIn($this->throttleKey($request));
        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(Request $request)
    {
        return Str::lower($request->input('email')) . '|' . $request->ip();
    }

}
