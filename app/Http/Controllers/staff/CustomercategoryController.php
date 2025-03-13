<?php

namespace App\Http\Controllers\staff;



use App\Models\Customercategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class CustomercategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $company = Customercategory::all();
       return view('staff.customercategory.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.customercategory.create');
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
        $name_exit = Customercategory::where('name', $request->name)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Customer Category already exit.');
        }   



        $slug = str_slug($request->name);

        $slug_e = Customercategory::where('slug', $slug)->count();
    
        if($slug_e >  0 ) {
            $slug = $slug.time();
        }


        $company = new Customercategory;

        $company->name = $request->name;
        $company->slug = $slug;
    

        $company->status = $request->status;
        $company->save();


        return redirect()->route('admin.customercategory.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Customercategory $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customercategory  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Customercategory $customercategory)
    {
       // $customercategory = Customercategory::orderBy('name', 'asc')->get();
        return view('admin.customercategory.edit', compact('customercategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customercategory $customercategory)
    {
        


         
                $this->validate($request, array(
                    'name' => 'required|max:100',

                   
                    'status' => 'required'
                ));
           
        $name_exit = Customercategory::where('name', $request->name)->where('id', '!=' , $customercategory->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Company name already exit.');
        }
        


        $slug = str_slug($request->name);
        $slug_e = Customercategory::where([['slug', $slug]])
                   ->whereNotIn('id', [$customercategory->id])
                ->count();
      

        if($slug_e > 0 ) {
            $slug = $slug.$company->id;
        }
        $customercategory->name = $request->name;
        $customercategory->slug = $slug;
      
        $customercategory->status = $request->status;
        $customercategory->save();

        return redirect()->route('admin.customercategory.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customercategory  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customercategory $customercategory)
    {
      
        $customercategory->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$customercategory->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $customercategory = Customercategory::find($id);
           
            $customercategory->delete();
        }


        return redirect()->route('admin.customercategory.index')->with('success', 'Data has been deleted successfully');

    }

}
