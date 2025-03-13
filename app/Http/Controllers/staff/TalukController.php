<?php

namespace App\Http\Controllers\staff;
use App\Models\Country;
use App\Models\District;
use App\Models\Taluk;
use App\Product;
use App\Banner;
use App\Category;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\State;
use Image;
use Storage;

class TalukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $company = Taluk::all();
       return view('staff.taluk.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $country = Country::all();
        $state = State::all();
        $district = District::all();
        return view('staff.taluk.create', compact('country','state','district'));
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

            $name_exit = Taluk::where('name', $request->name)->count(); 
            if($name_exit>0)
            {
                return redirect()->back()->with('error_message', 'Taluk already exit.');
            }

        $company = new Taluk;

        $company->name = $request->name;
        $company->country_id = $request->country_id;
        $company->state_id = $request->state_id;
        $company->district_id = $request->district_id;

     
    

        $company->save();


        return redirect()->route('taluk.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Taluk $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Taluk  $district
     * @return \Illuminate\Http\Response
     */
    public function edit(Taluk $taluk)
    {
       // $state = State::orderBy('name', 'asc')->get();
       $country = Country::all();
       $state = State::all();
       $district = District::all();
        return view('staff.taluk.edit', compact('taluk','country','state','district'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Taluk $taluk)
    {
        
       $this->validate($request, array(
                    'name' => 'required|max:100'
                  
                ));
        
        $name_exit = Taluk::where('name', $request->name)->where('id', '!=' , $taluk->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Taluk already exit.');
        }
       
        $taluk->name = $request->name;
        $taluk->country_id = $request->country_id;
        $taluk->state_id = $request->state_id;
        $taluk->district_id = $request->district_id;
        $taluk->save();

        return redirect()->route('taluk.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\State  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Taluk $taluk)
    {
      
        $taluk->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$taluk->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $taluk = Taluk::find($id);
           
            $taluk->delete();
        }


        return redirect()->route('taluk.index')->with('success', 'Data has been deleted successfully');

    }

}
