<?php

namespace App\Http\Controllers\staff;



use App\Models\Relatedto_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class Relatedto_categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $company = Relatedto_category::all();
       return view('staff.relatedto_category.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.relatedto_category.create');
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
        $name_exit = Relatedto_category::where('name', $request->name)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Customer Category already exit.');
        }   



        $slug = str_slug($request->name);

        $slug_e = Relatedto_category::where('slug', $slug)->count();
    
        if($slug_e >  0 ) {
            $slug = $slug.time();
        }


        $company = new Relatedto_category;

        $company->name = $request->name;
        $company->slug = $slug;
    

        $company->status = $request->status;
        $company->save();


        return redirect()->route('admin.relatedto_category.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Relatedto_category $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customercategory  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Relatedto_category $relatedto_category)
    {
       // $customercategory = Relatedto_category::orderBy('name', 'asc')->get();
        return view('admin.relatedto_category.edit', compact('relatedto_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Relatedto_category $relatedto_category)
    {
        


         
                $this->validate($request, array(
                    'name' => 'required|max:100',

                   
                    'status' => 'required'
                ));
           
        $name_exit = Relatedto_category::where('name', $request->name)->where('id', '!=' , $relatedto_category->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Company name already exit.');
        }
        


        $slug = str_slug($request->name);
        $slug_e = Relatedto_category::where([['slug', $slug]])
                   ->whereNotIn('id', [$relatedto_category->id])
                ->count();
      

        if($slug_e > 0 ) {
            $slug = $slug.$company->id;
        }
        $relatedto_category->name = $request->name;
        $relatedto_category->slug = $slug;
      
        $relatedto_category->status = $request->status;
        $relatedto_category->save();

        return redirect()->route('admin.relatedto_category.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Relatedto_category  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Relatedto_category $relatedto_category)
    {
      
        $relatedto_category->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$relatedto_category->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $relatedto_category = Relatedto_category::find($id);
           
            $relatedto_category->delete();
        }


        return redirect()->route('admin.relatedto_category.index')->with('success', 'Data has been deleted successfully');

    }

}
