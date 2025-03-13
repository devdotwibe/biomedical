<?php

namespace App\Http\Controllers\staff;



use App\Models\Hosdeparment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class HosdeparmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $company = Hosdeparment::all();
       return view('staff.hosdeparment.index', compact('company'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.hosdeparment.create');
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

        $name_exit = Hosdeparment::where('name', $request->name)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Department already exit.');
        }


        $slug = str_slug($request->name);

        $slug_e = Hosdeparment::where('slug', $slug)->count();
    
        if($slug_e >  0 ) {
            $slug = $slug.time();
        }


        $company = new Hosdeparment;

        $company->name = $request->name;
        $company->slug = $slug;
    

        $company->status = $request->status;
        $company->save();


        return redirect()->route('admin.hosdeparment.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Hosdeparment $company)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Hosdeparment  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Hosdeparment $hosdeparment)
    {
       // $hosdeparment = Hosdeparment::orderBy('name', 'asc')->get();
        return view('admin.hosdeparment.edit', compact('hosdeparment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Company  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Hosdeparment $hosdeparment)
    {
        


         
                $this->validate($request, array(
                    'name' => 'required|max:100',

                   
                    'status' => 'required'
                ));
           
        $name_exit = Hosdeparment::where('name', $request->name)->where('id', '!=' , $hosdeparment->id)->count(); 
        if($name_exit>0)
        {
            return redirect()->back()->with('error_message', 'Department already exit.');
        }
        


        $slug = str_slug($request->name);
        $slug_e = Hosdeparment::where([['slug', $slug]])
                   ->whereNotIn('id', [$hosdeparment->id])
                ->count();
      

        if($slug_e > 0 ) {
            $slug = $slug.$company->id;
        }
        $hosdeparment->name = $request->name;
        $hosdeparment->slug = $slug;
      
        $hosdeparment->status = $request->status;
        $hosdeparment->save();

        return redirect()->route('admin.hosdeparment.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Hosdeparment  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hosdeparment $hosdeparment)
    {
      
        $hosdeparment->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$hosdeparment->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $hosdeparment = Hosdeparment::find($id);
           
            $hosdeparment->delete();
        }


        return redirect()->route('admin.hosdeparment.index')->with('success', 'Data has been deleted successfully');

    }

}
