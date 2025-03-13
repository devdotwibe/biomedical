<?php

namespace App\Http\Controllers\staff;

use App\Models\District;
use App\Product;
use App\Banner;
use App\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\State;
use Image;
use Storage;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $company = District::all();
       return view('staff.district.index', compact('company'));
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
        return view('staff.district.create', compact('country','state'));
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
            $name_exit = District::where('name', $request->name)->count(); 
            if($name_exit>0)
            {
                return redirect()->back()->with('error_message', 'District name already exit.');
            }    

        $company = new District;

        $company->name = $request->name;
        $company->country_id = $request->country_id;
        $company->state_id = $request->state_id;

     
    

        $company->save();


        return redirect()->route('district.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(District $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\District  $district
     * @return \Illuminate\Http\Response
     */
    public function edit(District $district)
    {
       // $state = State::orderBy('name', 'asc')->get();
       $country = Country::all();
       $state = State::all();
        return view('staff.district.edit', compact('district','country','state'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, District $district)
    {
        
       $this->validate($request, array(
                    'name' => 'required|max:100'
                  
                ));
        
        $name_exit = District::where('name', $request->name)->where('id', '!=' , $district->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'District already exit.');
        }
       
        $district->name = $request->name;
        $district->country_id = $request->country_id;
        $district->state_id = $request->state_id;
        $district->save();

        return redirect()->route('district.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\State  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(District $district)
    {
      
        $district->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$district->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $district = District::find($id);
           
            $district->delete();
        }


        return redirect()->route('district.index')->with('success', 'Data has been deleted successfully');

    }

}
