<?php

namespace App\Http\Controllers\staff;

use App\Subcategory;
use App\Product;
use App\Category_type;
use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\User_permission;
use Image;
use Storage;

class Category_typeController extends Controller
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

       $category_type = Category_type::all();
       return view('staff.category_type.index', compact('category_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
   

        $parents = Category_type::orderBy('name', 'asc')->get();
        return view('staff.category_type.create', ['parents'=> $parents]);
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
        $category_type = new Category_type;
        $category_type->name = $request->name;
   
        $category_type->status = $request->status;
        $category_type->save();

        return redirect()->route('staff.category_type.index')->with('success','Data successfully saved.');
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
    public function edit(Category_type $category_type)
    {
       
        $parents = Category_type::orderBy('name', 'asc')->get();
        return view('staff.category_type.edit', compact('category_type'), ['parents'=> $parents]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category_type $category_type)
    {
        $category_type = Category_type::find($category_type->id);
       
                $this->validate($request, array(
                    'name' => 'required|max:100',
                  
                    'status' => 'required'
                ));
     
        $category_type->name = $request->name;
   
       
        $category_type->status = $request->status;
        $category_type->save();
        return redirect()->route('staff.category_type.index')->with('success', 'Data successfully saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category_type $category_type)
    {
        $category_type->delete();
        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$category_type->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $id) {
            $category_type = Category_type::find($id);
            $category_type->delete();
        }
        return redirect()->route('staff.category_type.index')->with('success', 'Data has been deleted successfully');
    }

}
