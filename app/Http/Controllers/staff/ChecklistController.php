<?php

namespace App\Http\Controllers\staff;


use App\Models\Checklist;
use App\Models\Relatedto_category;
use App\Models\Relatedto_subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class ChecklistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $checklist = Checklist::all();
       return view('staff.checklist.index', compact('checklist'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $relatedto_category = Relatedto_category::all();
        $relatedto_subcategory = Relatedto_subcategory::all();
        return view('staff.checklist.create',compact('relatedto_category','relatedto_subcategory'));
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
        $name_exit = Checklist::where('name', $request->name)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Customer Category already exit.');
        }   



 


        $company = new Checklist;

        $company->name = $request->name;

        $company->related_category_id = $request->related_category_id;
        $company->related_subcategory_id = $request->related_subcategory_id;
      
        
        

        $company->status = $request->status;
        $company->save();


        return redirect()->route('checklist.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Checklist $checklist)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customercategory  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Checklist $checklist)
    {
       // $customercategory = Checklist::orderBy('name', 'asc')->get();
       $relatedto_category = Relatedto_category::all();
       $relatedto_subcategory = Relatedto_subcategory::all();
        return view('staff.checklist.edit', compact('checklist','relatedto_category','relatedto_subcategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Checklist $checklist)
    {
        
                $this->validate($request, array(
                    'name' => 'required|max:100',

                    'status' => 'required'
                ));
           
        $name_exit = Checklist::where('name', $request->name)->where('id', '!=' , $checklist->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Company name already exit.');
        }
        


      

   
        $checklist->name = $request->name;
        $checklist->related_category_id = $request->related_category_id;
        $checklist->related_subcategory_id = $request->related_subcategory_id;
      
        $checklist->status = $request->status;
        $checklist->save();

        return redirect()->route('checklist.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relatedto_category  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Checklist $checklist)
    {
      
        $checklist->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$checklist->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $checklist = Checklist::find($id);
           
            $checklist->delete();
        }


        return redirect()->route('checklist.index')->with('success', 'Data has been deleted successfully');

    }

}
