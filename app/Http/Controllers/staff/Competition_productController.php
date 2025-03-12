<?php

namespace App\Http\Controllers\staff;

use App\Models\Category_type;
use App\Models\Competition_product;
use App\Models\Product_type;
use App\Models\User_permission;
use App\Subcategory;
use App\Product;
use App\Category;
use App\Brand;
use App\Banner;
use App\Product_option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use Image;
use Storage;

class Competition_productController extends Controller
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

       $competition_product = Competition_product::all();
       return view('staff.competition_product.index', compact('competition_product'));
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

      

        $product_option = DB::table('product_option')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

        $catgory_type = Category_type::all();
        $product_type = Product_type::all();
        $brand = DB::table('brand')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        
        $first      = array();
        $children   = array();
        foreach ($categories as $cats) {
                $first[$cats->id] = $cats;
        }
        

        $parents = Competition_product::orderBy('name', 'asc')->get();
        return view('staff.competition_product.create', ['brand'=> $brand,'parents'=> $parents,'first'=> $first,'product_option'=> $product_option,'catgory_type'=> $catgory_type,'product_type'=> $product_type]);
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
        $competition_product = new Competition_product;
        $competition_product->name = $request->name;
        $competition_product->category_type_id = $request->category_type_id;
        $competition_product->category_id = $request->category_id;
        $competition_product->brand_id = $request->brand_id;
        $competition_product->product_type_id = $request->product_type_id;
   
        $competition_product->save();

        return redirect()->route('staff.competition_product.index')->with('success','Data successfully saved.');
    }

   
    public function show(Competition_product $competition_product)
    {

    }

  
    public function edit(Competition_product $competition_product)
    {

        $categories = DB::table('categories')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

      

        $product_option = DB::table('product_option')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

        $catgory_type = Category_type::all();
        $product_type = Product_type::all();
        $brand = DB::table('brand')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        
        $first      = array();
        $children   = array();
        foreach ($categories as $cats) {
                $first[$cats->id] = $cats;
        }

       
        $parents = Competition_product::orderBy('name', 'asc')->get();
        return view('staff.competition_product.edit', compact('competition_product'), ['brand'=> $brand,'parents'=> $parents,'first'=> $first,'product_option'=> $product_option,'catgory_type'=> $catgory_type,'product_type'=> $product_type]);
    }

    public function update(Request $request, Competition_product $competition_product)
    {
        $competition_product = Competition_product::find($competition_product->id);
       
                $this->validate($request, array(
                    'name' => 'required|max:100'
            
                ));
     
        $competition_product->name = $request->name;
        $competition_product->category_type_id = $request->category_type_id;
        $competition_product->category_id = $request->category_id;
        $competition_product->brand_id = $request->brand_id;
        $competition_product->product_type_id = $request->product_type_id;
   
        $competition_product->save();
        return redirect()->route('staff.competition_product.index')->with('success', 'Data successfully saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Competition_product $competition_product)
    {
        $competition_product->delete();
        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$competition_product->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;
        foreach($ids as $id) {
            $competition_product = Competition_product::find($id);
            $competition_product->delete();
        }
        return redirect()->route('staff.competition_product.index')->with('success', 'Data has been deleted successfully');
    }

}
