<?php

namespace App\Http\Controllers\staff;



use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\State;
use Image;
use Storage;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $company = State::all();
       return view('staff.state.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $country = Country::all();
        return view('staff.state.create', compact('country'));
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
                'name' => 'required|max:100'
             
             
            ));

        $name_exit = State::where('name', $request->name)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'State already exit.');
        }     


      

        $company = new State;

        $company->name = $request->name;
        $company->country_id = $request->country_id;
     
    

        $company->save();


        return redirect()->route('state.index')->with('success','Data successfully saved.');
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
    public function edit(State $state)
    {
       // $state = State::orderBy('name', 'asc')->get();
       $country = Country::all();
        return view('staff.state.edit', compact('state','country'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, State $state)
    {
        
       $this->validate($request, array(
                    'name' => 'required|max:100'
                  
                ));

    $name_exit = State::where('name', $request->name)->where('id', '!=' , $state->id)->count(); 
    if($name_exit>0)
    {
        return redirect()->back()->with('error_message', 'State already exit.');
    }   
       
        $state->name = $request->name;
        $state->country_id = $request->country_id;
        $state->save();

        return redirect()->route('state.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\State  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(State $state)
    {
      
        $state->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$state->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $state = State::find($id);
           
            $state->delete();
        }


        return redirect()->route('state.index')->with('success', 'Data has been deleted successfully');

    }

}
