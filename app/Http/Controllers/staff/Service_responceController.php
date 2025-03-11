<?php

namespace App\Http\Controllers\staff;

use App\Service_responce;
use App\Contact_person;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class Service_responceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $service_responce = Service_responce::all();
       return view('staff.service_responce.index', compact('service_responce'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $service_responce = Service_responce::all();
        $contact_person = Contact_person::all();

        return view('staff.service_responce.create', compact('service_responce','contact_person'));
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
                'response_date' => 'required'
             
             
            ));

        $service_responce = new Service_responce;

        $service_responce->contact_id = $request->contact_id;
        $service_responce->response_date = $request->response_date;
        $service_responce->response_time = $request->response_time;
        $service_responce->responce = $request->responce;
        $service_responce->contact_no = $request->contact_no;
        $service_responce->contact_id = $request->contact_id;
        
        $service_responce->status = $request->status;
        $service_responce->action_plan = $request->action_plan;
        $service_responce->planned_date = $request->planned_date;
        $service_responce->planned_time = $request->planned_time;
        $service_responce->save();


        return redirect()->route('staff.service_responce.index')->with('success','Data successfully saved.');
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
    public function edit(Service_responce $service_responce)
    {
       // $state = State::orderBy('name', 'asc')->get();
     
       $contact_person = Contact_person::all();
        return view('staff.service_responce.edit', compact('service_responce','contact_person'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service_responce $service_responce)
    {
        
       $this->validate($request, array(
                    'response_date' => 'required|max:100'
                  
                ));

    
       
        $service_responce->contact_id = $request->contact_id;
        $service_responce->response_date = $request->response_date;
        $service_responce->response_time = $request->response_time;
        $service_responce->responce = $request->responce;
        $service_responce->contact_no = $request->contact_no;
        $service_responce->contact_id = $request->contact_id;
        
        $service_responce->status = $request->status;
        $service_responce->action_plan = $request->action_plan;
        $service_responce->planned_date = $request->planned_date;
        $service_responce->planned_time = $request->planned_time;
        $service_responce->save();

        return redirect()->route('staff.service_responce.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service_responce  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service_responce $service_responce)
    {
      
        $service_responce->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$service_responce->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $service_responce = Service_responce::find($id);
           
            $service_responce->delete();
        }


        return redirect()->route('staff.service_responce.index')->with('success', 'Data has been deleted successfully');

    }

}
