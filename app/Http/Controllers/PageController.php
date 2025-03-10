<?php

namespace App\Http\Controllers;

use App\Models\Category_type;
use App\Models\Cms;
use App\Models\Downloads_category;
use App\Models\Marketspace;
use App\Models\References;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Cooperations;
use App\Models\Feedbacks;
use App\Models\History;
use App\Models\HomeBanner;
use App\Models\Industries;
use App\Models\Marketspace_education;
use App\Models\Marketspace_experience;
use App\Models\Marketspace_qualification;
use App\Models\Marketspace_skills;
use App\Models\Product_type;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Downloads;
use App\Category;
use Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Redirect;



class PageController extends Controller
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

    public function index($slug)
    {
    	// return view('site_under_construction');
    	$banner 	= '';

    	//$contact 	= Cms::find($slug);
    	$cms 	= Cms::where('slug',$slug)->first();

    	if(is_null($cms) || $cms->status == 'N') {
    		$industries    = Industries::where('slug',$slug)->first();

    		if(is_null($industries) || $industries->status == 'N') {
    			//abort(404);
    			//return view('404');
    			return redirect('404');
    		} else {
    			if($industries->banner_id > 0) {
		    		$banner   = Banner::find($industries->banner_id);
		    	}

		    	return view('industries.page', compact('industries'), ['cms'=> $cms, 'banner' => $banner]);
    		}
    	} else {
    		if($cms->banner_id > 0) {
	    		$banner   = Banner::find($cms->banner_id);
	    	}

	    	if($cms->id == 7 || $cms->parent_id == 7) {
	    		//echo $cms->id;
	    		// $downloads = Downloads::where(['status'=> 'Y', 'content_id' => $cms->id])
	    		//                          ->orderBy('category_id','asc')->orderBy('id','desc')->get();
             	//$down_cat  = Downloads_category::all();


                $categories         = Downloads_category::where(['status'=> 'Y', 'parent_id' => 0])->orderBy('id','asc')->get();
               // $allCategories      = Downloads_category::where('status', 'Y')->pluck('name','id')->all();

                //print_r($allCategories);

                $download_items  = array();

                foreach($categories as $category) {
                    if(count($category->downloads)) {
                        $flag =0;
                        $main_downs = array();
                        foreach($category->downloads as $downloads) {
                            if($downloads->content_id == $cms->id) {
                               $flag = 1;
                              $main_downs[] = $downloads;
                            }
                        }

                        if($flag > 0) {
                            $download_items[$category->id] = array('name'=>$category->name, 'data' => $main_downs);
                        }
                    }

                    $flag = 0;
                    $exist = 0;

                    $child_array = array();

                    if(count($category->childs)) {
                       foreach($category->childs as $child) {
                            $flag = 0;
                            $child_downs = array();
                            if(count($child->downloads) > 0) {
                                foreach($child->downloads as $downloads) {

                                    if($downloads->content_id == $cms->id) {
                                       $flag = 1;
                                       $child_downs[] = $downloads;
                                    }
                                }
                            }

                            if($flag > 0) {
                                $exist++;
                                $child_array[$child->id] = array('name'=> $child->name, 'child_data' =>$child_downs);
                            }
                       }

                       if($exist > 0) {
                            $download_items[$category->id] = array('name'=>$category->name, 'childrens' => $child_array);
                       }
                    }
                }

                   // echo '<pre>';
               // print_r($download_items);

                //exit;

                return view('downloads.index',compact('download_items'),  ['cms'=> $cms, 'banner' => $banner]);
             	
	    		//return view('downloads.index', compact('downloads'), ['cms'=> $cms, 'banner' => $banner, 'down_cat' => $down_cat]);
	    	}

	    	switch($cms->id) {

	    		case 4:
	    				return view('contact', ['cms'=> $cms, 'banner' => $banner]);
	    				break;
	    		case 11:
	    				return view('aboutus.index', ['cms'=> $cms, 'banner' => $banner]);
	    				break;
	    		case 12:
	    				$histories = History::where('status', 'Y')->orderBy('id', 'asc')->get();
	    				return view('aboutus.history', compact('histories'), ['cms'=> $cms, 'banner' => $banner]);
	    				break;
	    		case 14:
	    				$cooperations = Cooperations::where('status', 'Y')->orderBy('id', 'asc')->get();
	    				//print_r($cooperations); exit;
	    				return view('aboutus.cooperations', compact('cooperations'), ['cms'=> $cms, 'banner' => $banner]);
	    				break;

	    		case 15:
	    				$references = References::where('status', 'Y')->orderBy('id', 'asc')->get();
	    				$feedbacks = Feedbacks::where('status', 'Y')->orderBy('id', 'asc')->get();
	    				return view('aboutus.references', compact('references'), ['cms'=> $cms, 'banner' => $banner, 'feedbacks' => $feedbacks]);
	    				break;

                // case 16:
                //         $categories     = Category::where(['parent_id' =>0, 'status' => 'Y'])->orderBy('id','asc')->get();
                //         return view('products.index', compact('categories'), ['cms'=> $cms, 'banner' => $banner]);
                //         break;

	    		default:
	    				return view('cms',  ['cms'=> $cms, 'banner' => $banner]);
	    				break;
	    	}
    	}
    }

    public function home()
    {
    	// return view('site_under_construction');
    	$home 		= Cms::find(1);
    	// $contact 	= Cms::find(4);
    	// $terms 		= Cms::find(2);
    	// $privacy 	= Cms::find(3);

//     	echo $currentPath= Route::getFacadeRoot()->current()->uri();
//     	 $route = Route::current();
//     	 echo $name = Route::currentRouteName();

// $action = Route::currentRouteAction();

    	$banner 	= '';

    	if($home->banner_id > 0) {
    		$banner   = Banner::find($home->banner_id);

    		//echo $url = Storage::url('app/public/banners/'.$banner->image_name);
    		//$path = storage_path().'/app/public/banners/';
			// if(file_exists($path.'thumb_'.$banner->image_name)) {
			//     $img_name = asset("storage/app/public/banners/thumb_".$banner->image_name);
			// }  else {
			//     $img_name = asset("storage/app/public/banners/$banner->image_name");
			// }
    	}
   //$categories         = Category::where(['status'=> 'Y'])->orderBy('id','asc')->get();
   $categories = DB::select("SELECT cat.id AS catid, MAX(cat.image_name) AS catimage, MAX(cat.slug) AS catslug, MAX(cat.name) AS catname FROM categories AS cat INNER JOIN products AS products ON cat.id = products.category_id WHERE cat.status = 'Y' AND products.show_inPage = 'Y' GROUP BY cat.id ORDER BY cat.id ASC");
   $brand  = Brand::where(['status'=> 'Y'])->orderBy('id','asc')->get();
   $category_type  = Category_type::orderBy('id','asc')->get();
   $product_type  = Product_type::orderBy('id','asc')->get();
   $brand_search = DB::select("SELECT brand.id AS brandid, 
   MAX(brand.name) AS brandname FROM brand AS brand INNER JOIN products AS products ON brand.id = products.brand_id WHERE brand.status = 'Y' AND products.show_inPage = 'Y' GROUP BY brand.id ORDER BY brand.id ASC");
   $testimonial   = Testimonial::orderBy('id','asc')->get();
   $homeBanner    = HomeBanner::orderBy('id','asc')->get();
   
   //return view('homenew',['homeBanner'=> $homeBanner,'testimonial'=> $testimonial,'cms'=> $home,'product_type'=> $product_type,'home' => $home, 'banner' => $banner, 'category_type' => $category_type, 'categories' => $categories, 'brand' => $brand, 'brand_search' => $brand_search]);
   return view('home',['homeBanner'=> $homeBanner,'testimonial'=> $testimonial,'cms'=> $home,'product_type'=> $product_type,'home' => $home, 'banner' => $banner, 'category_type' => $category_type, 'categories' => $categories, 'brand' => $brand, 'brand_search' => $brand_search]);
    }

    public function aboutus()
    {
    	// return view('site_under_construction');
    	$aboutus = Cms::find(11);
    	if(is_null($aboutus) || $aboutus->status == 'N') {
    		return redirect('404');
    	}

    	if($aboutus->banner_id > 0) {
    		$banner   = Banner::find($aboutus->banner_id);
    	}

    	return view('aboutus.index', compact('aboutus'), ['cms'=> $aboutus, 'banner' => $banner]);
    }

    public function marketspace()
    {
    	// return view('site_under_construction');

        return view('marketspace.home');
    }
    public function marketspacesearch()
    {
    	// return view('site_under_construction');
        
        $skills         = Marketspace_skills::groupBy('product_id')->orderBy('id','desc')->get();
        
       /* if(session('MARKETSPACE_ID')>0)
        {
            $masterspaces = DB::table('marketspace')
            ->select('marketspace.id','marketspace.name','marketspace.image','marketspace.about')
            ->join('marketspace_skills', 'marketspace.id','=', 'marketspace_skills.marketspace_id')
            ->where('marketspace.id','!=',session('MARKETSPACE_ID'))
            ->orderBy('marketspace.id', 'DESC')->groupBy('marketspace.id')->paginate(20);
           
        }
        else{*/
            $masterspaces = DB::table('marketspace')
            ->select('marketspace.id','marketspace.name','marketspace.image','marketspace.about')
            ->join('marketspace_skills', 'marketspace.id','=', 'marketspace_skills.marketspace_id')
            ->orderBy('marketspace.id', 'DESC')->groupBy('marketspace.id')->paginate(20);
           
       /* }*/
        
      
        return view('marketspace.search',compact('skills','masterspaces'));
    }
    public function notfound()
    {
        return view('404');
    }
    public function hero()
    {
        return view('hero');
    }
    public function thankyou(){
        return view('thankyou');
    }
    public function heroSubmit(Request $request)
    {        
        $rules = [
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'required|max:50',
            'message' => 'max:1000',
        ];
        $customMessages = [
            'name.required' => 'Name is required!',
            'name.max'  => 'Maximum 100 characters allowed!',
            'email.required' => 'Email is required!',
            'email.email'  => 'Invalid email address!',
            'phone.max'  => 'Maximum 50 characters allowed!',
            'message.max'  => 'Maximum 1000 characters allowed!', 
        ];
        $this->validate($request, $rules, $customMessages);
        $data = array(
            'name'=> $request->name,
            'email'=> $request->email,
            'messages'=> empty($request->message)?"":$request->message,
            'phone'=> $request->phone
        );
                
        \Mail::send('email.heroenquiry', $data, function($message) {
            $message->to(setting('CONTACT_EMAIL_ID'), setting('CONTACT_EMAIL_NAME'))->subject('Enquiry | '.date('l jS \o\f F Y h:i:s A'));
            $message->from(setting('ADMIN_EMAIL_ID'),setting('ADMIN_EMAIL_NAME'));
        });
        return redirect()->route('form.thankyou')->with('success', 'Thank you for contacting us. One of our staff will contact you soon!');
    }
    public function search_skills(Request $request) {

    $products =  DB::select("select products.name,products.id from  marketspace_skills as skills
    INNER JOIN  products as products ON products.id=skills.product_id
     WHERE products.name LIKE '%".$request->skill."%' and products.show_inPage='Y' order by products.id desc");
    
        
    echo json_encode($products);

    }

    function fetch_data(Request $request)
    {
    	// return view('site_under_construction');

        if($request->skills_id!='' || $request->price!='' || $request->search_word!='' || $request->review>0 )
        {
            $masterspaces = DB::table('marketspace')
            ->select('marketspace.id','marketspace.name','marketspace.image','marketspace.about'
            )
            ->join('marketspace_skills', 'marketspace.id','=', 'marketspace_skills.marketspace_id')
            ->join('products', 'products.id','=', 'marketspace_skills.product_id')
            ->join('brand', 'brand.id','=', 'marketspace_skills.brand_id')
            ->join('product_type', 'product_type.id','=', 'marketspace_skills.product_type_id')
            ->join('category_type', 'category_type.id','=', 'marketspace_skills.category_type_id')
            ->leftjoin('rating', 'rating.post_id','=', 'marketspace.id')->where('products.show_inPage','Y')

            ->where(function ($query)  use ($request) {
                if($request->skills_id!=''){
                    $query->whereIn('marketspace_skills.product_id',$request->skills_id);
                }
                if($request->price!=''){
                    $query->where('marketspace_skills.price','>=',$request->price);
                }
                if($request->review>0){
                    $query->where('rating.rating','>=',$request->review);
                   
                }
                if($request->search_word!=''){
                    
                    $query->where('products.name', 'like', '%' . $request->search_word . '%');
                    $query->orwhere('brand.name', 'like', '%' . $request->search_word . '%');
                    $query->orwhere('product_type.name', 'like', '%' . $request->search_word . '%');
                    $query->orwhere('category_type.name', 'like', '%' . $request->search_word . '%');
                    
                }
                /*if(session('MARKETSPACE_ID')>0)
                {
                    $query->where('marketspace.id','!=',session('MARKETSPACE_ID'));
                }*/
            })
            
            //->whereIn('marketspace_skills.product_id',$request->skills_id)
            ->orderBy('marketspace.id', 'DESC')->groupBy('marketspace.id')->paginate(20);

       

        }
        else{
            $masterspaces = DB::table('marketspace')
            ->select('marketspace.id','marketspace.name','marketspace.image','marketspace.about'
            )
            ->join('marketspace_skills', 'marketspace.id','=', 'marketspace_skills.marketspace_id')
            ->join('products', 'products.id','=', 'marketspace_skills.product_id')
            ->join('brand', 'brand.id','=', 'marketspace_skills.brand_id')
            ->join('product_type', 'product_type.id','=', 'marketspace_skills.product_type_id')
            ->join('category_type', 'category_type.id','=', 'marketspace_skills.category_type_id')
            ->leftjoin('rating', 'rating.post_id','=', 'marketspace.id')->where('products.show_inPage','Y')
            ->orderBy('marketspace.id', 'DESC')->groupBy('marketspace.id')->paginate(20);
        }
        
        return view('layouts.postdata', compact('masterspaces'))->render();
    
    }
    function profile($id)
    { 
        $marketspace = Marketspace::where('id', '=', $id)->first();
        $marketspace_quali = Marketspace_qualification::where('marketspace_id', '=', $id)->get();
        $marketspace_edu = Marketspace_education::where('marketspace_id', '=', $id)->get();
        $marketspace_exp = Marketspace_experience::where('marketspace_id', '=', $id)->get();
        $marketspace_skill = Marketspace_skills::where('marketspace_id', '=', $id)->get();
      
        
        return view('marketspace.profile',compact('marketspace_skill','marketspace','marketspace_quali','marketspace_edu','marketspace_exp'));
    
    }
    function verify($unique_code)
    { 
    	// return view('site_under_construction');
      
        $marketspace = Marketspace::where('unique_code', '=', $unique_code)->first();
        if($marketspace)
        {
           
           
            $marketspace =Marketspace::find($marketspace->id);
            $marketspace->unique_code = time();
            $marketspace->status ='Y';
            $marketspace->save();
            
            
            Session::put('MARKETSPACE_ID', $marketspace->id);
            return Redirect::to('marketspace/dashboard');
            
        }
        else{
            return view('404');
        }
    }
    
}
