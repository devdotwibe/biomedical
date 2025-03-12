<?php

namespace App\Http\Controllers\staff;

use App\Models\Product_type;
use App\Models\User_permission;
use App\Subcategory;
use App\Product;
use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Image;
use Storage;

class Product_typeController extends Controller
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

       $product_type = Product_type::all();
       return view('staff.product_type.index', compact('product_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
   

        $parents = Product_type::orderBy('name', 'asc')->get();
        return view('staff.product_type.create', ['parents'=> $parents]);
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
        $product_type = new Product_type;
        $product_type->name = $request->name;
   
        $product_type->save();

        return redirect()->route('staff.product_type.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Product_type $subcategory)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Product_type $product_type)
    {
       
        $parents = Product_type::orderBy('name', 'asc')->get();
        return view('staff.product_type.edit', compact('product_type'), ['parents'=> $parents]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product_type $product_type)
    {
        $product_type = Product_type::find($product_type->id);
       
                $this->validate($request, array(
                    'name' => 'required|max:100'
            
                ));
     
        $product_type->name = $request->name;
   
      
        $product_type->save();
        return redirect()->route('staff.product_type.index')->with('success', 'Data successfully saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(product_type $category_type)
    {
        $product_type->delete();
        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$product_type->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $id) {
            $product_type = Product_type::find($id);
            $product_type->delete();
        }
        return redirect()->route('staff.product_type.index')->with('success', 'Data has been deleted successfully');
    }

}
