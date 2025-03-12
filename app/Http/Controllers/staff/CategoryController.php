<?php

namespace App\Http\Controllers\staff;

use App\Models\User_permission;
use App\Product;
use App\Banner;
use App\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Image;
use Storage;

class CategoryController extends Controller
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

        $categories = Category::all();

        return view('staff.category.index', compact('categories'));

        // if($staff_id=="29" || $staff_id=="32" || $staff_id=="4" || $staff_id=="94")
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Category::orderBy('name', 'asc')->get();
        return view('staff.category.create', ['parents'=> $parents]);
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
               // 'image_name' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=350,min_height=230',
                'status' => 'required'
            ));


            if(isset($request->image_name)) {
            $imageName = time().$request->image_name->getClientOriginalName();
            $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $path =  storage_path();
            $img_path = $request->image_name->storeAs('public/category', $imageName);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777);
           //echo $imageName;

           $image = $request->file('image_name');
           $destinationPath = storage_path().('/app/public/category/thumbnail');

           $img = Image::make($image->getRealPath());

           $img->resize(100, 100, function ($constraint) {
               $constraint->aspectRatio();
           })->save($destinationPath.'/'.$imageName);
            }

        $slug = str_slug($request->name);

        $slug_e = Category::where('slug', $slug)->count();
        $slug_e1 = Brand::where([['slug', $slug]])->count();
        if($slug_e >  0 || $slug_e1 > 0) {
            $slug = $slug.time();
        }


        $category = new Category;

        $category->name = $request->name;
        $category->slug = $slug;
        $category->image_name = isset($imageName) ? $imageName: '';;

        $category->status = $request->status;
        $category->save();

         

        return redirect()->route('staff.category.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $parents = Category::orderBy('name', 'asc')->get();
        return view('staff.category.edit', compact('category'), ['parents'=> $parents]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category1 = Category::find($category->id);
        $current = $category1->image_name;


             if(isset($request->image_name)) {
                $this->validate($request, array(
                    'name' => 'required|max:100',
// 'image_name' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=350,min_height=230',
                    'status' => 'required'
                ));
             } else {
                $this->validate($request, array(
                    'name' => 'required|max:100',

                    'status' => 'required'
                ));
             }

             if(isset($request->image_name)) {
                $imageName = time().$request->image_name->getClientOriginalName();
                $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
                $path =  storage_path();
                $img_path = $request->image_name->storeAs('public/category', $imageName);
                $path = $path.'/app/'.$img_path;
                chmod($path, 0777);
                $image = $request->file('image_name');

                $destinationPath = storage_path().('/app/public/category/thumbnail');
                $img = Image::make($image->getRealPath());

                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$imageName);

                $path =  storage_path().'/app/public/category/';
                //Storage::delete($path.$banner->image_name);
                \File::delete($path.$current);
                \File::delete($path.'thumbnail/'.$current);

             } else {
                $imageName = $current;
             }


        $slug = str_slug($request->name);
        $slug_e = Category::where([['slug', $slug]])
                   ->whereNotIn('id', [$category->id])
                ->count();
        $slug_e1 = Brand::where([['slug', $slug]])->count();

        if($slug_e > 0 || $slug_e1 > 0) {
            $slug = $slug.$category->id;
        }
        $category->name = $request->name;
        $category->slug = $slug;
        $category->image_name = isset($imageName) ? $imageName: '';
        $category->status = $request->status;
        $category->save();

        return redirect()->route('staff.category.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->image_name != '') {
            $path =  storage_path().'/app/public/category/';
            //Storage::delete($path.$banner->image_name);
            \File::delete($path.$category->image_name);
            \File::delete($path.'thumbnail/'.$category->image_name);

            deleteFiles( $path, $category->image_name);
        }
        $category->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$category->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        $path =  storage_path().'/app/public/category/';

        foreach($ids as $id) {
            $category = Category::find($id);
            if($category->image_name != '') {
                \File::delete($path.$category->image_name);
                \File::delete($path.'thumbnail/'.$category->image_name);
                deleteFiles($path, $category->image_name);
            }
            $category->delete();
        }


        return redirect()->route('staff.category.index')->with('success', 'Data has been deleted successfully');

    }

}
