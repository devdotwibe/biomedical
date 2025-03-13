<?php

namespace App\Http\Controllers\staff;


use App\Contact_person;

use App\Models\Hosdesignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class HosdesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $company = Hosdesignation::all();
       return view('staff.hosdesignation.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('staff.hosdesignation.create');
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
                'name' => 'required|max:100',
             
                'status' => 'required'
            ));

        $name_exit = Hosdesignation::where('name', $request->name)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Designation already exit.');
        }    


        $slug = str_slug($request->name);

        $slug_e = Hosdesignation::where('slug', $slug)->count();
    
        if($slug_e >  0 ) {
            $slug = $slug.time();
        }


        $hosdesignation = new Hosdesignation;

        $hosdesignation->name = $request->name;
        $hosdesignation->slug = $slug;
    

        $hosdesignation->status = $request->status;
        $hosdesignation->save();


        return redirect()->route('hosdesignation.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Hosdesignation $hosdesignation)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Hosdesignation $hosdesignation)
    {
       // $company = Company::orderBy('name', 'asc')->get();
        return view('staff.hosdesignation.edit', compact('hosdesignation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hosdesignation  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hosdesignation $hosdesignation)
    {
        


         
                $this->validate($request, array(
                    'name' => 'required|max:100',

                   
                    'status' => 'required'
                ));
           
        $name_exit = Hosdesignation::where('name', $request->name)->where('id', '!=' , $hosdesignation->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Designation already exit.');
        }        
        


        $slug = str_slug($request->name);
        $slug_e = Hosdesignation::where([['slug', $slug]])
                   ->whereNotIn('id', [$hosdesignation->id])
                ->count();
      

        if($slug_e > 0 ) {
            $slug = $slug.$company->id;
        }
        $hosdesignation->name = $request->name;
        $hosdesignation->slug = $slug;
      
        $hosdesignation->status = $request->status;
        $hosdesignation->save();

        return redirect()->route('hosdesignation.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hosdesignation $hosdesignation)
    {
      
        $hosdesignation->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$hosdesignation->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $hosdesignation = Hosdesignation::find($id);
           
            $hosdesignation->delete();
        }


        return redirect()->route('hosdesignation.index')->with('success', 'Data has been deleted successfully');

    }

}
