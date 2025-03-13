<?php

namespace App\Http\Controllers\staff;




use App\Models\Relatedto_category;
use App\Models\Relatedto_subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class Relatedto_subcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $company = Relatedto_subcategory::all();
       
       return view('staff.relatedto_subcategory.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $rel_category = Relatedto_category::all();
        return view('staff.relatedto_subcategory.create', compact('rel_category'));
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
        $name_exit = Relatedto_subcategory::where('name', $request->name)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Related Sub Category already exit.');
        }   



        $slug = str_slug($request->name);

        $slug_e = Relatedto_subcategory::where('slug', $slug)->count();
    
        if($slug_e >  0 ) {
            $slug = $slug.time();
        }


        $relatedto_subcategory = new Relatedto_subcategory;

        $relatedto_subcategory->name = $request->name;
        $relatedto_subcategory->relatedto_category_id = $request->relatedto_category_id;

        
        $relatedto_subcategory->slug = $slug;
    

        $relatedto_subcategory->status = $request->status;
        $relatedto_subcategory->save();


        return redirect()->route('relatedto_subcategory.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Relatedto_subcategory $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customercategory  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Relatedto_subcategory $relatedto_subcategory)
    {
       // $customercategory = Customercategory::orderBy('name', 'asc')->get();
       $rel_category = Relatedto_category::all();
        return view('staff.relatedto_subcategory.edit', compact('relatedto_subcategory','rel_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Relatedto_subcategory $relatedto_subcategory)
    {
        


         
                $this->validate($request, array(
                    'name' => 'required|max:100',

                   
                    'status' => 'required'
                ));
           
        $name_exit = Relatedto_subcategory::where('name', $request->name)->where('id', '!=' , $relatedto_subcategory->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Company name already exit.');
        }
        


        $slug = str_slug($request->name);
        $slug_e = Relatedto_subcategory::where([['slug', $slug]])
                   ->whereNotIn('id', [$relatedto_subcategory->id])
                ->count();
      

        if($slug_e > 0 ) {
            $slug = $slug.$company->id;
        }
        $relatedto_subcategory->name = $request->name;
        $relatedto_subcategory->slug = $slug;
        $relatedto_subcategory->relatedto_category_id = $request->relatedto_category_id;
      
        $relatedto_subcategory->status = $request->status;
        $relatedto_subcategory->save();

        return redirect()->route('relatedto_subcategory.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relatedto_subcategory  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Relatedto_subcategory $relatedto_subcategory)
    {
      
        $relatedto_subcategory->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$relatedto_subcategory->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $relatedto_subcategory = Relatedto_subcategory::find($id);
           
            $relatedto_subcategory->delete();
        }


        return redirect()->route('relatedto_subcategory.index')->with('success', 'Data has been deleted successfully');

    }

}
