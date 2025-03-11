<?php

namespace App\Http\Controllers\staff;


use App\Models\CoordinatorPermission;
use App\Models\Taluk;
use App\Models\User_permission;
use App\Models\Country;
use App\Models\Staff;
use App\Models\Users_shipping_address;
use App\Models\Customercategory;
use App\Models\Assign_supervisor;

use App\Models\District;
use Yajra\DataTables\Facades\DataTables;


use App\Hosdeparment;
use App\Hosdesignation;

use App\Contact_person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

use Image;
use Storage;

use App\Exportscustomer\UsersExport;

use App\Importscustomer\UsersImport;
use App\Models\State;
use App\Models\User;
use App\Oppertunity;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
       //$user = User::all();
       $staff_id = session('STAFF_ID'); 
       
        $permission = User_permission::where('staff_id', $staff_id)->first();

        $cor_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type','customer')->first();

            if(optional($permission)->customer_view != 'view' && optional($cor_permission)->customer_view != 'view')
            {
                    return redirect()->route('staff.dashboard');
            }

       
       
            $user= DB::select('select * from users   ORDER BY `updated_at` DESC'); 

            // if($staff_id==32)
            // {
            //     $user= DB::select('select * from users   ORDER BY `updated_at` DESC'); 
            // }
            // else{
            //     $user= DB::select('select * from users where FIND_IN_SET('.$staff_id.',staff_id)  ORDER BY `updated_at` DESC'); 
            // 
        
            if ($request->ajax()) {

                $data = User::where('id','>',0)->with('customer_category','usertaluk');

                if(optional($cor_permission)->customer_view != 'view')
                {
                    $data->where('staff_id',$staff_id);
                }
                elseif(optional($cor_permission)->customer_view == 'view' && optional($permission)->customer_view != 'view' )
                {
                    $data->where('staff_id','!=',$staff_id);
                }

                if (!empty($request->state)) 
                {
                    $data->where('state_id',$request->state);
                }

                if (!empty($request->district)) 
                {
                    $data->where('district_id',$request->district);
                }
                if (!empty($request->taluk)) 
                {
                    $data->where('taluk_id',$request->taluk);
                }
                

                return DataTables::of($data)

                ->addColumn('business_name_edit', function ($data) use ($staff_id,$cor_permission,$permission) {

                    
                    if (optional($cor_permission)->customer_edit == 'edit' || optional($permission)->customer_edit == 'edit') {

                         if ($data->staff_id == $staff_id && optional($permission)->customer_edit == 'edit')
                         {
                            return '<a href="' . route('staff.customer.edit', $data->id) . '">' . $data->business_name . '</a>';
                         }
                         elseif($data->staff_id != $staff_id && optional($cor_permission)->customer_edit == 'edit')
                         {
                            return '<a href="' . route('staff.customer.edit', $data->id) . '">' . $data->business_name . '</a>';
                         }
                         else
                         {
                            return $data->business_name;
                         }

                    } else {

                        return $data->business_name;
                    }
                })
                

                ->addColumn('customer_category', function ($data) {
                  
                    if(!empty($data->customer_category_id))
                    {

                        return optional($data->customer_category)->name;
                    }
                    else
                    {
                        return "";
                    }
                })

                ->addColumn('taluk', function ($data) {
                  
                    if(!empty($data->taluk_id))
                    {
                        return optional($data->usertaluk)->name;
                    }
                    else
                    {
                        return "";
                    }
                })
                
                ->addColumn('delete_action', function ($data) use ($staff_id,$cor_permission,$permission) {
                  
                    if (optional($cor_permission)->customer_delete == 'delete' || optional($permission)->customer_delete == 'delete') {

                        if ($data->staff_id == $staff_id && optional($permission)->customer_delete == 'delete')
                        {
                           return '<input type="checkbox" class="dataCheck" name="ids[]" value="'.$data->id.'" id="check'.$data->id.'">';
                        }
                        elseif($data->staff_id != $staff_id && optional($cor_permission)->customer_delete == 'delete')
                        {
                           return '<input type="checkbox"  class="dataCheck" name="ids[]" value="'.$data->id.'" id="check'.$data->id.'">';
                        }
                        else
                        {
                           return '';
                        }

                   } else {

                       return '';
                   }
                })

                ->rawColumns(['business_name_edit','delete_action'])

                ->addIndexColumn()->make(true);

                // ->setRowId('id')

                // ->setRowAttr([
                //     'data-user_id' => '{{ $user_id }}',
                // ])

                // ->setRowClass(function ($data) {

                //     return "";
                // })

            }

        $stateIds = User::pluck('state_id')->unique()->toArray();

        $talukIDs = User::pluck('taluk_id')->unique()->toArray();

        $states = State::whereIn('id', $stateIds)->orderBy('name', 'asc')->get();

        $districts = District::orderBy('name', 'asc')->get();

        $taluks = Taluk::whereIn('id', $talukIDs)->orderBy('name', 'asc')->get();

       
       return view('staff.customer.index', compact('user','states','districts','taluks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $country = Country::all();
        $customer_category= Customercategory::all();
        $staff = Staff::all();
        return view('staff.customer.create', compact('country','staff','customer_category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, array(
            'business_name' => 'required|max:100',
            'email' => 'required|email'
           
        ));
       
        $admin_id = session('ADMIN_ID');
            
        $admin = DB::table('admin')->where([
                    ['id', $admin_id]
                ])
                ->first();
       
        $user = new User;
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->email = $request->email;

        $user->added_by_name = $admin->surname;
        $user->added_by_id = $admin_id;

        $user->business_name = $request->business_name;
        $user->address1 = $request->address1;
        
        $user->gst = $request->gst;
     
      //  $user->city = $request->city;
        $user->zip = $request->zip;
        $user->password =  Hash::make($request->password);
         
        $user->staff_id=$request->staff_id;
        $staff = Staff::find($request->staff_id);
        if($staff)
        {
            $user->staff_email=$staff->email;
        }
      
        $user->country_id=$request->country_id;
        $country = Country::find($request->staff_id);
        if($country)
        {
            $user->country_name=$country->name;
        }

        $user->state_id=$request->state_id;
        $state = State::find($request->state_id);
        if($state)
        {
            $user->state_name=$state->name;
        }

        $user->district_id=$request->district_id;
        $district = District::find($request->district_id);
        if($district)
        {
            $user->district_name=$district->name;
        }

        $user->taluk_id=$request->taluk_id;
        $taluk = Taluk::find($request->taluk_id);
        if($taluk)
        {
            $user->taluk_name=$taluk->name;
        }
        $user->customer_category_id = $request->customer_category_id;
        
        
        $user->account_name = $request->account_name;
        $user->bank_name = $request->bank_name;
        $user->bank_address = $request->bank_address;
        $user->ifsc_code = $request->ifsc_code;
        $user->account_no = $request->account_no;
        if($request->image_name1!='')
        {
            $imageName1 = time().$request->image_name1->getClientOriginalName();
            $imageName1 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);
            $path =  storage_path();
            $img_path = $request->image_name1->storeAs('public/user', $imageName1);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777);
        }   
        
        if($request->image_name2!='')
        {
            $imageName2 = time().$request->image_name2->getClientOriginalName();
            $imageName2 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName2);
            $path =  storage_path();
            $img_path = $request->image_name2->storeAs('public/user', $imageName2);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777);
        }  
        $user->image_name1 = isset($imageName1) ? $imageName1: '';
        $user->image_name2 = isset($imageName2) ? $imageName2: '';

        $user->save();

        $j=0;
        if($request->shipping_address1[0]!='')
        {
            foreach($request->shipping_address1 as $values)
            {
            
                $users_shipping = new Users_shipping_address;
                $users_shipping->user_id=$user->id;
                $users_shipping->country_id=$request->shipping_country_id[$j];
                $users_shipping->address1=$request->shipping_address1[$j];
                $users_shipping->address2=$request->shipping_address2[$j];
                $users_shipping->city=$request->shipping_city[$j];
                $users_shipping->state=$request->shipping_state[$j];
                $users_shipping->zip=$request->shipping_zip[$j];
              
                $users_shipping->save();
                $j++;
            }
        }
        
        
        return redirect()->route('staff.customer.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $user = User::withTrashed()->find($id);
        
        $country = Country::all();

        $hosdeparment = Hosdeparment::all();
        $hosdesignation = Hosdesignation::all();
        
        
       // $contact_person = Contact_person::all();
     //  $contact_person   = DB::table("contact_person")->where([['user_id', $id]])->orderBy('updated_at', 'DESC')->get();
     $contact_person= DB::select('select * from contact_person where  `user_id`="'.$id.'" ORDER BY `created_at` DESC'); 
        return view('staff.customer.contact', compact('user','country','contact_person','hosdeparment','hosdesignation'));
    }

    public function contact($id)
    {   echo $id.'--';exit;
        $user = User::find($id);
        $country = Country::all();
        return view('staff.customer.contact', compact('user','country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit($id,User $user)
    {
        $user = User::find($id);
        $country = Country::all();
        $state = State::all();
        $district = District::all();
        $taluk = Taluk::all();
        $staff = Staff::all();
        $customer_category= Customercategory::all();
        //Users_shipping_address

        $state= DB::select('select * from state where  `country_id`="'.$user->country_id.'" ORDER BY `name` ASC'); 
        $district= DB::select('select * from district where  `state_id`="'.$user->state_id.'" ORDER BY `name` ASC'); 
        $taluk= DB::select('select * from taluk where  `district_id`="'.$user->district_id.'" ORDER BY `name` ASC'); 
        
        $users_shipping_address = Users_shipping_address::where('user_id', $id)->orderBy('id', 'asc')->get();
        return view('staff.customer.edit', compact('user','customer_category','country','state','district','taluk','users_shipping_address','staff'));
       // return view('admin.subcategory.edit', compact('subcategory'), ['parents'=> $parents,'first'=> $first]);
    }

   
    public function update($id, Request $request, User $user)
    {
      
        $this->validate($request, array(
           
            'name' => 'required'
           
        ));
       

        $user = User::find($id);

        
        $user->name = $request->name;
        $user->zip = $request->zip;

        $user->customer_category_id = $request->customer_category_id;
        
        $user->gst = $request->gst;


        $user->country_id=$request->country_id;
        $country = Country::find($request->staff_id);
        if($country)
        {
            $user->country_name=$country->name;
        }

        $user->state_id=$request->state_id;
        $state = State::find($request->state_id);
        if($state)
        {
            $user->state_name=$state->name;
        }

        $user->district_id=$request->district_id;
        $district = District::find($request->district_id);
        if($district)
        {
            $user->district_name=$district->name;
        }

        $user->taluk_id=$request->taluk_id;
        $taluk = Taluk::find($request->taluk_id);
        if($taluk)
        {
            $user->taluk_name=$taluk->name;
        }
     
        $user->save();

        return redirect()->route('staff.customer.index')->with('success', 'Data successfully saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, User $user)
    {
        $user = User::find($id);
        $user->delete(); 
        
        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$user->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;        
        DB::table("users")->whereIn('id',$ids)->delete();
        return redirect()->route('staff.customer.index')->with('success', 'Data has been deleted successfully');
    }

    public function importExportViewCustomer()
    {
        return view('staff.customer.importExportView');
    }
    public function exportproductcustomer() {
        return Excel::download(new UsersExport, 'Customer.xlsx');
   
       }
   
    public function importproductcustomer() 
    {
        Excel::import(new UsersImport,request()->file('file'));
        return back();
    }

    public function changestatus() 
    {
        $staff_id = session('STAFF_ID');   
       $viewstaff=array(); 
      // echo 'select * from assign_supervisor where  `supervisor_id`="'.$staff_id.'" ';
       $user_list= DB::select('select * from assign_supervisor where  `supervisor_id`="'.$staff_id.'" '); 
       foreach($user_list as $values)
       {
        $viewstaff[]=$values->staff_id;
       } 
      
       $all_staff= implode(",",$viewstaff);
       
     // echo 'select * from users  where FIND_IN_SET("'.$all_staff.'", staff_id) ';
     //  $user= DB::select('select * from users  where FIND_IN_SET("'.$all_staff.'", staff_id) '); 
       
       return view('staff.customer.changestatus', compact('viewstaff'));
    }
    

}
