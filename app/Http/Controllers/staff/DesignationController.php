<?php

namespace App\Http\Controllers\staff;


use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $designation = Designation::all();
       return view('staff.designation.index', compact('designation'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('staff.designation.create');
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

            $name_exit = Designation::where('name', $request->name)->count(); 
            if($name_exit>0)
            {
                return redirect()->back()->with('error_message', 'Designation already exit.');
            }




        $slug = str_slug($request->name);

        $slug_e = Designation::where('slug', $slug)->count();
    
        if($slug_e >  0 ) {
            $slug = $slug.time();
        }


        $designation = new Designation;

        $designation->name = $request->name;
        $designation->slug = $slug;
    

        $designation->status = $request->status;
        $designation->save();


        return redirect()->route('designation.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Designation $designation)
    {
       // $designation = Designation::orderBy('name', 'asc')->get();
        return view('staff.designation.edit', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Designation $designation)
    {
        


            
                $this->validate($request, array(
                    'name' => 'required|max:100',

                    'status' => 'required'
                ));
        
        $name_exit = Designation::where('name', $request->name)->where('id', '!=' , $designation->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Designation already exit.');
        }       
        


        $slug = str_slug($request->name);
        $slug_e = Designation::where([['slug', $slug]])
                   ->whereNotIn('id', [$designation->id])
                ->count();
      

        if($slug_e > 0 ) {
            $slug = $slug.$brand->id;
        }
        $designation->name = $request->name;
        $designation->slug = $slug;
      
        $designation->status = $request->status;
        $designation->save();

        return redirect()->route('designation.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Designation $designation)
    {
      
        $designation->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$designation->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $designation = Designation::find($id);
           
            $designation->delete();
        }


        return redirect()->route('designation.index')->with('success', 'Data has been deleted successfully');

    }

}
