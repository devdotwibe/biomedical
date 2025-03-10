<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cms;
use App\Banner;
use App\Category;
use App\Brand;
use App\Product;
use App\Product_image;
use App\Product_view_count;

//use Route;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function index()
    {
    	// return view('site_under_construction');

    	$banner 	= '';
echo '111';exit;
        $slug = 'products';
        $products = DB::table('products')->where('status', '=','Y')->where('verified', '=','Y')->where('show_inPage','Y')->orderBy('id','asc')->paginate(12)->onEachSide(1);
        $category = DB::select("SELECT 
        cat.id AS catid, 
        MAX(cat.slug) AS catslug, 
        MAX(cat.name) AS catname, 
        (SELECT COUNT(*) FROM products 
         WHERE category_id = cat.id 
           AND show_inPage = 'Y' 
           AND verified = 'Y' 
           AND status = 'Y') AS totalproduct 
    FROM categories AS cat 
    INNER JOIN products AS products ON cat.id = products.category_id  
    WHERE products.status = 'Y' 
      AND products.verified = 'Y' 
      AND cat.status = 'Y' 
      AND products.show_inPage = 'Y'  
    GROUP BY cat.id  
    ORDER BY cat.id DESC");
        
        $brand =  DB::select("select *,brand.slug as brandslug,brand.id as brandid,brand.name as brandname,(SELECT count(*)  FROM products WHERE brand_id = brand.id AND show_inPage='Y' AND verified='Y' AND status='Y' ) as totalproduct from brand as brand inner join products as products ON brand.id=products.brand_id  where products.status='Y' AND products.verified='Y' AND brand.status='Y' and products.show_inPage='Y'  group by brand.id  order by brand.id desc");
        
        return view('products.index', compact('products'), ['category'=> $category, 'brand' => $brand	]);
        
    }
    public function productindex()
    {
    	// return view('site_under_construction');

    	$banner 	= '';

        $slug = 'products';
        $products = DB::table('products')->where('status', '=','Y')->where('verified', '=','Y')->where('show_inPage','Y')->orderBy('id','asc')->paginate(12)->onEachSide(1);
        $category = DB::select("SELECT cat.id AS catid, MAX(cat.slug) AS catslug, MAX(cat.name) AS catname, (SELECT COUNT(*) FROM products WHERE category_id = cat.id AND show_inPage = 'Y' AND verified = 'Y' AND status = 'Y') AS totalproduct FROM categories AS cat INNER JOIN products AS products ON cat.id = products.category_id WHERE products.status = 'Y' AND products.verified = 'Y' AND cat.status = 'Y' AND products.show_inPage = 'Y' GROUP BY cat.id ORDER BY cat.id DESC");
        
        $brand = DB::select("SELECT brand.id AS brandid, MAX(brand.slug) AS brandslug, MAX(brand.name) AS brandname, (SELECT COUNT(*) FROM products WHERE brand_id = brand.id AND show_inPage = 'Y' AND verified = 'Y' AND status = 'Y') AS totalproduct FROM brand AS brand INNER JOIN products AS products ON brand.id = products.brand_id WHERE products.status = 'Y' AND products.verified = 'Y' AND brand.status = 'Y' AND products.show_inPage = 'Y' GROUP BY brand.id ORDER BY brand.id DESC");

        
        return view('products.index', compact('products'), ['category'=> $category, 'brand' => $brand	]);
        
    }

    
    public function quote_details_cat($pd_slug)
    {
    	// return view('site_under_construction');

        $banner     = '';

        $slug = 'products';
        $cms    = Cms::where('slug',$slug)->first();

        $p_slug = explode(",",$pd_slug);

        //print_r($p_slug);die;
       // $products     = Product::where(['status' => 'Y'])->orderBy('id','asc')->get()->paginate(15);
        $products = DB::table('products')->where('verified', '=','Y')->where('show_inPage','Y')->whereIn('slug', $p_slug)->orderBy('id','asc')->paginate(12)->onEachSide(1);
        $category =  DB::select("select *,cat.slug as catslug,cat.id as catid,cat.name as catname,(SELECT count(*) as totalproduct FROM products WHERE category_id = cat.id AND show_inPage='Y' AND verified='Y' AND status='Y' ) as totalproduct from categories as cat inner join products as products ON cat.id=products.category_id  where products.status='Y' AND products.verified='Y' AND cat.status='Y' and products.show_inPage='Y' group by cat.id  order by cat.id desc");
        
        $brand =  DB::select("select *,brand.slug as brandslug,brand.id as brandid,brand.name as brandname,(SELECT count(*)  FROM products WHERE brand_id = brand.id AND show_inPage='Y' AND verified='Y' AND status='Y' ) as totalproduct from brand as brand inner join products as products ON brand.id=products.brand_id  where products.status='Y' AND products.verified='Y' AND brand.status='Y' and products.show_inPage='Y'  group by brand.id  order by brand.id desc");
        
        return view('products.index', compact('products'), ['category'=> $category, 'brand' => $brand, 'cms' => $cms    ]);
    }

    public function categories($slug) {
    	// return view('site_under_construction');

       // DB::enableQueryLog();
        //echo $category;
        $category = Category::where('slug', $slug)->first();
        $brand = Brand::where('slug', $slug)->first();
        $product = Product::where('slug', $slug)->first();
        

        if((is_null($category) || $category->status == 'N') && (is_null($product)|| $product->show_inPage=='N' || $product->status == 'N'|| $product->verified == 'N') ) {          
           // return redirect('404');
          
             if((is_null($brand) ) && (is_null($product) ) ) {          
            return redirect('404');
        } else {
            if(!is_null($brand)) {
                

                $sub_categories = Brand::where([ 'status' => 'Y'])->orderBy('id', 'asc')->get();
                
                $cms = array();
                $category = Brand::where('slug', $slug)->first();
                $type="brand";
                //print_r($sub_categories);
                //exit;
                $categoryall =  DB::select("select *,cat.slug as catslug,cat.id as catid,cat.name as catname,(SELECT count(*) as totalproduct FROM products WHERE category_id = cat.id AND show_inPage='Y' AND verified='Y' AND status='Y' ) as totalproduct from categories as cat inner join products as products ON cat.id=products.category_id  where products.status='Y' AND products.verified='Y' AND cat.status='Y' and products.show_inPage='Y' group by cat.id  order by cat.id desc");
        
                $brand =  DB::select("select *,brand.slug as brandslug,brand.id as brandid,brand.name as brandname,(SELECT count(*)  FROM products WHERE brand_id = brand.id AND show_inPage='Y' AND verified='Y' AND status='Y' ) as totalproduct from brand as brand inner join products as products ON brand.id=products.brand_id  where products.status='Y' AND products.verified='Y' AND brand.status='Y' and products.show_inPage='Y'  group by brand.id  order by brand.id desc");
             
                $products = DB::table('products')->where('show_inPage','Y')->where('status', '=','Y')->where('verified', '=','Y')->where('brand_id', '=',$category->id)->orderBy('id','asc')->paginate(12)->onEachSide(1);
                return view('products.category', compact('sub_categories', 'category','products','type'), ['cms'=> $cms,'categoryall'=> $categoryall,'brand'=> $brand]);
            }
         }
            
            
        } else {
            if(!is_null($category)) {
                
                $sub_categories = Category::where([ 'status' => 'Y'])->orderBy('id', 'asc')->get();
                $type="category";
                $cms = array();
                //print_r($sub_categories);
                //exit;
                $categoryall =  DB::select("select *,cat.slug as catslug,cat.id as catid,cat.name as catname,(SELECT count(*) as totalproduct FROM products WHERE category_id = cat.id AND show_inPage='Y' AND verified='Y' AND status='Y' ) as totalproduct from categories as cat inner join products as products ON cat.id=products.category_id  where products.status='Y' AND products.verified='Y' AND cat.status='Y' and products.show_inPage='Y' group by cat.id  order by cat.id desc");
        
                $brand =  DB::select("select *,brand.slug as brandslug,brand.id as brandid,brand.name as brandname,(SELECT count(*)  FROM products WHERE brand_id = brand.id AND show_inPage='Y' AND verified='Y' AND status='Y' ) as totalproduct from brand as brand inner join products as products ON brand.id=products.brand_id  where products.status='Y' AND products.verified='Y' AND brand.status='Y' and products.show_inPage='Y'  group by brand.id  order by brand.id desc");
                $products = DB::table('products')->where('status', '=','Y')->where('category_id', '=',$category->id)->where('verified', '=','Y')->where('show_inPage','Y')->orderBy('id','asc')->paginate(12)->onEachSide(1);
                return view('products.category', compact('sub_categories', 'category','products','type'), ['cms'=> $cms,'categoryall'=> $categoryall,'brand'=> $brand]);
            }
            
        
             
            if(!is_null($product)) {
                //print_r($product);die;
                //echo $product->id;die;
                $cms = array();
                $related = ($product->related_products != '') ? explode(',',$product->related_products): array();
                
                $related_products = DB::select("select * from products  where status='Y' AND verified='Y' AND id!='".$product->id."' AND show_inPage='Y' AND (brand_id='".$product->brand_id."' OR category_id='".$product->category_id."') order by  id asc limit 8");;  
          
                $sub_products = Product::where('status', 'Y')->where('products.show_inPage','Y')
                                             ->orderBy('order_id', 'asc')
                                             ->get(); 
                $ip=$_SERVER['REMOTE_ADDR'];
                $product_view = DB::select("select * from product_view_count  where ip='".$ip."'AND  product_id='".$product->id."' ");
                if(count($product_view)==0){
                $product_view_count = new Product_view_count;
                $product_view_count->product_id  = $product->id;
                $product_view_count->ip  = $ip;
                $product_view_count->save();

                $product = Product::find($product->id);
                $view_count=$product->view_count+1;
                $product->view_count  = $view_count;
                $product->save();
                }
                
                $category = Category::where([ 'id' => $product->category_id])->get();
                $product_image = Product_image::where([ 'product_id' => $product->id])->get();
                
           // print_r($product_image); 
                return view('products.details', compact('product','product_image', 'related_products', 'sub_products','category'), ['cms'=> $cms]);
            }
        }
    }

}
