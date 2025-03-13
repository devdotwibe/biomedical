<?php

namespace App\Http\Controllers\staff;

use App\Designation;

use App\Models\StaffCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Image;
use Storage;

class StaffCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $staff_category = StaffCategory::all();

       return view('staff.staff_category.index', compact('staff_category'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('staff.staff_category.create');
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
            
            ));

            $name_exit = StaffCategory::where('name', $request->name)->count(); 
            if($name_exit>0)
            {
                return redirect()->back()->with('error_message', 'Staff Category already exit.');
            }


        $designation = new StaffCategory;

        $designation->name = $request->name;
    
        $designation->save();


        return redirect()->route('staff_category.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(StaffCategory $staffCategory)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffCategory $staffCategory)
    {
       // $designation = Designation::orderBy('name', 'asc')->get();
        return view('staff.staff_category.edit', compact('staffCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaffCategory $staffCategory)
    {
        


            
                $this->validate($request, array(
                    'name' => 'required|max:100',

                ));
        
        $name_exit = StaffCategory::where('name', $request->name)->where('id', '!=' , $staffCategory->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Designation already exit.');
        }       
        
      
        $staffCategory->name = $request->name;
        
        $staffCategory->save();

        return redirect()->route('staff_category.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffCategory $staffCategory)
    {
      
        $staffCategory->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$staffCategory->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $staffCategory = StaffCategory::find($id);
           
            $staffCategory->delete();
        }


        return redirect()->route('staff_category.index')->with('success', 'Data has been deleted successfully');

    }

}
