<?php

namespace App\Http\Controllers\staff;

use App\Models\Subcategory;
use App\Product;
use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $subcategories = Subcategory::all();
       return view('staff.subcategory.index', compact('subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = DB::table('categories')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        foreach ($categories as $cats) {
                $first[$cats->id] = $cats;
        }

        $parents = Subcategory::orderBy('name', 'asc')->get();
        return view('staff.subcategory.create', ['parents'=> $parents,'first'=> $first]);
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
                'category_id' => 'required',
                'status' => 'required'
            ));

        $slug = str_slug($request->name);
        $subcategory = new Subcategory;
        $subcategory->name = $request->name;
        $subcategory->categories_id = $request->category_id;
        $subcategory->slug = $slug;
        $subcategory->status = $request->status;
        $subcategory->save();

        return redirect()->route('subcategory.index')->with('success','Data successfully saved.');
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
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Subcategory $subcategory)
    {
        $categories = DB::table('categories')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        foreach ($categories as $cats) {
                $first[$cats->id] = $cats;
        }
        $parents = Subcategory::orderBy('name', 'asc')->get();
        return view('staff.subcategory.edit', compact('subcategory'), ['parents'=> $parents,'first'=> $first]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subcategory $subcategory)
    {
        $subcategory1 = Subcategory::find($subcategory->id);
        $current = $subcategory1->image_name;
                $this->validate($request, array(
                    'name' => 'required|max:100',
                    'category_id' => 'required',
                    'status' => 'required'
                ));
        $slug = str_slug($request->name);
        $subcategory->name = $request->name;
        $subcategory->slug = $slug;
        $subcategory->categories_id = $request->category_id;
        $subcategory->status = $request->status;
        $subcategory->save();
        return redirect()->route('subcategory.index')->with('success', 'Data successfully saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(subcategory $subcategory)
    {
        $subcategory->delete();
        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$subcategory->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $id) {
            $subcategory = Subcategory::find($id);
            $subcategory->delete();
        }
        return redirect()->route('subcategory.index')->with('success', 'Data has been deleted successfully');
    }

}
