<?php

namespace App\Http\Controllers\staff;

use App\Service_task;
use App\Contact_person;

use App\Service_responce;
use App\State;
use App\District;
use App\User;
use App\Quote;
use App\Staff;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class Service_taskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $service_task = Service_task::all();
       $contact_person = Contact_person::all();
       $state = State::all();
       $product = Product::all();
       $district = District::all();
       $staff = Staff::all();
     //  print_r($product);
       return view('staff.service_task.index', compact('staff','product','service_task','contact_person','state','district'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service_task = Service_task::all();
        $contact_person = Contact_person::all();
        $state = State::all();
        $product = Product::all();
        $district = District::all();
        $staff = Staff::all();

        return view('staff.service_task.create', compact('staff','service_task','contact_person','state','district','product'));
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
                'start_date' => 'required'
             
            ));

        $service_task = new Service_task;

        $service_task->service_ref_no = $request->service_ref_no_val;
        $service_task->start_date = $request->start_date;
        $service_task->end_date = $request->end_date;
        $service_task->schedule_date = $request->schedule_date;
        $service_task->service_type = $request->service_type;
        $service_task->other_ref = $request->other_ref;
        $service_task->service_status = $request->service_status;
        $service_task->next_schedule_date = $request->next_schedule_date;
        
        $service_task->job_type = $request->job_type;
        $service_task->state_id = $request->state_id;
        $service_task->district_id = $request->district_id;
        $service_task->user_id = $request->user_id;
        $service_task->contact_id = $request->contact_id;
        $service_task->product_id = $request->product_id;
        $service_task->cus_eq_status = $request->cus_eq_status;
        $service_task->serial_no = $request->serial_no;
        $service_task->staff_id = $request->staff_id;
        $service_task->contact_no = $request->contact_no_val;
        $service_task->email = $request->email_val;
        $service_task->problem_reported = $request->problem_reported;
        $service_task->prob_desc = $request->prob_desc;
        $service_task->save();


        return redirect()->route('staff.service_task.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(State $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\State  $state
     * @return \Illuminate\Http\Response
     */
    public function edit(Service_task $service_task)
    {
       // $state = State::orderBy('name', 'asc')->get();
       if($service_task->state_id>0 && $service_task->district_id>0)
       {
        $user =  DB::select("select * from users where `state_id`='".$service_task->state_id."' AND `state_id`='".$service_task->district_id."'  ");
       }

       if($service_task->user_id>0 )
       {
        $contact_person =  DB::select("select * from contact_person where `user_id`='".$service_task->user_id."'  ");
       }
       //
       $contact_person = Contact_person::all();
       //$service_task = Service_task::all();
       // $contact_person = Contact_person::all();
        $state = State::all();
        $product = Product::all();
        $district = District::all();
        $staff = Staff::all();
        $user = User::all();
        $service_responce =  DB::select("select * from service_responce where `service_task_id`='".$service_task->id."' order by schedule_date desc ");
        $service_visit =  DB::select("select * from service_visit where `service_task_id`='".$service_task->id."'  order by travel_start_time desc");
        $service_part =  DB::select("select * from service_part where `service_task_id`='".$service_task->id."' order by intened_date desc ");
        $product_verify =  DB::select("select * from products where `verified`='Y' ");
        $quote = DB::select("select * from quote where `service_task_id`='".$service_task->id."' ");

        $contact_person =  DB::select("select * from contact_person where `user_id`='".$service_task->user_id."' order by name ASC ");
       
        return view('staff.service_task.edit', compact('quote','product_verify','service_part','service_visit','service_responce','user','staff','service_task','contact_person','state','district','product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service_task $service_task)
    {
        
       $this->validate($request, array(
                    'start_date' => 'required|max:100'
                  
                ));

    
       
        $service_task->service_ref_no = $request->service_ref_no_val;
        $service_task->start_date = $request->start_date;
        $service_task->end_date = $request->end_date;
        $service_task->schedule_date = $request->schedule_date;
        $service_task->service_type = $request->service_type;
        $service_task->other_ref = $request->other_ref;
        $service_task->service_status = $request->service_status;
        $service_task->next_schedule_date = $request->next_schedule_date;
        $service_task->job_type = $request->job_type;
        $service_task->state_id = $request->state_id;
        $service_task->district_id = $request->district_id;
        $service_task->user_id = $request->user_id;
        $service_task->contact_id = $request->contact_id;
        $service_task->product_id = $request->product_id;
        $service_task->cus_eq_status = $request->cus_eq_status;
        $service_task->serial_no = $request->serial_no;
        $service_task->staff_id = $request->staff_id;
        $service_task->contact_no = $request->contact_no_val;
        $service_task->email = $request->email_val;
        $service_task->problem_reported = $request->problem_reported;
        $service_task->prob_desc = $request->prob_desc;
        $service_task->save();

        return redirect()->route('staff.service_task.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service_task  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service_task $service_task)
    {
      
        $service_task->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$service_task->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $service_task = Service_task::find($id);
           
            $service_task->delete();
        }


        return redirect()->route('staff.service_task.index')->with('success', 'Data has been deleted successfully');

    }

    public function AllTaskservice()
    {
       
        $service_responce =  DB::select("select * from service_responce  order by schedule_date desc ");
   
        return view('staff.service_task.AllTaskservice', compact('service_responce'));
     }

    

    
}
