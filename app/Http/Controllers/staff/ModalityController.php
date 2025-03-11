<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modality;
use App\User_permission;

class ModalityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staff_id = session('STAFF_ID');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        if(optional($permission)->product_view != 'view')
        {
             return redirect()->route('staff.dashboard');
        }
        
        $modalities = Modality::all();
        return view('staff.modality.index',  compact('modalities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
 
        return view('staff.modality.create');
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

        $slug = str_slug($request->name);
        $modality = new Modality;
        $modality->name = $request->name;
   
        $modality->status = $request->status;
        $modality->save();

        return redirect()->route('staff.modality.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $subcategory)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Modality  $modality
     * @return \Illuminate\Http\Response
     */
    public function edit(Modality $modality)
    {
       
        $parents = Modality::orderBy('name', 'asc')->get();
        return view('staff.modality.edit', compact('modality'), ['parents'=> $parents]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modality  $modality
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Modality $modality)
    {
        $modality = Modality::find($modality->id);
       
                $this->validate($request, array(
                    'name' => 'required|max:100',
                    'status' => 'required'
                ));
     
        $modality->name = $request->name;
        $modality->status = $request->status;
        $modality->save();

        return redirect()->route('staff.modality.index')->with('success', 'Data successfully saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modality $modality
     * @return \Illuminate\Http\Response
     */
    public function destroy(Modality $modality)
    {
        $modality->delete();
        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$modality->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $id) {
            $modality = Modality::find($id);
            $modality->delete();
        }
        return redirect()->route('staff.modality.index')->with('success', 'Data has been deleted successfully');
    }

}
