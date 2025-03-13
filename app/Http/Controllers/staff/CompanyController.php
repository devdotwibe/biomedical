<?php

namespace App\Http\Controllers\staff;

use App\Staff;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Image;
use Storage;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $company = Company::all();
       return view('staff.company.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('staff.company.create');
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
            $name_exit = Company::where('name', $request->name)->count(); 
            if($name_exit>0)
            {
                return redirect()->back()->with('error_message', 'Company name already exit.');
            }


            

        $slug = str_slug($request->name);

        $slug_e = Company::where('slug', $slug)->count();
    
        if($slug_e >  0 ) {
            $slug = $slug.time();
        }


        $company = new Company;

        $company->name = $request->name;
        $company->slug = $slug;
    

        $company->status = $request->status;
        $company->save();


        return redirect()->route('company.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
       // $company = Company::orderBy('name', 'asc')->get();
        return view('staff.company.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        


         
                $this->validate($request, array(
                    'name' => 'required|max:100',
                    'status' => 'required'
                ));
           
        $name_exit = Company::where('name', $request->name)->where('id', '!=' , $company->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Company name already exit.');
        }

        $slug = str_slug($request->name);
        $slug_e = Company::where([['slug', $slug]])
                   ->whereNotIn('id', [$company->id])
                ->count();
      

        if($slug_e > 0 ) {
            $slug = $slug.$company->id;
        }
        $company->name = $request->name;
        $company->slug = $slug;
      
        $company->status = $request->status;
        $company->save();

        return redirect()->route('staff.company.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Company $company)
    {
      
        $company->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$company->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $company = Company::find($id);
           
            $company->delete();
        }


        return redirect()->route('company.index')->with('success', 'Data has been deleted successfully');

    }

}
