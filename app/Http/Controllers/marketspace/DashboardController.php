<?php

namespace App\Http\Controllers\marketspace;

use App\Marketspace;
use App\Marketspace_qualification;
use App\Marketspace_education;
use App\Marketspace_experience;
use App\Marketspace_skills;
use App\Contact_person;
use App\Hosdeparment;
use App\Hosdesignation;
use App\Marketspace_request_service;
use App\Marketspace_location;
use App\Marketspace_reference;
use App\Marketspace_training;

use Illuminate\Support\Facades\Hash;
use App\Http\Middleware\MarketspaceAuthenticate;

use App\Ib;
use App\Product;
use App\User;
use App\Category;
use App\Staff;
use App\Marketspace_available_date;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Country;
use App\EquipmentStatus;
use App\State;
use App\Taluk;
use App\District;
use App\Brand;
use App\Rating;
use App\Product_type;
use App\Category_type;
use Image;
use PHPMailer\PHPMailer\PHPMailer;
use Storage;
use Session;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use PHPMailer\PHPMailer\Exception;

class DashboardController extends Controller
{
    public function dashboard()
    {
      
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            $marketspace_quali = Marketspace_qualification::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
            $marketspace_edu = Marketspace_education::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
            $marketspace_exp = Marketspace_experience::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
            $marketspace_training = Marketspace_training::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
            $marketspace_reference = Marketspace_reference::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
            $marketspace_skill = Marketspace_skills::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
            $brand         = Brand::where(['status'=> 'Y'])->orderBy('id','asc')->get();
            $brand = Brand::select('brand.id','brand.name')
            ->join('products', 'brand.id', '=', 'products.brand_id')
            ->groupBy('brand.id')
            ->get();

            $product_type = Product_type::select('product_type.id','product_type.name')
            ->join('products', 'product_type.id', '=', 'products.product_type_id')
            ->groupBy('product_type.id')
            ->orderBy('product_type.name','asc')
            ->get();

            $catgory_type = Category_type::select('category_type.id','category_type.name')
            ->join('products', 'category_type.id', '=', 'products.category_id')
            ->groupBy('category_type.id')
            ->get();


            $user  = User::orderBy('business_name','asc')->get();
            $id=session('MARKETSPACE_ID');
            $users_rating_count = DB::select("select *,sum(rating) as rating from `rating` where post_id='".$id."'");
            $users_rating = Rating::where('post_id','=',$id)->get();
            
            $total_rating=$users_rating_count[0]->rating/count($users_rating_count);
            $data = Ib::with('ibEquipmentStatus','ibBrand','ibUser', 'ibStaff', 'ibDepartment')
            ->where('user_id', '=', $marketspace->user_id)
            ->where('equipment_id', '!=',null)
            ->orderBy('id','DESC')
            ->paginate(20);

            $contact_person = Contact_person::
            where('user_id', '=', $marketspace->user_id)
            ->get();

            return view('marketspace.dashboard',compact('marketspace_reference','marketspace_training','user','contact_person','data','users_rating','total_rating','catgory_type','marketspace_skill','brand','product_type','marketspace','marketspace_quali','marketspace_edu','marketspace_exp'));
        }
        
       
        return redirect("marketspace/login")->withSuccess('Opps! You do not have access');
    }
    
    public function marketspace_ib()
    {
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        $data = Ib::with('ibEquipmentStatus','ibBrand','ibUser', 'ibStaff', 'ibDepartment')
        ->where('user_id', '=', $marketspace->user_id)
        ->where('equipment_id', '!=',null)
        ->orderBy('id','DESC')
        ->paginate(20);
        return view('marketspace.ib',compact('data','marketspace'));
    }
    public function skills()
    {
        $marketspace_skill = Marketspace_skills::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        $brand = Brand::select('brand.id','brand.name')
        ->join('products', 'brand.id', '=', 'products.brand_id')
        ->groupBy('brand.id')
        ->get();
        $product_type = Product_type::select('product_type.id','product_type.name')
        ->join('products', 'product_type.id', '=', 'products.product_type_id')
        ->groupBy('product_type.id')
        ->orderBy('product_type.name','asc')
        ->get();

        $catgory_type = Category_type::select('category_type.id','category_type.name')
        ->join('products', 'category_type.id', '=', 'products.category_id')
        ->groupBy('category_type.id')
        ->get();
        return view('marketspace.skills',compact('marketspace_skill','marketspace','brand','product_type'));
    }
    public function work_experience()
    {
        $marketspace_exp = Marketspace_experience::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        return view('marketspace.work_experience',compact('marketspace_exp','marketspace'));
    }
    public function education()
    {
        $marketspace_edu = Marketspace_education::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        return view('marketspace.education',compact('marketspace_edu','marketspace'));
    }
    public function reference()
    {
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        $marketspace_reference = Marketspace_reference::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        return view('marketspace.reference',compact('marketspace_reference','marketspace'));
    }
    public function training_attended()
    {
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        $marketspace_training = Marketspace_training::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        $product_type = Product_type::select('product_type.id','product_type.name')
        ->join('products', 'product_type.id', '=', 'products.product_type_id')
        ->groupBy('product_type.id')
        ->orderBy('product_type.name','asc')
        ->get();
        $catgory_type = Category_type::select('category_type.id','category_type.name')
        ->join('products', 'category_type.id', '=', 'products.category_id')
        ->groupBy('category_type.id')
        ->get();
        return view('marketspace.training_attended',compact('marketspace_training','marketspace','product_type','catgory_type'));
    }
    
    public function rating($id,$user_id,$hire_id)
    {
        $rating_exit = Rating::where('post_id', '=', $id)->where('user_id', '=', $user_id)->get();
        return view('marketspace.rating',compact('id','user_id','rating_exit','hire_id'));
    }
    
    public function save_rating(Request $request)
    {
        $rating = new Rating;
        $rating->user_id = $request->user_id;
        $rating->post_id = $request->post_id;
        $rating->hire_id = $request->hire_id;
        $rating->rating = $request->phprating;
        $rating->content =$request->content;
        $rating->save();
        return redirect('marketspace/mywork')->with('message', 'You have Successfully Added Review!');
      //  return redirect()->back()->with('message', ' You have Successfully Added Review');
    }
    

    public function hospitalcreate()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            $user = User::all();
            $country = Country::all();
            $hosdeparment = Hosdeparment::all();
            $hosdesignation = Hosdesignation::all();
            
            return view('marketspace.hospitalcreate',compact('hosdesignation','hosdeparment','marketspace','country','user'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }
    public function servicestaffprofile()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            $user = User::all();
            $country = Country::all();
            $hosdeparment = Hosdeparment::all();
            $hosdesignation = Hosdesignation::all();
            
            return view('marketspace.servicestaffprofile',compact('hosdesignation','hosdeparment','marketspace','country','user'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }
    
    public function editprofile()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            $user = User::
            orderBy('business_name', 'ASC')
    ->get();
            $country = Country::all();
            $hosdeparment = Hosdeparment::all();
            $hosdesignation = Hosdesignation::all();
            if($marketspace->contact_person_id>0)
            {
                $contact_person = Contact_person::where('id', '=', $marketspace->contact_person_id)->get();
            }else{
                $contact_person=array();
            }
            return view('marketspace.editprofile',compact('contact_person','hosdesignation','hosdeparment','marketspace','country','user'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }

    
    public function availabledate()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            
            $data =Marketspace_available_date::where('marketspace_id', '=',session('MARKETSPACE_ID'))->
            where('start_date', '>=',Carbon::today()->format('Y-m-d'))
            ->orderBy('id','DESC')
            ->paginate(20);
            return view('marketspace.availabledate',compact('marketspace','data'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }
    
    public function location()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            
            $data =Marketspace_location::where('marketspace_id', '=',session('MARKETSPACE_ID'))->orderBy('id','DESC')
            ->paginate(20);
            return view('marketspace.location',compact('marketspace','data'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }
    
    
    public function check_servicereq_date(Request $request)
    {
        $id=$request->product_id;
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            $product = Product::where('id', '=',$id)->get();
            $marketspace_skill = Marketspace_skills::
            whereRaw("find_in_set($id,product_id)")
            ->get();
            $ib = Ib::with('ibEquipmentStatus','ibBrand','ibUser', 'ibStaff', 'ibDepartment')
            ->where('user_id', '=', $marketspace->user_id)
            ->where('equipment_id', '!=',null)
            ->orderBy('id','DESC')->get();

            $data_date=array();
            if(count($marketspace_skill)>0){
            foreach($marketspace_skill as $val)
                {
                    $preferred_dates = Marketspace_available_date::whereIn('marketspace_id', [$val->marketspace_id])->get();    
                    if(count($preferred_dates)>0)
                    {
                        foreach($preferred_dates as $values)
                        {
                            $cur_date=date("Y-m-d");
                        if(strtotime($cur_date)<=strtotime($values->start_date))
                        {
                            $data_date[] = date('d-m-Y',strtotime($values->start_date));
                        }
                        
                        }
                    }
                }
            }
           
            echo  json_encode(array_unique($data_date)).'*'.count($marketspace_skill).'*'.count($data_date);

        }

    }
    public function requestservice()
    {
      if(isset($_REQUEST['id'])){
        $id=$_REQUEST['id'];
      }else{
        $id=0;
      }
         if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            $product = Product::where('id', '=',$id)->get();
            $marketspace_skill = Marketspace_skills::
            whereRaw("find_in_set($id,product_id)")
            ->get();
            $ib = Ib::with('ibEquipmentStatus','ibBrand','ibUser', 'ibStaff', 'ibDepartment')
            ->where('user_id', '=', $marketspace->user_id)
            ->where('equipment_id', '!=',null)
            ->orderBy('id','DESC')->get();

            $data_date=array();
            if(count($marketspace_skill)>0){
            foreach($marketspace_skill as $val)
                {
                    $preferred_dates = Marketspace_available_date::whereIn('marketspace_id', [$val->marketspace_id])->get();    
                    if(count($preferred_dates)>0)
                    {
                        foreach($preferred_dates as $values)
                        {
                            $cur_date=date("Y-m-d");
                        if(strtotime($cur_date)<=strtotime($values->start_date))
                        {
                            $data_date[] = date('d-m-Y',strtotime($values->start_date));
                        }
                        
                        }
                    }
                }
            }
           
            $json_encode_date= json_encode(array_unique($data_date));

            return view('marketspace.request_service',compact('ib','marketspace_skill','data_date','product','json_encode_date','id','marketspace','marketspace_skill','marketspace_skill'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
      
    }


    public function requestservice_edit($id)
    {
      
        if(session('MARKETSPACE_ID')){
            $market_req = Marketspace_request_service::where('id', '=', $id)->first();
            $id=$market_req->product_id;
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            $product = Product::where('id', '=',$id)->get();
            $marketspace_skill = Marketspace_skills::
            whereRaw("find_in_set($id,product_id)")
            ->get();
           
            $data_date=array();
            if(count($marketspace_skill)>0){
            foreach($marketspace_skill as $val)
                {
                    $preferred_dates = Marketspace_available_date::whereIn('marketspace_id', [$val->marketspace_id])->get();    
                    if(count($preferred_dates)>0)
                    {
                        foreach($preferred_dates as $values)
                        {
                            $cur_date=date("Y-m-d");
                        if(strtotime($cur_date)<=strtotime($values->start_date))
                        {
                            $data_date[] = date('d-m-Y',strtotime($values->start_date));
                        }
                        
                        }
                    }
                }
            }
           
            $json_encode_date= json_encode(array_unique($data_date));

            $hosdeparment = Hosdeparment::all();
            $hosdesignation = Hosdesignation::all();
       
            return view('marketspace.request_service_edit',compact('market_req','marketspace_skill','data_date','hosdesignation','hosdeparment','product','json_encode_date','id','marketspace','marketspace_skill','marketspace_skill'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
      
    }


    public function available_date_edit($id)
    {
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            
            $data =Marketspace_available_date::where('id', '=',$id)
            ->first();
            return view('marketspace.availabledate_edit',compact('marketspace','data'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
      
    }

    public function location_edit($id)
    {
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            
            $data =Marketspace_location::where('id', '=',$id)
            ->first();
            return view('marketspace.location_edit',compact('marketspace','data'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
      
    }
    
    public function iblist()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            $country = Country::all();
            $data = Ib::with('ibEquipmentStatus','ibBrand','ibUser', 'ibStaff', 'ibDepartment')
            ->where('user_id', '=', $marketspace->user_id)
            ->orderBy('id','DESC')
            ->paginate(20);
            return view('marketspace.iblist',compact('marketspace','country','data'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }

    public function ibcreate()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
           
            $products           = Product::get();
            $users              = User::get();
            $equipment_status   = EquipmentStatus::get();
            $categories         = Category::get();
            $staffs             = Staff::get();
            
            return view('marketspace.ibcreate',compact('marketspace','products', 'users', 'equipment_status', 'categories', 'staffs'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }

    public function kyc()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
           
            
            return view('marketspace.kyc',compact('marketspace'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }

    
    public function available_date_update(Request $request)
    {
        $avil_date =Marketspace_available_date::find($request->id);
        $avil_date->start_date = $request->start_date;
        $avil_date->start_time = $request->start_time;
        $avil_date->end_time = $request->end_time;
        $avil_date->avail_hour = $request->avail_hour;
        $avil_date->marketspace_id =session('MARKETSPACE_ID');
        $avil_date->save();
        return redirect()->route('marketspace/availabledate')->with('success','Data successfully saved.');
    }

    public function available_location_update(Request $request)
    {
        $update_location =Marketspace_location::find($request->id);
        $update_location->state_id = $request->state_id;
        $update_location->district_id = $request->district_id;
        $update_location->save();
        return redirect()->route('marketspace/location')->with('success','Data successfully saved.');
    }
    
    public function kyc_store(Request $request)
    {
        $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
        $marketspace->pan_no = $request->pan_no;
      $marketspace->adhar_no = $request->adhar_no;
        $marketspace->save();
        return redirect()->route('marketspace/kyc')->with('success','Data successfully saved.');
    }

    
    public function get_anotherservice_staff_form(Request $request)
    {
        $marketspace_req = Marketspace_request_service::where('id', '=',$request->id)->first();
        $id=$marketspace_req->product_id;
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            $product = Product::where('id', '=',$id)->get();
            $marketspace_skill = Marketspace_skills::
            whereRaw("find_in_set($id,product_id)")
            ->get();
           
            $data_date=array();
            if(count($marketspace_skill)>0){
            foreach($marketspace_skill as $val)
                {
                    $preferred_dates = Marketspace_available_date::whereIn('marketspace_id', [$val->marketspace_id])->get();    
                    if(count($preferred_dates)>0)
                    {
                        foreach($preferred_dates as $values)
                        {
                            $cur_date=date("Y-m-d");
                        if(strtotime($cur_date)<=strtotime($values->start_date))
                        {
                            $data_date[] = date('d-m-Y',strtotime($values->start_date));
                        }
                        
                        }
                    }
                }
            }
           
            $json_encode_date= json_encode(array_unique($data_date));

            $hosdeparment = Hosdeparment::all();
            $hosdesignation = Hosdesignation::all();
       
            return view('marketspace.gettting-another-staff',compact('marketspace_skill','data_date','hosdesignation','hosdeparment','product','json_encode_date','id','marketspace','marketspace_skill','marketspace_skill'));
       

    }
    public function available_date_store(Request $request)
    {
        $avil_date = new Marketspace_available_date;
        $avil_date->start_date = $request->start_date;
        $avil_date->start_time = $request->start_time;
        $avil_date->end_time = $request->end_time;
        $avil_date->avail_hour = $request->avail_hour;
        $avil_date->marketspace_id =session('MARKETSPACE_ID');
        $avil_date->save();
        return redirect()->route('marketspace/availabledate')->with('success','Data successfully saved.');
    }

    public function location_store(Request $request)
    {
        $avil_location = new Marketspace_location;
        $avil_location->state_id = $request->state_id;
        $avil_location->district_id = $request->district_id;
        $avil_location->marketspace_id =session('MARKETSPACE_ID');
        $avil_location->save();
        return redirect()->route('marketspace/location')->with('success','Data successfully saved.');
    }
    
  
    public function ibstore(Request $request)
    {
        $request->validate([
            'user_id'=>'required',
            'department_id'=>'required',
            'equipment_id'=>'required',
            'equipment_serial_no'=>'required',
            'equipment_model_no'=>'required' ,
            'equipment_status_id'=>'required',
            'staff_id'=>'required',
            'installation_date'=>'required',  
            'warrenty_end_date'=>'required',  
            'description'=>'required'
        ]);

        Ib::create($request->all());
        return redirect()->route('marketspace-ib')->with('success','Data successfully saved.');
    }



    public function changepassword()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            return view('marketspace.changepassword',compact('marketspace'));
        }
  
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }

    
    public function account()
    {
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            return view('marketspace.account',compact('marketspace'));
        }
         return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }
    

    public function verify($id)
    {
echo '111';exit;

    }

      
    
   
    public function saveQualification(Request $request)
    {
        if($request->qualification_id==0)
        {
            $quali = new Marketspace_qualification;
            $quali->qualification = $request->qualification;
            $quali->qualification_from_month = $request->qualification_from_month;
            $quali->qualification_from_year = $request->qualification_from_year;
            $quali->qualification_to_month = $request->qualification_to_month;
            $quali->qualification_to_year = $request->qualification_to_year;
            $quali->quali_cert = $request->quali_current_image;
            $quali->marketspace_id =session('MARKETSPACE_ID');
            $quali->save();
        }else{
            $quali =Marketspace_qualification::find($request->qualification_id);
            $quali->qualification = $request->qualification;
            $quali->qualification_from_month = $request->qualification_from_month;
            $quali->qualification_from_year = $request->qualification_from_year;
            $quali->qualification_to_month = $request->qualification_to_month;
            $quali->qualification_to_year = $request->qualification_to_year;
            $quali->quali_cert = $request->quali_current_image;
            $quali->marketspace_id =session('MARKETSPACE_ID');
            $quali->save();
        }
        
        $qualificationall = Marketspace_qualification::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        return json_encode($qualificationall);
    }
    public function saveEducation(Request $request)
    {
        if($request->education_id==0)
        {
            $edu = new Marketspace_education;
            $edu->education = $request->education;
            $edu->institution = $request->institution;
            $edu->percentage_mark = $request->percentage_mark;
            $edu->education_to_year = $request->education_to_year;
            $edu->edu_cert = $request->edu_current_image;
            $edu->marketspace_id =session('MARKETSPACE_ID');
            $edu->save();
        }else{
            $edu =Marketspace_education::find($request->education_id);
            $edu->education = $request->education;
            $edu->institution = $request->institution;
            $edu->percentage_mark = $request->percentage_mark;
            $edu->education_to_year = $request->education_to_year;
            $edu->edu_cert = $request->edu_current_image;
            $edu->marketspace_id =session('MARKETSPACE_ID');
            $edu->save();
        }
        
        $education = Marketspace_education::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        return json_encode($education);
    }

    public function saveExperience(Request $request)
    {
        if($request->experience_id==0)
        {
            $exp = new Marketspace_experience;
            $exp->company_name = $request->company_name;
            $exp->designation = $request->designation;
            $exp->from_date = $request->from_date;
            $exp->to_date = $request->to_date;
            $exp->responsibilities = $request->responsibilities;
          
            $exp->exp_cert = $request->exp_current_image;
            
            $exp->exp_det = $request->exp_det;
            if($request->current_work)
            {
                $exp->current_work = 'Y';
            }else{
                $exp->current_work = 'N';
            }
            $exp->marketspace_id =session('MARKETSPACE_ID');
            $exp->save();
        }else{
            $exp =Marketspace_experience::find($request->experience_id);
            $exp->company_name = $request->company_name;
            $exp->designation = $request->designation;
            $exp->from_date = $request->from_date;
            $exp->to_date = $request->to_date;
            $exp->responsibilities = $request->responsibilities;
            $exp->exp_cert = $request->exp_current_image;
            
            $exp->exp_det = $request->exp_det;
            if($request->current_work)
            {
                $exp->current_work = 'Y';
            }else{
                $exp->current_work = 'N';
            }
            $exp->marketspace_id =session('MARKETSPACE_ID');
            $exp->save();
        }
        
        $experiance = Marketspace_experience::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        return json_encode($experiance);
    }
    
    public function saveReference(Request $request)
    {
        if($request->reference_id==0)
        {
            $reference = new Marketspace_reference;
            $reference->name_of_person = $request->name_of_person;
            $reference->organisation = $request->organisation;
            $reference->refer_designation = $request->refer_designation;
            $reference->refer_email = $request->refer_email;
            $reference->refer_contact = $request->refer_contact;
            $reference->marketspace_id =session('MARKETSPACE_ID');
            $reference->save();
        }else{
            $reference =Marketspace_reference::find($request->reference_id);
            $reference->name_of_person = $request->name_of_person;
            $reference->organisation = $request->organisation;
            $reference->refer_designation = $request->refer_designation;
            $reference->refer_email = $request->refer_email;
            $reference->refer_contact = $request->refer_contact;
            $reference->marketspace_id =session('MARKETSPACE_ID');
            $reference->save();
        }
        
        // $training = Marketspace_training::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        // return json_encode($training);
        $reference = Marketspace_reference::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        echo ' <thead class="thead-light">
        <tr>
           
          <th scope="col">Name of person</th>
            <th scope="col">Email</th>
            <th scope="col">Contact no</th>
            
            <th scope="col">Action</th>
        </tr>
    </thead>';
        foreach($reference as $val)
        {
            
            echo ' <tr>';
            
           echo ' <td>'.$val->name_of_person.'</td>
           <td>'.$val->refer_email.'</td>
           <td>'.$val->refer_contact.'</td>
            <td>
             <div class="reference-edit" data-name_of_person="'.$val->name_of_person.'" data-refer_email="'.$val->refer_email.'" data-refer_contact="'.$val->refer_contact.'"
              data-organisation="'.$val->organisation.'"  data-refer_designation="'.$val->refer_designation.'"  
               data-id="'.$val->id.'"  >
               <a ><img src="'.asset('images/edit-grey.svg').'" alt="editbtn"></a></div>
               <a class="reference-delete" data-id="'.$val->id.'"><i class="fa fa-times"></i></a>
               </td>
            </tr>';
        }

    }

    public function saveTraining(Request $request)
    {
        if($request->training_id==0)
        {
            $training = new Marketspace_training;
            $training->category_type_id = $request->category_type_id;
            $training->product_id = implode(',',$request->training_product_id);
            $training->training_type = $request->training_type;
            $training->training_institution = $request->training_institution;
            $training->date_training = $request->date_training;
            $training->training_description = $request->training_description;
            $training->training_cert = $request->training_current_image;
            $training->marketspace_id =session('MARKETSPACE_ID');
            $training->save();
        }else{
            $training =Marketspace_training::find($request->training_id);
            $training->category_type_id = $request->category_type_id;
            $training->product_id = implode(',',$request->training_product_id);
            $training->date_training = $request->date_training;
            $training->training_type = $request->training_type;
            $training->training_institution = $request->training_institution;
            $training->training_description = $request->training_description;
            $training->training_cert = $request->training_current_image;
            $training->marketspace_id =session('MARKETSPACE_ID');
            $training->save();
        }
        
        // $training = Marketspace_training::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        // return json_encode($training);
        $training = Marketspace_training::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        echo ' <thead class="thead-light">
        <tr>
           
          <th scope="col">Product</th>
            <th scope="col">Institution</th>
            
            <th scope="col">Action</th>
        </tr>
    </thead>';
        foreach($training as $val)
        {
            
            echo ' <tr>';
            
            
            echo ' <td>';
            if( strpos($val->product_id,',') !== false ) {
                $proarr=explode(',',$val->product_id);
            }else{
                $proarr[]=$val->product_id;
            }
           
            if(count($proarr)>0){
                foreach($proarr as $valpro)
                {
                $productsall =  DB::select("select * from products where `id`='".$valpro."' order by id desc");
                if($productsall){echo $productsall[0]->name;echo "<br>";}
                }
            }

           echo '</td>';
            
           echo ' <td>'.$val->training_institution.'</td>
            <td>
             <div class="training-edit" data-training_description="'.$val->training_description.'" data-training_institution="'.$val->training_institution.'" data-training_type="'.$val->training_type.'" data-date_training="'.$val->date_training.'"  data-training_cert="'.$val->training_cert.'"  data-category_type_id="'.$val->category_type_id.'" data-id="'.$val->id.'"  data-product_id="'.$val->product_id.'">
               <a ><img src="'.asset('images/edit-grey.svg').'" alt="editbtn"></a></div>
               <a class="training-delete" data-id="'.$val->id.'"><i class="fa fa-times"></i></a>
               </td>
            </tr>';
        }

    }
    public function deleteSkill(Request $request)
    {
       Marketspace_skills::where('id',$request->id)->delete();
    }
    public function deleteExperience(Request $request)
    {
       Marketspace_experience::where('id',$request->id)->delete();
    }
    public function deleteEducation(Request $request)
    {
       Marketspace_education::where('id',$request->id)->delete();
    }
    public function deleteTraining(Request $request)
    {
       Marketspace_training::where('id',$request->id)->delete();
    }
    public function deleteReference(Request $request)
    {
       Marketspace_reference::where('id',$request->id)->delete();
    }
    
    public function saveSkill(Request $request)
    {
        if($request->skill_id==0)
        {
            $skill = new Marketspace_skills;
            $skill->product_id =$request->product_id;
            $skill->brand_id = $request->brand_id;
            $skill->product_type_id = $request->product_type_id;
            $skill->proficiency = $request->proficiency;
            $skill->price = $request->price;
            $skill->year_of_exp = $request->year_of_exp;
            $skill->skill_cert = $request->skill_current_image;
            $skill->orginal_company_training = $request->orginal_company_training;
            $skill->skillex_cert = $request->skillex_current_image;
            
            $skill->marketspace_id =session('MARKETSPACE_ID');
            $skill->save();
        }else{
            $skill =Marketspace_skills::find($request->skill_id);
            $skill->product_id =$request->product_id;
            $skill->brand_id = $request->brand_id;
            $skill->product_type_id = $request->product_type_id;
            $skill->proficiency = $request->proficiency;
            $skill->year_of_exp = $request->year_of_exp;
            $skill->orginal_company_training = $request->orginal_company_training;
            
            $skill->price = $request->price;
            $skill->skill_cert = $request->skill_current_image;
            $skill->skillex_cert = $request->skillex_current_image;
            $skill->marketspace_id =session('MARKETSPACE_ID');
            $skill->save();
        }
        
        $skills = Marketspace_skills::where('marketspace_id', '=', session('MARKETSPACE_ID'))->get();
        foreach($skills as $val)
        {
            
            echo ' <tr>
            <td>'.$val->brand->name.'</td>
            <td>'.$val->product_type->name.'</td>';
            echo ' <td>';
            $proarr=explode(',',$val->product_id);
            if(count($proarr)>0){
                foreach($proarr as $valpro)
                {
                $productsall =  DB::select("select * from products where `id`='".$valpro."' order by id desc");
                if($productsall){echo $productsall[0]->name;echo "<br>";}
                }
            }

           echo '</td>';
            
           echo ' <td>'.number_format($val->price,2).'</td>
            <td>
              

             <div class="skill-edit" data-proficiency="'.$val->proficiency.'" data-year_of_exp="'.$val->year_of_exp.'" data-orginal_company_training="'.$val->orginal_company_training.'" data-skill_cert="'.$val->skill_cert.'" data-skillex_cert="'.$val->skillex_cert.'"  data-product_type_id="'.$val->product_type_id.'" data-id="'.$val->id.'" data-price="'.$val->price.'" data-brand_id="'.$val->brand_id.'" data-product_id="'.$val->product_id.'">
               <a ><img src="'.asset('images/edit-grey.svg').'" alt="editbtn"></a></div>
               <a class="skill-delete" data-id="'.$val->id.'"><i class="fa fa-times"></i></a>
               </td>
            </tr>';
        }
       
    }

    

    public function saveAbout(Request $request)
    {
        $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
        $marketspace->about = $request->about;
        $marketspace->pro_headline = $request->pro_headline;
        $marketspace->save();
        
    }
    public function saveAccount(Request $request)
    {
        $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
        $marketspace->user_type = implode(',',$request->user_type);
        $marketspace->save();
    }

    public function saveChangepassword(Request $request)
    {
        $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
    
        $marketspace->password = Hash::make($request->password);
        $marketspace->save();
    }
    
    
    public function savehospital(Request $request)
    {
        $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
        $marketspace->user_id = $request->user_id;
        $marketspace->save();

            if($request->add_contact>0)
            {
                    if($request->contact_person_id>0)
                    {
                        $contact_person = Contact_person::find($request->contact_person_id);
                    }
                    else{
                        $contact_person = new Contact_person;
                    }
                    
                    $contact_person->name = $request->name;
                    $contact_person->email = $request->email;
                    $contact_person->phone = $marketspace->phone;
                    $contact_person->remark = $request->remark;
                    $contact_person->title = $request->title;
                    $contact_person->mobile = $marketspace->phone;
                    $contact_person->last_name = $request->last_name;
                    $contact_person->department = $request->department;
                    $contact_person->designation = $request->designation;
                    $contact_person->user_id = $request->user_id;
                    //$contact_person->password =  Hash::make($request->password);
                    $contact_person->save();
                    $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
                    $marketspace->contact_person_id = $contact_person->id;
                    $marketspace->save();
            }else{
                $user = User::where('id', '=', $request->user_id)
            ->withTrashed()->get();
         
            if(count($user)>0){
                $marketspace_update =Marketspace::find($marketspace->id);
                $marketspace_update->name = $user[0]->business_name;
                $marketspace_update->email = $user[0]->email;
                $marketspace_update->address1 = $user[0]->address1;
                $marketspace_update->address2 = $user[0]->address2;
                $marketspace_update->country_id = 101;
                $marketspace_update->state_id = $user[0]->state_id;
                $marketspace_update->district_id = $user[0]->district_id;
                $marketspace_update->zip = $user[0]->zip;
                $marketspace_update->city = $user[0]->city;
                $marketspace_update->user_id = $user[0]->id;
                $marketspace_update->image = $user[0]->image_name1;
                $marketspace_update->save();
            }
            
            }

           
    }
    
    public function saveEditprofile(Request $request)
    {
        if($request->user_id>0)
        {
            $user =User::withTrashed()->find($request->user_id);
        }
        else{
            $user = new User;
        }
        $user->name = $request->name;
        $user->business_name = $request->business_name;
        $user->address1 = $request->address1;
        $user->country_id = $request->country_id;
        $user->state_id = $request->state_id;
        $user->district_id = $request->district_id;
        $user->taluk_id = $request->taluk_id;
        $user->zip = $request->zip;
        $user->gst = $request->gst;
        $user->save();

        $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
        $marketspace->name = $request->name;
        $marketspace->business_name = $request->business_name;
        $marketspace->address1 = $request->address1;
        $marketspace->country_id = $request->country_id;
        $marketspace->state_id = $request->state_id;
        $marketspace->district_id = $request->district_id;
        $marketspace->taluk_id = $request->taluk_id;
        $marketspace->zip = $request->zip;
        $marketspace->gst = $request->gst;
        $marketspace->email = $request->email;
        $marketspace->phone = $request->phone;
        $marketspace->dob = $request->dob;
        $marketspace->blood_group = $request->blood_group;
        $marketspace->alt_contact_no = $request->alt_contact_no;
        $marketspace->current_address = $request->current_address;
        $marketspace->user_id = $user->id;
        $marketspace->save();


    }

    
public function partOneSaveImage(Request $request){
        $imageName = time().$request->image->getClientOriginalName();
    
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
    
        $path =  storage_path();
    
        $img_path = $request->image->storeAs('public/user', $imageName);
    
        $path = $path.'/app/'.$img_path;
    
        chmod($path, 0777);
    
       
        $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
        $marketspace->image = $imageName;
        $marketspace->save();
    
        echo  asset("storage/app/public/user/$imageName");
    
    
     }
    

     
public function pan_image(Request $request){
    $imageName = time().$request->image->getClientOriginalName();

    $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);

    $path =  storage_path();

    $img_path = $request->image->storeAs('public/masterspace', $imageName);

    $path = $path.'/app/'.$img_path;

    chmod($path, 0777);

   
    $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
    $marketspace->pan_image = $imageName;
    $marketspace->save();

    echo  asset("storage/app/public/masterspace/$imageName");


 }
 
 public function adhar_image(Request $request){
    $imageName = time().$request->image->getClientOriginalName();

    $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);

    $path =  storage_path();

    $img_path = $request->image->storeAs('public/masterspace', $imageName);

    $path = $path.'/app/'.$img_path;

    chmod($path, 0777);

   
    $marketspace =Marketspace::find(session('MARKETSPACE_ID'));
    $marketspace->adhar_image = $imageName;
    $marketspace->save();

    echo  asset("storage/app/public/masterspace/$imageName");


 }

 public function exp_cert(Request $request){
    $imageName = time().$request->image->getClientOriginalName();

    $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);

    $path =  storage_path();

    $img_path = $request->image->storeAs('public/masterspace', $imageName);

    $path = $path.'/app/'.$img_path;

    chmod($path, 0777);

    echo  asset("storage/app/public/masterspace/$imageName").'||'.$imageName;


 }

 public function quali_cert(Request $request){
    $imageName = time().$request->image->getClientOriginalName();

    $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);

    $path =  storage_path();

    $img_path = $request->image->storeAs('public/masterspace', $imageName);

    $path = $path.'/app/'.$img_path;

    chmod($path, 0777);

    echo  asset("storage/app/public/masterspace/$imageName").'||'.$imageName;


 }
 public function edu_cert(Request $request){
    $imageName = time().$request->image->getClientOriginalName();

    $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);

    $path =  storage_path();

    $img_path = $request->image->storeAs('public/masterspace', $imageName);

    $path = $path.'/app/'.$img_path;

    chmod($path, 0777);

    echo  asset("storage/app/public/masterspace/$imageName").'||'.$imageName;


 }
 
 
    
     public function deleteprofilephoto(Request $request){
    
        $path =  storage_path().'/app/public/profilepic/';
    
        \File::delete($path.Auth::user()->image);
    
        $user = Auth::user();
    
        $user->image='';
    
        $user->save();
    
        echo  asset("images/noimage.jpg");
    
     }

    
     public function logout() {
        Session::flush();
        Auth::logout();
        
        return Redirect('marketspace/login');
    }

    
    public function change_country(Request $request) {

        $state =  DB::select("select * from state where `country_id`='".$request->country_id."' order by id desc");

        $response = array();

        echo json_encode($state);

    }



    public function change_state(Request $request) {

        $district =  DB::select("select * from district where `state_id`='".$request->state_id."' order by name asc");

        $response = array();

        echo json_encode($district);

    }



    public function change_district(Request $request) {

        $state =  DB::select("select * from taluk where `district_id`='".$request->district_id."' order by name asc");

        $response = array();

        echo json_encode($state);

    }

    public function change_taluk(Request $request) {

        $user =  DB::select("select * from users where `taluk_id`='".$request->taluk_id."' order by business_name asc");

        $response = array();

        echo json_encode($user);

    }
    
    public function searchproduct_typemarketspace(Request $request) {
        $products =  DB::select("select * from products where `product_type_id`='".$request->product_type_id."' order by id desc");
        $response = array();
        echo json_encode($products);
    }
    
    public function searchcategory_typemarketspace(Request $request) {
        
        $products =  DB::select("SELECT product_type.name,product_type.id FROM products as products inner join product_type as product_type 
        ON products.product_type_id=product_type.id where products.category_id='".$request->category_type_id."' GROUP BY products.product_type_id");
        
        $response = array();
        echo json_encode($products);
    }
    public function search_request_service(Request $request) {


        $marketspace_skill = Marketspace_skills::where(function ($query) use ($request) {
           $query->whereRaw("find_in_set($request->product_id,product_id)");
            if($request->search_price>0){
                // $query->where('price', '>=', 0);
                // $query->where('price', '<=', $request->search_price);
                $query->whereBetween('price', [0,$request->search_price]);
            }
         
        })->get();

         $arr_pro=array();
       
        if(count($marketspace_skill)>0){
            foreach($marketspace_skill as $val)
            {
              
           $res = Marketspace_available_date::where(function ($query) use ($request,$val) {
            $query->whereIn('marketspace_id', [$val->marketspace_id]);
            if($request->start_date!=''){
                $query->where('start_date', '=',$request->start_date);
            }
            if($request->search_time>0)
            {
               $query->whereBetween('avail_hour', [0,$request->search_time]);
            }
            
        })->get();

        if(count($res)>0){
            $arr_pro[]=count($res);
        }
        
    }
}


        echo count($arr_pro);
    }
    
    
   
    public function add_contact_person(Request $request) {
        if($request->image_name1!='')
        {
        $imageName1 = time().$request->image_name1->getClientOriginalName();
        $imageName1 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);
        $path =  storage_path();
        $img_path = $request->image_name1->storeAs('public/contact', $imageName1);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName1="";
        }
        if($request->image_name2!='')
        {
        $imageName2 = time().$request->image_name2->getClientOriginalName();
        $imageName2 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName2);
        $path =  storage_path();
        $img_path = $request->image_name2->storeAs('public/contact', $imageName2);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName2="";
        }
        $contact_person = new Contact_person;
        $contact_person->name = $request->name;
        $contact_person->email = $request->email;
        $contact_person->phone = $request->phone;
        $contact_person->image_name1 = $imageName1;
        $contact_person->image_name2 = $imageName2;
        $contact_person->remark = $request->remark;
        $contact_person->title = $request->title;
        $contact_person->mobile = $request->mobile;
        $contact_person->whatsapp = $request->whatsapp;
        $contact_person->last_name = $request->last_name;
        $contact_person->contact_type = $request->contact_type;
        $contact_person->department = $request->department;
        $contact_person->designation = $request->designation;
        $contact_person->user_id = $request->user_id;
        //$contact_person->password =  Hash::make($request->password);
        $contact_person->save();
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        
        $contact_person = Contact_person::
        where('user_id', '=', $marketspace->user_id)
        ->get();
        echo json_encode($contact_person);
        }

        public function save_service_request(Request $request) {
         
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();

            $res = Marketspace_available_date::where(function ($query) use ($request) {
                    if($request->start_date!=''){
                     $query->where('start_date', '=',$request->start_date);
                 }
             })->groupBy('marketspace_id')->get();
             
             if(count($res)>0){
                foreach($res as $val){
                    $req_service = new Marketspace_request_service;
                    $req_service->title = $request->title;
                    $req_service->service_date = $request->start_date;
                    $req_service->product_id = $request->product_id;
                    $req_service->start_time = $request->start_time;
                    $req_service->service_type = $request->service_type;
                    $req_service->description = $request->description;
                    $req_service->auth_user = $request->contact_person;
                        $req_service->auth_by_user ='Y';
                        $req_service->service_staff = $val->marketspace_id;   
                        if(isset($request->preference)){
                            $req_service->preference = implode(',',$request->preference);
                        }
                    $req_service->marketspace_id=session('MARKETSPACE_ID');
                    $req_service->save();

                   if($req_service->id>0)
                   {
                    $marketspace = Marketspace::where('id', '=',$val->marketspace_id)->first();
                    // $apiKey = urlencode('NzYzNTc2NmY2ZTc2NzAzMzYzNDI0ZjU1MzczOTc2NDU=');
                    // $phone=$marketspace->phone;
                    // $numbers = array($phone);
                    // $sender = urlencode('BMENGC');
                    // $message = rawurlencode('This is remider from customer service request booked,reply yes to confirm - BMENGC');
                    // $numbers = implode(',', $numbers);
                    // $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
                    // $ch = curl_init('https://api.textlocal.in/send/');
                    // curl_setopt($ch, CURLOPT_POST, true);
                    // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    // $response = curl_exec($ch);
                    // curl_close($ch);

                        $datas = array(
                            'name'=> $marketspace->name??$marketspace->email,
                            'email'=>$marketspace->email, 
                            'url'=>url('/'),
                            'nmessagetxt'=>'This is remider from customer service request booked,reply yes to confirm - BMENGC',
                        ); 
                        $mail = new PHPMailer(true);

            try {                                     
                $mail->isMail();           
            
                $mail->setFrom('sales@biomedicalengineeringcompany.com', 'beczone');    
                $mail->addAddress($datas['email'], ' BMENGC');
                
                $mail->isHTML(true);                                  
                $mail->Subject = ' BMENGC';
                $mail->Body    = view('email.otp',$datas)->render(); 
                $mail->send();
                echo "Mail has been sent successfully!";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            } 
                   }     

                }
             }
             /**/
     
            

        }


    private function getToken($length, $seed){    
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randstring = '';
        for ($i = 0; $i < 10; $i++) {
            $randstring = $characters[rand(0, strlen($characters))];
        }
        return substr(time(), -3).$randstring;
    }
    public function viewbid($marketspace_id){
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        $data =Marketspace_request_service::where('marketspace_id', '=',$marketspace_id)->groupBy('product_id')->get();
       return view('marketspace.viewbid',compact('marketspace','data'));  
    }
    public function allservicerequest()
    {
       
        if(session('MARKETSPACE_ID')){
            $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
            if($marketspace->user_type=="Hire"){
                //customer
                $query =Marketspace_request_service::where(function ($query) {
                    $query->where('marketspace_id', '=',session('MARKETSPACE_ID'))
                    ->orwhere('service_staff', '=',session('MARKETSPACE_ID'));
                });
                $query->where('status', '!=','Accept');
                $query->where('status', '!=','Completed');
                $query->where('status', '!=','Reject');
                $query->where('status', '!=','Reject_Staff');
                $query->groupBy('marketspace_id');
                $query->groupBy('product_id');
                $query->orderBy('id','DESC');
                $data=$query->paginate(20);
            }
            else{
                //service staff
                $query =Marketspace_request_service::where(function ($query) {
                    $query->where('marketspace_id', '=',session('MARKETSPACE_ID'))
                    ->orwhere('service_staff', '=',session('MARKETSPACE_ID'));
                });
                $query->where('status', '!=','Accept');
                $query->where('status', '!=','Completed');
                $query->where('status', '!=','Reject');
                $query->where('status', '!=','Reject_Staff');
                
                $query->orderBy('id','DESC');
                $data=$query->paginate(20);
            }
          

            $query =Marketspace_request_service::where(function ($query) {
                $query->where('marketspace_id', '=',session('MARKETSPACE_ID'))
                ->orwhere('service_staff', '=',session('MARKETSPACE_ID'));
            });
            $query->where('status', '=','Accept');
            $query->orderBy('id','DESC');
            $data_accept=$query->paginate(20);

            if($marketspace->user_type=='Hire')
            {
                $query =Marketspace_request_service::where(function ($query) {
                    $query->where('marketspace_id', '=',session('MARKETSPACE_ID'))
                    ->orwhere('service_staff', '=',session('MARKETSPACE_ID'));
                });
                $query->where('status', '=','Reject')->orwhere('status', '=','Reject_Staff');
                $query->orderBy('id','DESC');
                $data_reject=$query->paginate(20);  
            }else{
                $query =Marketspace_request_service::where(function ($query) {
                    $query->where('marketspace_id', '=',session('MARKETSPACE_ID'))
                    ->orwhere('service_staff', '=',session('MARKETSPACE_ID'));

                    $query->where('status', '=','Reject')->orwhere('status', '=','Reject_Staff');;
                });
               
                $query->orderBy('id','DESC');
                $data_reject=$query->paginate(20);  
            }
            

            $query =Marketspace_request_service::where(function ($query) {
                $query->where('marketspace_id', '=',session('MARKETSPACE_ID'))
                ->orwhere('service_staff', '=',session('MARKETSPACE_ID'));
            });
            $query->where('status', '=','Completed');
            $query->orderBy('id','DESC');
            $data_completed=$query->paginate(20); 

           
            return view('marketspace.request',compact('marketspace','data','data_accept','data_reject','data_completed'));
        }
    
        return redirect("marketspace.login")->withSuccess('Opps! You do not have access');
    }
    
    public function approve_service_staff(Request $request) {
        $req_service =Marketspace_request_service::find($request->accept_id);
        $req_service->service_approve_date = $request->accept_date;
        $req_service->service_staff_time = $request->accept_time;
        $req_service->service_staffquote_price = $request->accept_quote_price;
        
        $req_service->status = 'Accept_staff';
        $req_service->save();

        $marketspace_customer =Marketspace::find($req_service->marketspace_id);
       
        $datas = array(
            'name'=> $marketspace_customer->name??$marketspace_customer->email,
            'email'=>$marketspace_customer->email, 
            'url'=>url('/'),
            'nmessagetxt'=>'Service request accepted - BMENGC',
        );
 
        try {                                     
            $mail->isMail();           
        
            $mail->setFrom('sales@biomedicalengineeringcompany.com', 'beczone');    
            $mail->addAddress($datas['email'], ' BMENGC');
            
            $mail->isHTML(true);                                  
            $mail->Subject = ' BMENGC';
            $mail->Body    = view('email.otp',$datas)->render(); 
            $mail->send();
            echo "Mail has been sent successfully!";
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        } 
        // $apiKey = urlencode('NzYzNTc2NmY2ZTc2NzAzMzYzNDI0ZjU1MzczOTc2NDU=');
        // $phone=$marketspace_customer->phone;
        // $numbers = array($phone);
        // $sender = urlencode('BMENGC');
        // $message = rawurlencode('Service request accepted - BMENGC');
        // $numbers = implode(',', $numbers);
        // $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
        // $ch = curl_init('https://api.textlocal.in/send/');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // $response = curl_exec($ch);
        // curl_close($ch);

    }
    public function approve_service_customer(Request $request) {
        $req_service =Marketspace_request_service::find($request->customer_accept_id);
        $req_service->status = 'Accept';
        $req_service->save();

        $marketspace_customer =Marketspace::find($req_service->service_staff);

        /**
         *--------------------------------2023-10-19 Start-----------------------------* 
        */

            // echo $marketspace_customer->phone;
            echo $marketspace_customer->email;

        /**
         *--------------------------------2023-10-19 End-----------------------------* 
        */
    }
    public function complete_service_request(Request $request) {
        $req_service =Marketspace_request_service::find($request->customer_complete_id);
        $req_service->status = 'Completed';
        $req_service->save();

        $marketspace =Marketspace::find($req_service->marketspace_id);
        
        /**
         *--------------------------------2023-10-19 Start-----------------------------* 
        */

            // echo $marketspace->phone;
            echo $marketspace->email;

        /**
         *--------------------------------2023-10-19 End-----------------------------* 
        */

    }
    public function reject_service_request(Request $request) {
        $staff_id='';
        $req_service =Marketspace_request_service::find($request->customer_reject_id);

        if($request->delete_reason=="forgetting_eng")
        {
        if($req_service->service_date!=$request->start_date || $req_service->start_time!=$request->start_time)
        {
            $res = Marketspace_available_date::where(function ($query) use ($request) {
                if($request->start_date!=''){
                 $query->where('start_date', '=',$request->start_date);
             }
         })->groupBy('marketspace_id')->get();
         
         if(count($res)>0){
            foreach($res as $val){

                $req_service_save = new Marketspace_request_service;
                $req_service_save->title = $req_service->title;
                $req_service_save->service_date = $request->start_date;
                $req_service_save->product_id = $req_service->product_id;
                $req_service_save->start_time = $request->start_time;
                $req_service_save->service_type = $req_service->service_type;
                
                $req_service_save->description = $req_service->description;
                $req_service_save->auth_user = $req_service->contact_person;
               
                    $req_service_save->auth_by_user ='Y';
                    $req_service_save->service_staff = $val->marketspace_id;   
                    // if(isset($request->preference)){
                    //     $req_service->preference = implode(',',$request->preference);
                    // }
                $req_service_save->marketspace_id=session('MARKETSPACE_ID');
                $req_service_save->save();

            }
         }
         echo 1;
        }else{
            echo 0;
        }

    }else{
        echo 1;
        
    }

        $req_service_reject =Marketspace_request_service::find($request->customer_reject_id);
        $req_service_reject->status = 'Reject';
        $req_service_reject->delete_reason =$request->delete_reason;
        $req_service_reject->save();

        
       /* $apiKey = urlencode('NzYzNTc2NmY2ZTc2NzAzMzYzNDI0ZjU1MzczOTc2NDU=');
        $phone=$marketspace->phone;
        $numbers = array($phone);
        $sender = urlencode('BMENGC');
        $message = rawurlencode('Service request rejected - BMENGC');
        $numbers = implode(',', $numbers);
        $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
        $ch = curl_init('https://api.textlocal.in/send/');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        */
      /*  $req_service = Marketspace_request_service::where('id', '=', $request->customer_reject_id)->first();

        if($req_service->marketspace_id==session('MARKETSPACE_ID'))
        {
            $marketspace =Marketspace::find($req_service->service_staff);
            echo $marketspace->phone;
        }else{
            $marketspace =Marketspace::find($req_service->marketspace_id);
            echo $marketspace->phone;
        }
        
        
        $marketspace = Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
        
            $user_details = User::where('id', '=',$marketspace->user_id)->first();
            if($user_details){
                $district_id=$user_details->district_id;
            }else{$district_id=0;}

            $marketspace_skill = Marketspace_skills::where(function ($query) use ($req_service) {
                $query->whereRaw("find_in_set($req_service->product_id,product_id)");
                $query->where('marketspace_id', '!=',$req_service->service_staff);
             })->get();
     
              $arr_pro=array();
              $arr_location=array();
              
             if(count($marketspace_skill)>0){
                 foreach($marketspace_skill as $val)
                 {
                $res = Marketspace_available_date::where(function ($query) use ($req_service,$val) {
                 $query->whereIn('marketspace_id', [$val->marketspace_id]);
                 if($req_service->service_date!=''){
                     $query->where('start_date', '=',$req_service->service_date);
                 }
                
                 
             })->get();
     
             if(count($res)>0){
                foreach($res as $val)
                {
                    if($district_id>0)
                    {
                        $location=Marketspace_location::where('marketspace_id', '=',$val->marketspace_id)
                        ->where('district_id', '=',$district_id)
                        ->get();
                        if(count($location)>0 ){
                            $arr_location[]=$val->marketspace_id;
                        }
                        else{
                            $arr_pro[]=$val->marketspace_id;
                        }
                    }
                    else{
                        $arr_pro[]=$val->marketspace_id;
                    }
                   
                    
                 //$arr_pro[]=$val->marketspace_id;
                }
             }
             
         }
     }

   
     if(count($arr_location)>0){
        if(count($arr_location)==1)
        {
            $staff_id=$arr_location[0];
        }
        else{
            $arr=array();
            foreach($arr_location as $val){
                $chk_service_exit=Marketspace_request_service::where('service_date', '=',$req_service->service_date)
                ->where('marketspace_id', '=',$val)
                ->get();
                    if(count($chk_service_exit)==0){
                        $arr[]=$val;
                    }
            }
            if(count($arr)>0){
                $k = array_rand($arr);
                $staff_id = $arr[$k];
               
            }else{
                $k = array_rand($arr_location);
                $staff_id = $arr_location[$k];
            }

        }
     }

      if(count($arr_pro)>0 && count($arr_location)==0){
        
         $k = array_rand($arr_pro);
         $staff_id = $arr_pro[$k];
        if(count($arr_pro)==1)
        {
            $staff_id=$arr_pro[0];
        }
        else{
            $arr=array();
            foreach($arr_pro as $val){
                $chk_service_exit=Marketspace_request_service::where('service_date', '=',$req_service->service_date)
                ->where('marketspace_id', '=',$val)
                ->get();
                    if(count($chk_service_exit)==0){
                        $arr[]=$val;
                    }
            }
            if(count($arr)>0){
                $k = array_rand($arr);
                $staff_id = $arr[$k];
            }else{
                $k = array_rand($arr_pro);
                $staff_id = $arr_pro[$k];
            }

        }

     }
    
     
     if($staff_id>0){
        $req_service_update =Marketspace_request_service::find($request->customer_reject_id);
        $req_service_update->service_staff = $staff_id;
        $req_service_update->status ='Progress';
        $req_service_update->save();

        $req_service_add = new Marketspace_request_service;
        $req_service_add->title = $req_service->title;
        $req_service_add->service_date = $req_service->service_date;
        $req_service_add->product_id = $req_service->product_id;
        $req_service_add->service_staff = $req_service->service_staff;
        $req_service_add->description = $req_service->description;
        $req_service_add->status = 'Reject_Staff';
        $req_service_add->marketspace_id=session('MARKETSPACE_ID');
        $req_service_add->save();
     }else{
        $req_service =Marketspace_request_service::find($request->customer_reject_id);
        $req_service->status = 'Reject';
        $req_service->save();
     }
           */ 

    

       /* */
    }
 
    public function reject_service_request_auth_user(Request $request) {
        
        $req_service =Marketspace_request_service::find($request->customer_reject_id);
        $req_service->status = 'Reject';
        $req_service->save();
        $marketspace =Marketspace::find($req_service->marketspace_id);

        /**
         *--------------------------------2023-10-19 Start-----------------------------* 
        */

        // echo $marketspace->phone;
        echo $marketspace->email ;

        /**
         *--------------------------------2023-10-19 End-----------------------------* 
        */ 
        
    }
    public function accept_service_request_auth_user(Request $request) {
        $req_service =Marketspace_request_service::find($request->customer_reject_id);
        $req_update =Marketspace_request_service::find($request->customer_reject_id);
        $req_service->service_staff = $req_service->service_wait_auth;
        $req_service->auth_by_user = 'Y';
        $req_service->save();

        $marketspace_customer =Marketspace::find($req_service->marketspace_id);
        $marketspace_service =Marketspace::find($req_service->service_wait_auth);

        /**
         *--------------------------------2023-10-19 Start-----------------------------* 
        */

        // echo $marketspace_customer->phone.'*'.$marketspace_service->phone;
            echo $marketspace_customer->email.'*'.$marketspace_service->email;

        /**
         *--------------------------------2023-10-19 End-----------------------------* 
        */
    }

    
    public function ajaxChangeStatus(Request $request) {

        $table  = $request->from; //($request->from == 'cms') ? 'content': $from;

        $data   = DB::table($table)->where([['id', $request->id ]])->first();

        $status = ($data->status == 'Y') ? 'N': 'Y';

        

        DB::table($table)

        ->where('id', $request->id)

        ->update(['status' => $status]);

        //print_r($data);

        return response()->json(['success'=>"Status updated successfully.", 'status'=>$status]);

    }
    public function send_sms_message(Request $request) {
        $email=substr($request->number,1);

        if($request->type=="service_request")
        {
            
            $datas = array(
                'name'=> $email,
                'email'=>$email, 
                'url'=>url('/'),
                'nmessagetxt'=>'This is remider from customer service request booked,reply yes to confirm - BMENGC',
            );

            $mail = new PHPMailer(true);

            try {                                     
                $mail->isMail();           
            
                $mail->setFrom('sales@biomedicalengineeringcompany.com', 'beczone');    
                $mail->addAddress($datas['email'], ' BMENGC');
                
                $mail->isHTML(true);                                  
                $mail->Subject = ' BMENGC';
                $mail->Body    = view('email.otp',$datas)->render(); 
                $mail->send();
                echo "Mail has been sent successfully!";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            } 
        }

        if($request->type=="service_accepted")
        {

            $datas = array(
                'name'=> $email,
                'email'=>$email, 
                'url'=>url('/'),
                'nmessagetxt'=>'Service request accepted - BMENGC',
            );

            $mail = new PHPMailer(true);

            try {                                     
                $mail->isMail();           
            
                $mail->setFrom('sales@biomedicalengineeringcompany.com', 'beczone');    
                $mail->addAddress($datas['email'], ' BMENGC');
                
                $mail->isHTML(true);                                  
                $mail->Subject = ' BMENGC';
                $mail->Body    = view('email.otp',$datas)->render(); 
                $mail->send();
                echo "Mail has been sent successfully!";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            } 
        }

        if($request->type=="service_rejected")
        {


            $datas = array(
                'name'=> $email,
                'email'=>$email, 
                'url'=>url('/'),
                'nmessagetxt'=>'Service request rejected - BMENGC',
            );

            $mail = new PHPMailer(true);

            try {                                     
                $mail->isMail();           
            
                $mail->setFrom('sales@biomedicalengineeringcompany.com', 'beczone');    
                $mail->addAddress($datas['email'], ' BMENGC');
                
                $mail->isHTML(true);                                  
                $mail->Subject = ' BMENGC';
                $mail->Body    = view('email.otp',$datas)->render(); 
                $mail->send();
                echo "Mail has been sent successfully!";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            } 
        }

        if($request->type=="service_complete")
        {


            $datas = array(
                'name'=> $email,
                'email'=>$email, 
                'url'=>url('/'),
                'nmessagetxt'=>'Service request completed- BMENGC',
            );

            $mail = new PHPMailer(true);

            try {                                     
                $mail->isMail();           
            
                $mail->setFrom('sales@biomedicalengineeringcompany.com', 'beczone');    
                $mail->addAddress($datas['email'], ' BMENGC');
                
                $mail->isHTML(true);                                  
                $mail->Subject = ' BMENGC';
                $mail->Body    = view('email.otp',$datas)->render(); 
                $mail->send();
                echo "Mail has been sent successfully!";
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            } 
        }

    }

    public function old_send_sms_message(Request $request) {
        $num=$request->number;
        if($request->type=="service_request")
        {
                $apiKey = urlencode('NzYzNTc2NmY2ZTc2NzAzMzYzNDI0ZjU1MzczOTc2NDU=');
                $phone=$num;
                $numbers = array($phone);
                $sender = urlencode('BMENGC');
                $message = rawurlencode('This is remider from customer service request booked,reply yes to confirm - BMENGC');
                $numbers = implode(',', $numbers);
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
        }
        if($request->type=="service_accepted")
        {
                $apiKey = urlencode('NzYzNTc2NmY2ZTc2NzAzMzYzNDI0ZjU1MzczOTc2NDU=');
                $phone=$num;
                $numbers = array($phone);
                $sender = urlencode('BMENGC');
                $message = rawurlencode('Service request accepted - BMENGC');
                $numbers = implode(',', $numbers);
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
        }
        if($request->type=="service_rejected")
        {
                $apiKey = urlencode('NzYzNTc2NmY2ZTc2NzAzMzYzNDI0ZjU1MzczOTc2NDU=');
                $phone=$num;
                $numbers = array($phone);
                $sender = urlencode('BMENGC');
                $message = rawurlencode('Service request rejected - BMENGC');
                $numbers = implode(',', $numbers);
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
        }
        if($request->type=="service_complete")
        {
                $apiKey = urlencode('NzYzNTc2NmY2ZTc2NzAzMzYzNDI0ZjU1MzczOTc2NDU=');
                $phone=$num;
                $numbers = array($phone);
                $sender = urlencode('BMENGC');
                $message = rawurlencode('Service request completed- BMENGC');
                $numbers = implode(',', $numbers);
                $data = array('apikey' => $apiKey, 'numbers' => $numbers, "sender" => $sender, "message" => $message);
                $ch = curl_init('https://api.textlocal.in/send/');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);
                curl_close($ch);
        }
        
    }

    


    
    
}
