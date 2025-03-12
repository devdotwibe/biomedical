<?php
namespace App\Http\Controllers\staff;


use App\Models\Units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class UnitsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $units = Units::all();
       return view('admin.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        return view('admin.units.create');
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
                'fullname' => 'required|max:100',
                'shortname' => 'required|max:100',
                
            ));


        $units = new Units;
          $units->fullname = $request->fullname;
        $units->shortname = $request->shortname;
        $units->secondary_unit = $request->secondary_unit;
        $units->save();


        return redirect()->route('admin.units.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Units  $units
     * @return \Illuminate\Http\Response
     */
    public function show(Units $units)
    {

    }

   
    public function edit(Units $units)
    {
       
       return view('admin.units.edit', compact('units'));
    }

    
    public function update(Request $request, Units $units)
    {
           $this->validate($request, array(
                    'fullname' => 'required|max:100',
                    'shortname' => 'required|max:100',
                ));
        
     
        $units->fullname = $request->fullname;
        $units->shortname = $request->shortname;
        $units->secondary_unit = $request->secondary_unit;
        $units->save();

        return redirect()->route('admin.units.index')->with('success', 'Data successfully saved.');

    }

    public function destroy(Units $units)
    {
      
        $units->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$units->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $units = Units::find($id);
           
            $units->delete();
        }


        return redirect()->route('admin.units.index')->with('success', 'Data has been deleted successfully');

    }

}
