<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Staff;
use App\Contact_person;
use App\Leadoption;
use App\Oppertunity;

use Validator;
use Auth;
use DB;

class LeadoptionController extends Controller
{
    public function index()
    {
        $staff_id                   = session('STAFF_ID');
        $user_permission =  DB::select("SELECT * FROM `user_permission` WHERE FIND_IN_SET('Opportunity',section) AND staff_id='".$staff_id."'" );
        if(count($user_permission)>0)
        {
            $lead_option				= Leadoption::orderBy('id','desc')->get();
        }
        else{
         $lead_option				= Leadoption::where('staff_id',$staff_id)->orWhere('created_by_id',$staff_id)->orderBy('id','desc')->get();
      
        }
    	return view('staff.leadoption.index',array('leadop'=>$lead_option,'user_permission'=>count($user_permission)));
    }

    
    public function cancel(Request $request,$id)
    {
    	$leadoption 					= Leadoption::find($id);
    	$leadoption->staff_status 		= 'Cancel';
    	$leadoption->save();

		$request->session()->flash('success', 'Lead Option Cancelled Successfully');

		return redirect('staff/staff_lead_option');
    }

    public function convert(Request $request,$id)
    {
        
        $leadoption                          = Leadoption::find($id);
        $oppertunity                         = new Oppertunity;
        $oppertunity->user_id                = $leadoption->user_id;
        $oppertunity->staff_id               = $leadoption->staff_id;
        $oppertunity->description            = $leadoption->description;
        $oppertunity->deal_stage             = 1;
        $oppertunity->support                = 1;
        $oppertunity->order_forcast_category = 1;
        $oppertunity->save();

        $leadoption->staff_status           = 'Converted to Opportunity';
        $leadoption->save();

        $request->session()->flash('success', 'Lead Option converted to Opportunity');

        return redirect('staff/staff_lead_option');
    }

    public function create()
    {
        $user               = User::all();
        $staff_id           = session('STAFF_ID');
        $staff              = Staff::where('id','<>',$staff_id)->get();

        return view('staff.leadoption.create',array('customer'=>$user,'staff'=>$staff));
    }

    public function loadcontacts($id)
    {
        $contact            = Contact_person::where('user_id',$id)->get();
       
        foreach($contact as $row)
        {
            echo "<option value='$row->id'>$row->name</option>";
        }
    }

    public function insert(Request $request)
    {
        $validation = Validator::make($request->all(), [
            //'customer_name' => 'required',
            'staff_name'    => 'required',
            //'contact_name'  => 'required',
            'description'   => 'required',
        ]);

        $staff_id                       = session('STAFF_ID');

        $leadoption                     = new Leadoption;
        $leadoption->user_id            = $request->customer_name;
        $leadoption->contact_person_id  = $request->contact_name;
        $leadoption->staff_id           = $request->staff_name;
        $leadoption->description        = $request->description;
        $leadoption->created_by_id      = $staff_id;
        $leadoption->created_by_name    = Staff::where('id',$staff_id)->first()->name;
        if(isset($request->new_customer))
        {
            $leadoption->customer_name          = $request->new_customer;
            $leadoption->contact_person_name    = $request->new_contact;
        }
        $leadoption->save();

        $request->session()->flash('success', 'Lead Option added Successfully');

        return redirect('staff/staff_lead_option');
    }

    public function edit($id)
    {
        $user               = User::all();
        $staff_id           = session('STAFF_ID');
        $staff              = Staff::where('id','<>',$staff_id)->get();
        $leadoption         = Leadoption::find($id);
        $contact            = Contact_person::where('user_id',$leadoption->user_id)->get();

        return view('staff.leadoption.edit',array('customer'=>$user,'staff'=>$staff,'lead'=>$leadoption,'id'=>$id,'contact'=>$contact));
    }

    public function update($id)
    {
        $validation = Validator::make($request->all(), [
            //'customer_name' => 'required',
            'staff_name'    => 'required',
           // 'contact_name'  => 'required',
            'description'   => 'required',
        ]);

        $leadoption                     = Leadoption::find($id);
        $leadoption->user_id            = $request->customer_name;
        $leadoption->contact_person_id  = $request->contact_name;
        $leadoption->staff_id           = $request->staff_name;
        $leadoption->description        = $request->description;
        if(isset($request->new_customer))
        {
            $leadoption->customer_name          = $request->new_customer;
            $leadoption->contact_person_name    = $request->new_contact;
        }
        $leadoption->save();

        $request->session()->flash('success', 'Lead Option updated Successfully');

        return redirect('staff/staff_lead_option');
    }
    public function my_lead()
    {
        $id                         = session('STAFF_ID');
        $lead_option                = Leadoption::where('created_by_id',$id)->orderBy('id','desc')->get();
        return view('staff.leadoption.created_lead',array('leadop'=>$lead_option));
    }
}
