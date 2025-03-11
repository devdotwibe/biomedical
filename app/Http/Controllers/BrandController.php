<?php

namespace App\Http\Controllers\admin;

use App\Brand;
use App\Product;
use App\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $categories = Brand::all();
       return view('admin.brand.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parents = Brand::orderBy('name', 'asc')->get();
        return view('admin.brand.create', ['parents'=> $parents]);
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
                'image_name' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=50,min_height=50',
                'status' => 'required'
            ));



            $imageName = time().$request->image_name->getClientOriginalName();
            $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $path =  storage_path();
            $img_path = $request->image_name->storeAs('public/brand', $imageName);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777);
           //echo $imageName;


        $slug = str_slug($request->name);

        $slug_e = Brand::where('slug', $slug)->count();
        $slug_e1 = Product::where([['slug', $slug]])->count();
        if($slug_e >  0 || $slug_e1 > 0) {
            $slug = $slug.time();
        }


        $brand = new Brand;

        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->image_name = isset($imageName) ? $imageName: '';;

        $brand->status = $request->status;
        $brand->save();

            $image = $request->file('image_name');
            $destinationPath = storage_path().('/app/public/brand/thumbnail');

            $img = Image::make($image->getRealPath());

            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

        return redirect()->route('admin.brand.index')->with('success','Data successfully saved.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        $parents = Brand::orderBy('name', 'asc')->get();
        return view('admin.brand.edit', compact('brand'), ['parents'=> $parents]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $brand1 = Brand::find($brand->id);
        $current = $brand1->image_name;


             if(isset($request->image_name)) {
                $this->validate($request, array(
                    'name' => 'required|max:100',

                    'image_name' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=50,min_height=50',
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
                $img_path = $request->image_name->storeAs('public/brand', $imageName);
                $path = $path.'/app/'.$img_path;
                chmod($path, 0777);
                $image = $request->file('image_name');

                $destinationPath = storage_path().('/app/public/brand/thumbnail');
                $img = Image::make($image->getRealPath());

                $img->resize(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$imageName);

                $path =  storage_path().'/app/public/brand/';
                //Storage::delete($path.$banner->image_name);
                \File::delete($path.$current);
                \File::delete($path.'thumbnail/'.$current);

             } else {
                $imageName = $current;
             }


        $slug = str_slug($request->name);
        $slug_e = Brand::where([['slug', $slug]])
                   ->whereNotIn('id', [$brand->id])
                ->count();
        $slug_e1 = Product::where([['slug', $slug]])->count();

        if($slug_e > 0 || $slug_e1 > 0) {
            $slug = $slug.$brand->id;
        }
        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->image_name = isset($imageName) ? $imageName: '';
        $brand->status = $request->status;
        $brand->save();

        return redirect()->route('admin.brand.index')->with('success', 'Data successfully saved.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        if($brand->image_name != '') {
            $path =  storage_path().'/app/public/brand/';
            //Storage::delete($path.$banner->image_name);
            \File::delete($path.$brand->image_name);
            \File::delete($path.'thumbnail/'.$brand->image_name);

            deleteFiles( $path, $brand->image_name);
        }
        $brand->delete();

        return response()->json(['success'=>"Data has been deleted successfully.", 'tr'=>'tr_'.$brand->id]);
    }

    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        $path =  storage_path().'/app/public/brand/';

        foreach($ids as $id) {
            $brand = Brand::find($id);
            if($brand->image_name != '') {
                \File::delete($path.$brand->image_name);
                \File::delete($path.'thumbnail/'.$brand->image_name);
                deleteFiles($path, $brand->image_name);
            }
            $brand->delete();
        }


        return redirect()->route('admin.brand.index')->with('success', 'Data has been deleted successfully');

    }

}
