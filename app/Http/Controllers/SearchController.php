<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Cms;
use App\Industries;
use App\Category;
use App\Product;
use App\Downloads;
use App\Downloads_category;

use Session;

use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	// return view('site_under_construction');

        //DB::statement("");

        //DB::enableQueryLog();

        $results    = array();
        $search_word = $request->search_word;
        $brand_id = Session::get('brand_id');
     $brand_id_model = Session::get('brand_id_model');
     
         $category_id = Session::get('category_id');
      $product_type_id = Session::get('product_type_id');
         $category_type_id = Session::get('category_type_id');
          $product_id = Session::get('product_id');
     
     
       
         $item_code= session('item_code');
       
        if($search_word != '') {

           $prodCnt = Product::select('name','slug')->where([['status', 'Y' ],['verified', 'Y' ],['show_inPage','Y']])
                           ->where(function ($query) use ($search_word) {
                                $query->where('name', 'like', '%' . $search_word . '%')
                                      ->orWhere('title', 'like', '%' . $search_word . '%')
                                      ->orWhere('description', 'like', '%' . $search_word . '%')                                      
                                      ->orWhere('short_title', 'like', '%' . $search_word . '%')
                                      ->orWhere('short_description', 'like', '%' . $search_word . '%')
                                      ->orWhere('slug', 'like', '%' . $search_word . '%');
                            })->count();
 
            $products = Product::where([['status', 'Y' ],['verified', 'Y' ],['show_inPage','Y']])
                            ->where(function ($query) use ($search_word) {
                                $query->where('name', 'like', '%' . $search_word . '%')
                                       ->orWhere('title', 'like', '%' . $search_word . '%')
                                      ->orWhere('description', 'like', '%' . $search_word . '%')                                      
                                      ->orWhere('short_title', 'like', '%' . $search_word . '%')
                                      ->orWhere('short_description', 'like', '%' . $search_word . '%')
                                      ->orWhere('slug', 'like', '%' . $search_word . '%');
                            })
    
                     
                        ->simplePaginate(12);
             
                        $totalCount=$prodCnt;
        }
      else  if($category_id != '' || $brand_id!='' || $brand_id_model!='' || $item_code!="" || $product_type_id!="" || $product_id!="" || $category_type_id!="") {

  
                 $prodCnt = Product::where([['status', 'Y' ],['verified', 'Y' ],['show_inPage','Y']])
                ->where(function ($query) use ($category_id,$brand_id,$brand_id_model,$item_code,$product_type_id,$product_id,$category_type_id) {
                
                    if($category_id != ''){
                        //$query->where('category_id', '=', $category_id);
                        $query->whereRaw("find_in_set('$category_id',category_id)");
                    }
                    if($brand_id != ''){
                        $query->where('brand_id', '=', $brand_id);
                    }
                    if($brand_id_model != ''){
                        $query->where('brand_id', '=', $brand_id_model);
                    }

                    if($product_type_id!= ''){
                        $query->where('product_type_id', '=', $product_type_id);
                    }
                   
                    if($category_type_id != ''){
                        $query->where('category_type_id', '=', $category_type_id);
                    }
                   
                    if($item_code != ''){
                        $query->where('item_code', '=', $item_code);
                    }
                    if($product_id != ''){
                        $query->where('id', '=', $product_id);
                    }
                })->count();

        $products = Product::where([['status', 'Y' ],['verified', 'Y' ],['show_inPage','Y']])
        ->where(function ($query) use ($category_id,$brand_id,$brand_id_model,$item_code,$product_type_id,$product_id,$category_type_id) {
            if($category_id != ''){
               // $query->where('category_id', '=', $category_id);
               $query->whereRaw("find_in_set('$category_id',category_id)");
            }
          
            if($brand_id != ''){
                $query->where('brand_id', '=', $brand_id);
            }
            if($brand_id_model != ''){
                $query->where('brand_id', '=', $brand_id_model);
            }
            if($item_code != ''){
                $query->where('item_code', '=', $item_code);
            }

            if($product_type_id != ''){
                $query->where('product_type_id', '=', $product_type_id);
            }

            if($category_type_id != ''){
                $query->where('category_type_id', '=', $category_type_id);
            }
            if($product_id != ''){
                $query->where('id', '=', $product_id);
            }


            
            
                })

        
            ->simplePaginate(12);

            $totalCount=$prodCnt;
        }
        
        else {
            $totalCount = array();
            $products    = array();
        }
        $category =  DB::select("select *,cat.slug as catslug,cat.id as catid,cat.name as catname,(SELECT count(*) as totalproduct FROM products WHERE category_id = cat.id AND show_inPage='Y' AND verified='Y' AND status='Y' ) as totalproduct from categories as cat inner join products as products ON cat.id=products.category_id  where products.status='Y' AND products.verified='Y' AND cat.status='Y' AND products.show_inPage='Y' group by cat.id  order by cat.id desc");
        
        $brand =  DB::select("select *,brand.slug as brandslug,brand.id as brandid,brand.name as brandname,(SELECT count(*)  FROM products WHERE brand_id = brand.id AND show_inPage='Y' AND verified='Y' AND status='Y' ) as totalproduct from brand as brand inner join products as products ON brand.id=products.brand_id  where products.status='Y' AND products.verified='Y' AND brand.status='Y' AND products.show_inPage='Y'  group by brand.id  order by brand.id desc");
        
        return view('search', compact('products', 'totalCount'), ['search_word' => $search_word,'category' => $category,'brand' => $brand]);
    }

    public function search(Request $request)
    {
    	// return view('site_under_construction');

        //$results    = array();
        //session('search_word', $request->search_word);

        Session::put('search_word', $request->search_word);
        Session::put('brand_id', $request->brand_id);
        Session::put('brand_id_model', $request->brand_id_model);
        
        Session::put('category_id', $request->category_id);
        Session::put('product_id', $request->product_id);
        Session::put('product_type_id', $request->product_type_id);
        Session::put('category_type_id', $request->category_type_id);
        
        
        Session::put('item_code', $request->item_code);
        
        //$results = CMS::where([['status', 'Y' ]])->paginate(5);
        return redirect()->route('search.index'); //view('search', 
        //return redirect()->route('search.index', ['search_word'=> $request->search_word])->with('s', $request->search_word); //view('search', compact('results'), ['search_word' => $request->search_word]);
    }

    
}
