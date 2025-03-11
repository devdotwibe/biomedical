<?php

namespace App\Http\Controllers\staff;

use App\Product;
use App\Category;
use App\Brand;
use App\Company;
use App\Product_image;
use App\Product_option;
use App\Category_type;
use App\Product_type;
use App\Competition_product;

use Illuminate\Http\Request;
use App\Modality;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MyController;

use App\Exports\UsersExport;

use App\Imports\UsersImport;
use App\User_permission;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;

use Validator;
use Image;
use Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
        

    //     $staff_id = session('STAFF_ID');
      
    //     $user_permission =  DB::select("SELECT * FROM `user_permission` WHERE FIND_IN_SET('Product',section) AND staff_id='".$staff_id."'" );
       
    //     if($staff_id=="29" || $staff_id=="32"  || $staff_id=="4" || count($user_permission)>0)
    //     {
       
    //         $products = Product::orderBy('id','desc')->get();
    //         return view('staff.products.index', compact('products'));
    //     }else{
    //         return view('staff.dashboard');
    //     }
    // }


    public function modality_change(Request $request)
    {
        $value = $request->value;

        $modality = Modality::where('brand_id',$value)->pluck('id');

        return response(['mod'=>$modality]);
    }

    public function index(Request $request)
    {
        $staff_id = session('STAFF_ID');
dd("fsdfdf");
        $permission = User_permission::where('staff_id', $staff_id)->first();

        if(optional($permission)->product_view != 'view')
        {
            return redirect()->route('staff.dashboard');
        }

        if($request->ajax()){
           

            $data = Product::orderBy('name', 'asc')->get();


            return Datatables::of($data)

            ->addColumn('name',function($data){
                return $data->name;
                })

            ->addColumn('category_name',function($data){
                return $data->category_name;
                })

                ->addColumn('category_type',function($data){
                    if($data->category_type_id>0)
                    {
    
                        $cat_det=  Category_type::where('id',$data->category_type_id)->get();
                            if($cat_det)
                            {
                                return  $cat_det[0]->name;
                            }
                            else{
                                return '';
                            }
                    }
                    else{
                        return '';
                    }
                 })
          
           
          ->addColumn('modality',function($data){
                // if($data->modality>0)
                // {

                //     $modality_det=  Modality::where('id',$data->modality)->get();
                //         if($modality_det)
                //         {
                //             return  $modality_det[0]->name;
                //         }
                //         else{
                //             return '';
                //         }
                // }
                // else{
                //     return '';
                // }
                return '';
             })
          
             ->addColumn('brand_name',function($data){
                if($data->brand_id>0)
                {

                    $brand_det=  Brand::where('id',$data->brand_id)->get();
                        if($brand_det)
                        {
                            return  $brand_det[0]->name;
                        }
                        else{
                            return '';
                        }
                }
                else{
                    return '';
                }
             })
            ->addColumn('company_name',function($data){
                return $data->company_name;
                }) 
            ->addColumn('unit',function($data){
                return $data->unit;
                })
            ->addColumn('max_sale_amount',function($data){
                return $data->max_sale_amount;
                })
            ->addColumn('min_sale_amount',function($data){
                return $data->min_sale_amount;
                })
            ->addColumn('tax_percentage',function($data){
                return $data->tax_percentage;
                }) 
            ->addColumn('hsn_code',function($data){
                return $data->hsn_code;
                })   
            ->addColumn('action',function($data) use($staff_id){
                
                $button = "";
                $permission = User_permission::where('staff_id', $staff_id)->first();

                if(optional($permission)->product_edit == 'edit')
                {
                    $button .='
                    <a class="btn btn-primary btn-xs" href="'.route('staff.products.edit',"$data->id").'" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>';
                    
                }

                if(optional($permission)->product_delete == 'delete')
                {
                    $button .='<a class="delete-btn   deleteItem" href="'.route('staff.products.destroy',$data->id) .'" id="deleteItem'.$data->id.'" data-tr="tr_'.$data->id.'" title="Delete"><img src="'. asset('images/delete.svg') .'"></a>';
                }
              
               return $button;

            })
        

           
            ->rawColumns(['name','category_name','category_type','modality','action','name','min_sale_amount','max_sale_amount','unit','company_name','brand_name'])->addIndexColumn()->make(true);
        }

    	/*$oppertunity 		= Oppertunity::with('oppertunityOppertunityProduct')->orderBy('updated_at','desc')->paginate(100);
        return view('admin.oppertunity.index',array('oppertunity'=>$oppertunity));*/
        return view('staff.products.index');
    }


    public function importExportView()
    {
        return view('staff.products.importExportView');
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $staff_id = session('STAFF_ID');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        if(optional($permission)->product_create != 'create')
        {
             return redirect()->route('staff.dashboard');
        }

        $categories = DB::table('categories')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

        $modalities = DB::table('modality')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

        $subcategories = DB::table('subcategories')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

        $product_option = DB::table('product_option')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

        $brand = DB::table('brand')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        $first      = array();

        $modality = DB::table('modality')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();
        
        $children   = array();
        foreach ($categories as $cats) {
                $first[$cats->id] = $cats;
        }
        $company = Company::all();
        $related_products = Product::orderBy('name', 'asc')->get();
        $catgory_type_sort =  DB::select("select product_type.id,product_type.name from product_type as product_type INNER JOIN   products as products ON product_type.id=products.product_type_id group by product_type.id");
        $catgory_type_compi =  DB::select("select product_type.id,product_type.name from product_type as product_type INNER JOIN   competition_product as comp ON product_type.id=comp.product_type_id group by product_type.id");
        $parents = Product::where('parent_id', 0)->orderBy('name', 'asc')->get();
        $catgory_type = Category_type::all();
        $product_type = Product_type::all();
        $competition_product = Competition_product::all();
        $products = Product::all();
        //print_r($children);
        return view('staff.products.create', compact('catgory_type_compi','catgory_type_sort','competition_product','company','product_type','catgory_type','product_option','related_products','first', 'children', 'parents','subcategories','brand','products','modality'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //print_r($request->related_products);
        //exit;
        $this->validate($request, array(
            'category_id' => 'required',
            'brand_id' => 'required',
            'company_id' => 'required',
            'name' => 'required',
            'status' => 'required'
        ));

        //echo 'select * from products where `name`="'.trim($request->name).'" AND `brand_id`="'.trim($request->brand_id).'" AND `item_code`="'.trim($request->item_code).'"';exit;
        // $product_exits = DB::select('select * from products where `name`="'.trim($request->name).'" AND `brand_id`="'.trim($request->brand_id).'" AND `item_code`="'.trim($request->item_code).'"');   
        // if(count($product_exits)==0)
        if(Product::where('name',$request->name)->where('brand_id',$request->brand_id)->where('item_code',$request->item_code)->count()==0){
        

        if($request->image_name!='')
        {
            $imageName = time().$request->image_name->getClientOriginalName();
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path =  storage_path();
        $img_path = $request->image_name->storeAs('public/products', $imageName);
       /* $path = $path.'/app/'.$img_path;
        chmod($path, 0777);*/
        }

        if($request->image_name1!='')
        {
            $imageName1 = time().$request->image_name1->getClientOriginalName();
        $imageName1 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);
        $path =  storage_path();
        $img_path = $request->image_name1->storeAs('public/products', $imageName1);
      /*  $path = $path.'/app/'.$img_path;
        chmod($path, 0777);*/
        }
        

        $slug = ($request->slug != '') ? str_slug($request->slug): str_slug($request->name);
        $slug_e = Product::where([['slug', $slug]])->count();
        $slug_e1 = Category::where([['slug', $slug]])->count();
        if($slug_e > 0 || $slug_e1 > 0) {
            $slug = $slug.time();
        }

        $product = new Product;
       

        
        $brand = Brand::find($request->brand_id);

        $catnames=array();
         foreach($request->category_id as $val)
         {
            $cat = Category::find($val);
            $catnames[]=$cat->name;
         }
         $product->category_id = implode(',',$request->category_id);
         $product->category_name =implode(',',$catnames);
         if($request->related_products!='')
         {
            $product->related_products = implode(',',$request->related_products);
         }
         if($request->competition_product!='')
         {
            $product->competition_product = implode(',',$request->competition_product);
         }
         if($request->op_pdt!='')
         {
            $product->optional_products = implode(',',$request->op_pdt);
         }
         
         
         $product->option1 = $request->option1;
         $product->option2 = $request->option2;
         
         
         
         
        $product->brand_name=$brand->name;
        
        $product->parent_id = isset($request->parent_id) ? $request->parent_id: 0;
        $product->name = $request->name;
        $product->part_no=$request->part_no;
        $product->title = $request->title;

        $product->category_type_id = $request->category_type_id;
      
        $product->brand_id = $request->brand_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->modality  = $request->modality;

        $product->short_title = $request->short_title;
        $product->short_description = $request->short_description;
        $product->item_code = $request->item_code;
        $product->unit = $request->unit;
        $product->quantity = $request->quantity;
        $product->warrenty = $request->warrenty;
        $product->payment = $request->payment;
        $product->validity = $request->validity;
        $product->company_id = $request->company_id;
        $product->product_type_id = $request->product_type_id;
        
        $product->hsn_code = $request->hsn_code;
        $product->tax_percentage = $request->tax_percentage;
        $product->unit_price = $request->unit_price;
        $product->max_sale_amount = $request->max_sale;
        $product->min_sale_amount = $request->min_sale;

        if($request->company_id>0)
        {
            $company = Company::find($request->company_id);
            $product->company_name=$company->name;
        }
        
        
        
        //$product->parent_id = isset($request->parent_id) ? $request->parent_id: 0;
        $product->slug  = $slug;
        $product->description = $request->description;
        $product->image_name = isset($imageName) ? $imageName: '';

        $product->image_name1 = isset($imageName1) ? $imageName1: '';

        $product->status        = $request->status;
        $product->save();


       /* if($request->image_name!='')
        {
        $image = $request->file('image_name');
        //$input['imagename'] = time().'.'.$image->getClientOriginalExtension();
        $destinationPath = storage_path().('/app/public/products/thumbnail');
        $img = Image::make($image->getRealPath());
        $img->resize(100, 100, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationPath.'/'.$imageName);
        }*/
        return redirect()->route('staff.products.index')->with('success','Data successfully saved.');
    }
    else{
        return redirect()->back()->with('error_message', 'Product already exit.');
    // return redirect()->route('staff.products.index')->with('warning','Product already exit.');
    }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {

        $staff_id = session('STAFF_ID');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        if(optional($permission)->product_edit != 'edit')
        {
             return redirect()->route('staff.dashboard');
        }

        DB::enableQueryLog();
        //echo $product->id;
        $categories = DB::table('categories')
        ->orderBy('name', 'asc')
        ->get();
         $subcategories = DB::table('subcategories')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

        $brand = DB::table('brand')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

        $modality = DB::table('modality')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();

        $product_option = DB::table('product_option')
        ->orderBy('name', 'asc')
        ->select('name','id')
        ->get();


        $first      = array();
        $children   = array();
        foreach ($categories as $cats) {
                $first[$cats->id] = $cats;
        }

        $product_image = DB::table('product_image')
         ->where('product_id', [$product->id])
        ->get();

        $related_products = Product::whereNotIn('id', [$product->id])->orderBy('name', 'asc')->get();
        $competition_product = Competition_product::all();
        $parents = Product::where('parent_id', 0)->orderBy('name', 'asc')->get();
        $catgory_type = Category_type::all();
        $product_type = Product_type::all();
        $catgory_type_sort =  DB::select("select product_type.id,product_type.name from product_type as product_type INNER JOIN   products as products ON product_type.id=products.product_type_id group by product_type.id");
        $catgory_type_compi =  DB::select("select product_type.id,product_type.name from product_type as product_type INNER JOIN   competition_product as comp ON product_type.id=comp.product_type_id group by product_type.id");
       
         //$query = DB::getQueryLog();
        $company = Company::all();
        $products = Product::all();
        return view('staff.products.edit', compact('catgory_type_compi','catgory_type_sort','competition_product','product_type','product_option','catgory_type','company','product', 'related_products', 'first', 'children', 'parents','product_image','subcategories','brand','products','modality'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $staff_id = session('STAFF_ID');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        if(optional($permission)->product_edit != 'edit')
        {
             return redirect()->route('staff.dashboard');
        }
    
        $current_img        = $product->image_name;
        $current_img1        = $product->image_name1;
        $current_banner     = $product->banner_image;
        $alt_current_img    = $product->alt_image;
        $pro_id=$product->id;

        $rules = array(
                    'category_id' => 'required',
                    'brand_id' => 'required',
                    'name' => 'required|max:250',
                  //  'slug' => 'required',
                    //'description' => 'required',
                    'status' => 'required'
                );

        if(isset($request->image_name)) {
          //  $rules['image_name'] = 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=100,min_height=200';
        }

        $this->validate($request, $rules);

        if(isset($request->image_name)) {
            $imageName = time().$request->image_name->getClientOriginalName();
            $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $path =  storage_path();
            $img_path = $request->image_name->storeAs('public/products', $imageName);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777);
            $image = $request->file('image_name');
            request()->image_name->move(public_path('products'), $imageName);

          /* $destinationPath = storage_path().('public/products');
            $img = Image::make($image->getRealPath());

            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

            $path =  storage_path().'public/products/';
            //Storage::delete($path.$banner->image_name);
            \File::delete($path.$current_img);
            \File::delete($path.'thumbnail/'.$current_img);*/

         } else {
            $imageName = $current_img;
         }


         if(isset($request->image_name1)) {
            $imageName1 = time().$request->image_name1->getClientOriginalName();
            $imageName1 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);
            $path =  storage_path();
            $img_path = $request->image_name1->storeAs('public/products', $imageName1);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777);
          

         

            $path =  storage_path().'/app/public/products/';
            //Storage::delete($path.$banner->image_name);
            \File::delete($path.$current_img1);
           

         } else {
            $imageName1 = $current_img1;
         }

         
         $catnames=array();
         foreach($request->category_id as $val)
         {
            $cat = Category::find($val);
            $catnames[]=$cat->name;
         }
         $product->category_id = implode(',',$request->category_id);
         $product->category_name =implode(',',$catnames);
      
     // exit; 


        $slug = ($request->slug != '') ? str_slug($request->slug): str_slug($request->name);
       // $foo = Cms::where('slug', '=', $slug)->whereNotIn( 'id', [$id])->get();

        $slug_e = Product::where([['slug', $slug]])
                   ->whereNotIn('id', [$product->id])
                ->count();
        $slug_e1 = Category::where([['slug', $slug]])->count();

        if($slug_e > 0 || $slug_e1 > 0) {
            //$slug = $slug.time();
            $slug = $slug.$product->id;
        }
       
        //$product = new Product;
        $product->parent_id = isset($request->parent_id) ? $request->parent_id: 0;
        $product->name = $request->name;
        $product->part_no=$request->part_no;
        $product->title = $request->title;
       // $product->category_id = $request->category_id;
        //$product->parent_id = isset($request->parent_id) ? $request->parent_id: 0;
        $product->slug  = $slug;
        $product->description = $request->description;
     //   $cat = Category::find($request->category_id);
        $brand = Brand::find($request->brand_id);
        if($request->company_id>0)
        {
            $company = Company::find($request->company_id);
            $product->company_name=$company->name;
        }
      //  $product->category_name=$cat->name;
        $product->brand_name=$brand->name;
        $product->company_id = $request->company_id;

        $product->short_title = $request->short_title;
        $product->short_description = $request->short_description;
        $product->item_code = $request->item_code;
        $product->unit = $request->unit;
        $product->quantity = $request->quantity;
        $product->warrenty = $request->warrenty;
        $product->payment = $request->payment;
        $product->validity = $request->validity;
        $product->modality = $request->modality;

        $product->hsn_code = $request->hsn_code;
        $product->tax_percentage = $request->tax_percentage;
        $product->unit_price = $request->unit_price;
        $product->max_sale_amount = $request->max_sale;
        $product->min_sale_amount = $request->min_sale;
        $product->product_type_id = $request->product_type_id;

        $product->brand_id = $request->brand_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->specification = $request->specification;
        $product->feature = $request->feature;
        $product->image_name = isset($imageName) ? $imageName: '';
        $product->image_name1 = isset($imageName1) ? $imageName1: '';
        $product->status        = $request->status;

        $product->category_type_id = $request->category_type_id;

        
        if($request->related_products!='')
        {
           $product->related_products = implode(',',$request->related_products);
        }
        if($request->competition_product!='')
        {
           $product->competition_product = implode(',',$request->competition_product);
        }
        if($request->op_pdt!='')
         {
            $product->optional_products = implode(',',$request->op_pdt);
         }
        
         $product->option1 = $request->option1;
         $product->option2 = $request->option2;

        $product->save();


     //   exit;


        return redirect()->route('staff.products.index')->with('success', 'Data successfully saved.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $staff_id = session('STAFF_ID');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        if(optional($permission)->product_delete != 'delete')
        {
             return redirect()->route('staff.dashboard');
        }

        if($product->image_name != '') {
            $path =  storage_path().'/app/public/products/';
            //Storage::delete($path.$banner->image_name);
            \File::delete($path.$product->image_name);
            \File::delete($path.'thumbnail/'.$product->image_name);

            deleteFiles( $path, $product->image_name);
        }

        $product->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$product->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        foreach($ids as $id) {
            $product = Product::find($id);
            if($product->image_name != '') {
                $path =  storage_path().'/app/public/products/';
                \File::delete($path.$product->image_name);
                \File::delete($path.'thumbnail/'.$product->image_name);
                deleteFiles($path, $product->image_name);
            }



            $product->delete();
        }

        //DB::table("banners")->whereIn('id',$ids)->delete();

        //DB::table("content")->whereIn('id',explode(",",$ids))->delete();
        //return response()->json(['success'=>"Products Deleted successfully."]);
        return redirect()->route('staff.products.index')->with('success', 'Data has been deleted successfully');

    }

    public static function ajaxDataDetails(Request $request) {
        $cms1 = Product::find($request->id);
        $cat  = Category::find($cms1->category_id);
        $parent_prod = Product::find($cms1->parent_id);
        $alt_cat = Category::find($cms1->alt_category);

        $out = '<table class="detailsTable white" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">
                <tr>
                    <td><span class="detailHead">Category:</span> </td>
                    <td>'.$cat->name.'</td>
                </tr>';


                $out  .= '<tr>
                    <td><span class="detailHead">Name:</span> </td>
                    <td>'.$cms1->name.'</td>
                </tr>
                <tr>
                    <td><span class="detailHead">SEO Url:</span> </td>
                    <td>'.$cms1->slug.'</td>
                </tr>';


                $out .= '<tr>
                    <td><span class="detailHead">Description:</span> </td>
                    <td>'.$cms1->description.'</td>
                </tr>';


                if($cms1->image_name != '') {
                    $out .= '<tr>
                    <td><span class="detailHead">Image:</span> </td>
                    <td><img src="'.asset("storage/app/public/products/$cms1->image_name").'" height="300px"></td>
                </tr>';
                }

                 $out .= '<tr>
                    <td><span class="detailHead">Created Date:</span> </td>
                    <td>'.date('d-m-Y h:i A', strtotime($cms1->created_at)).'</td>
                </tr>

                <tr>
                    <td><span class="detailHead">Status:</span> </td>
                    <td>'.(($cms1->status== 'Y') ? 'Active': 'Inactive').'</td>
                </tr>
                </table>';

        return $out;

    }

    public function exportproduct() {
     return Excel::download(new UsersExport, 'users.xlsx');

    }

    public function importproduct() 
    {
        Excel::import(new UsersImport,request()->file('file'));
        return back();
    }


}
