<?php

namespace App\Http\Controllers\staff;

use Illuminate\Http\Request;
use App\Http\Controllers\admin\CmsController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

use Image;
use Storage;
use URL;
use App\Http\Controllers\ThumbImage;
use App\Contact_person;
use Imagick;
use App\Category;
use App\Product_image;
use App\Quote;
use Validator;
use App\State;
use App\Taluk;
use App\Country;
use App\District;
use App\Task;
use App\Staff;
use App\Relatedto_subcategory;
use App\Task_comment;
use App\Task_comment_replay;
use App\Product;

use App\Dailyclosing;
use Input;
class AdminajaxController extends Controller
{
    public function ajaxRequest()
    {
        return view('admin.ajaxRequest');

    }
    
    public function ajaxRequestPost(Request $request)
    {
        $input = $request->all();
        return response()->json(['success'=>'Got Simple Ajax Request.']);
    }
    
    public function ajaxGet()
    {
        return view('admin.ajaxRequest');
    }
    
    public function ajaxPost(Request $request)
    {
        if($request->controller == 'CMS') {
            CmsController::getAjax();
            $input = $request->all();
            return response()->json(['success'=>'Got Simple Ajax Request.']);
        }
    }
    
    public function ajaxDataTables(Request $request) {
        $user = DB::table('content')->get();
        //print_r($user);
        //exit;
        
        foreach($user as $item) {
            $user1 = new \stdClass();
             $user1->id = $item->id;
            $user1->name = $item->name;
            $user1->slug = $item->slug;
             $user1->created_at = $item->created_at;
            $user1->status = $item->status;
            $user1->status = $item->status;

            $user2[] = $user1; 
        }
        $results = ["sEcho" => 1,

                        "iTotalRecords" => count($user),

                        "iTotalDisplayRecords" => count($user),

                        "aaData" => $user2 ];


        return json_encode($results);
    }

    public function popup_imageCrop(Request $request) {
        $id                     = (isset($request->id) &&  $request->id > 0) ? $request->id : 0;
        $thumb_width            = (isset($request->thumb_width) &&  $request->thumb_width > 0) ? $request->thumb_width: 0;
        $thumb_height           = (isset($request->thumb_height) &&  $request->thumb_height > 0) ? $request->thumb_height : 0;
        $folder                 = $request->folder;

        $from                   = $request->from;
        $img                    = $request->img;

        $table                  = $folder;

        if($folder == 'contacts') {
            $table                  = 'contact_persons';
        }

        if($folder == 'category') {
            $table                  = 'categories';
        }

        
        $image_class = $data   = DB::table($table)->where([['id', $request->id ]])->first();

        $dir_file   =   storage_path().'/app/public/'.$folder.'/'; 
        $uri_file   =   URL::to('/').'/storage/app/public/'.$folder.'/'; 

        $save = (isset($request->save) &&  $request->save > 0) ? $request->save : 0;



        if($save > 0) {

            $x1             = (isset($request->x1) &&  $request->x1 != '') ? $request->x1 : 0;
            $y1                 = (isset($request->y1) &&  $request->y1 != '') ? $request->y1 : 0; //functions::clean_string($_REQUEST["y1"]);
            $x2                 = (isset($request->x2) &&  $request->x2 != '') ? $request->x2 : 0; //functions::clean_string($_REQUEST["x2"]);
            $y2                 = (isset($request->y2) &&  $request->y2 != '') ? $request->y2 : 0; //functions::clean_string($_REQUEST["y2"]);
            $width      = (isset($request->w) &&  $request->w != '') ? $request->w : 0; //functions::clean_string($_REQUEST["w"]);
            $height     = (isset($request->h) &&  $request->h != '') ? $request->h : 0; //functions::clean_string($_REQUEST["h"]);


         /*   $file = $request->data_url;

            if(!empty($file)) {
      $destinationPath = storage_path().'/app/public/'.$folder.'/'; 

      $file = str_replace('data:image/png;base64,', '', $file);
      $img = str_replace(' ', '+', $file);
      $data = base64_decode($img);
      //$filename = date('ymdhis') . '_croppedImage' . ".png";
      $filename = 'thumb_abc' . ".png";
      $file = $destinationPath . $filename;
      $success = file_put_contents($file, $data);

      // THEN RESIZE IT
      $returnData = '/storage/app/public/'.$folder.'/'. $filename;
      $image = Image::make(file_get_contents(URL::asset($returnData)));
      $image = $image->resize($width,$height)->save($destinationPath . $filename);

         
    }  */

            

            //$width      = (isset($request->width_a) &&  $request->width_a != '') ? $request->width_a : 0; //functions::clean_string($_REQUEST["w"]);
            //$height     = (isset($request->height_a) &&  $request->height_a != '') ? $request->height_a : 0;



           $thumbImage     = new ThumbImage($dir_file.$image_class->image_name);
           $thumbImage->thumb_width = $thumb_width; 
           $thumbImage->thumb_height = $thumb_height; 

            // $cropped        = $thumbImage->cropThumbImage($dir_file.'thumb_'.$image_class->image_name, $dir_file.$image_class->image_name, $width, $height, $x1, $y1);

            //  $x1 = floor($x1);
            // $y1 = floor($y1);

            // $width = floor($width);
            // $height = floor($height);

            $img = Image::make($dir_file.$image_class->image_name);

            /* $background = Image::canvas($width, $height);
            $background->fill('#000');

            $background->insert($img, 'top-right', -intval($x1), -intval($y1));
            $background->resize($thumb_width, $thumb_height)->save($dir_file.'thumb_'.$image_class->image_name, 95);

            $background = Image::canvas($thumb_width, $thumb_height);

            $image = $img->resize($thumb_width, $thumb_height, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });

            $img->resizeCanvas($thumb_width, $thumb_height, 'center', true, array(255, 255, 255, 0));
            // insert resized image centered into background
            $background->insert($image, 'center');
            // save or do whatever you like
            $background->save($dir_file.'thumb_'.$image_class->image_name, 95); */

            // crop image
            //$img->crop($width, $height, $x1, $y1)->save($dir_file.'thumb_'.$image_class->image_name);
            //$img->crop($thumb_width, $thumb_height)->save($dir_file.'thumb_'.$image_class->image_name);

            // $img->crop($width, $height, $x1, $y1)
            // ->resize( $thumb_width, null, function ($constraint) {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // })->save($dir_file.'thumb_'.$image_class->image_name, 100);



            $image = new Imagick($dir_file.$image_class->image_name);

            // if (($srgb = file_get_contents('http://www.color.org/sRGB_IEC61966-2-1_no_black_scaling.icc')) !== false)
            // {
            //     $image->profileImage('icc', $srgb);
            //     $image->setImageColorSpace(Imagick::COLORSPACE_SRGB);
            // }

            $image->cropImage($width, $height, $x1, $y1);

            //$image->thumbnailImage($thumb_width, $thumb_height);

            list($imagewidth, $imageheight, $imageType) = getimagesize($dir_file.$image_class->image_name);
            $imageType = image_type_to_mime_type($imageType);

            switch($imageType) {
                    case "image/gif":
                        $image->setImageFormat ("gif");
                        $image->setImageCompression(Imagick::COMPRESSION_LZW);
                        break;
                    case "image/pjpeg":
                    case "image/jpeg":
                    case "image/jpg":
                        $image->setImageFormat ("jpeg");
                        $image->setImageCompression(Imagick::COMPRESSION_JPEG);
                        break;
                    case "image/png":
                    case "image/x-png":
                        $image->setImageFormat ("png");
                        $image->setImageCompression(Imagick::COMPRESSION_LZW);
                        break;
                }


            //$image->setImageColorspace(255); 
            //$image->setImageCompression(Imagick::COMPRESSION_JPEG);
            $image->setImageCompressionQuality(100); 

            //file_put_contents ("test_1.jpg", $image); // works, or:
            $image->writeImage ($dir_file.'thumb_'.$image_class->image_name); //also works


//$uri_file.'thumb_'.$image_class->image_name
            //$returnData = URL::to('/').$returnData;
            $returnData = $uri_file.'thumb_'.$image_class->image_name;

            return response()->json(['success'=>"Default status updated successfully.", 'file'=>$returnData]);
        }

       return view('admin.popup_imageCrop', compact('image_class'),
                        [
                            'id'=>$id,
                            'thumb_width'=> $thumb_width,
                            'thumb_height' => $thumb_height,
                            'folder' => $folder,
                            'from' => $from,
                            'img' => $img,
                            'dir_file' => $dir_file,
                            'uri_file' => $uri_file
                        ]
                    );
    }
    
    public function ajaxChangeStatus(Request $request) {
        $table  = $request->from; //($request->from == 'cms') ? 'content': $from;
        $data   = DB::table($table)->where([['id', $request->id ]])->first();
        $status = ($data->status == 'Y') ? 'N': 'Y';
        
        DB::table($table)
        ->where('id', $request->id)
        ->update(['status' => $status]);
        //print_r($data);
        return response()->json(['success'=>"Status updated successfully.", 'status'=>$status]);
    }

    public function ajaxChangeStatus_product(Request $request) {
        $table  = $request->from; //($request->from == 'cms') ? 'content': $from;
        $data   = DB::table($table)->where([['id', $request->id ]])->first();
        $status = ($data->status == 'Y') ? 'N': 'Y';
        
        DB::table($table)
        ->where('id', $request->id)
        ->update(['verify' => $status]);
        //print_r($data);
        return response()->json(['success'=>"Status updated successfully.", 'status'=>$status]);
    }

    public function ajaxChangeDefaultStatus(Request $request) {
        $table  = $request->from; //($request->from == 'cms') ? 'content': $from;
        $data   = DB::table($table)->where([['id', $request->id ]])->first();
        $status = ($data->default_status == 'Y') ? 'N': 'Y';

        DB::table($table)
        ->update(['default_status' => 'N']);
        
        DB::table($table)
        ->where('id', $request->id)
        ->update(['default_status' => $status]);
        //print_r($data);
        return response()->json(['success'=>"Default status updated successfully.", 'status'=>$status]);
    }

    public function updateOrder(Request $request) {
        $table  = $request->updateTable; 
        $ids    = $request->ids;
        $orderBy = $request->orderBy;

        if(strtoupper($orderBy) == 'DESC') {
           krsort($ids);
        }

        $order_id = 1;
        foreach($ids as $key => $val) {
            DB::table($table)
                ->where('id', $val)
                ->update(['order_id' => $order_id]);

            $order_id++;
        }

        return response()->json(['success'=>"Order updated successfully."]);
    }
    
    public function ajaxDataDetails(Request $request) { 
        $table  = $request->from; //($request->from == 'cms') ? 'cms': $from;
        //$data   = DB::table($table)->where([['id', $request->id ]])->first();
        //return response()->json(['success'=>"data", 'status'=>compact('data')]);
        if($request->from == 'cms') {
            $data = CmsController::ajaxDataDetails($request);
            //$input = $request->all();
            return response()->json(['success'=>'data', 'data'=>$data]);
        }
        
        if($request->from == 'banner') {
            $data = BannerController::ajaxDataDetails($request);
            //$input = $request->all();
            return response()->json(['success'=>'data', 'data'=>$data]);
        }
        
        if($request->from == 'industries') {
            $data = IndustriesController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }
        
        if($request->from == 'downloads_category') {
            $data = DownloadsCategoryController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }


        if($request->from == 'downloads') {
            $data = DownloadsController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }

        if( $request->from == 'contact-persons') {
            $data = ContactPersonsController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }

        if( $request->from == 'feedbacks') {
            $data = FeedbacksController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }

        if( $request->from == 'references') {
            $data = ReferencesController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }

        if( $request->from == 'history') {
            $data = HistoryController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }

        if( $request->from == 'cooperations') {
            $data = CooperationsController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }

        if( $request->from == 'category') {
            $data = CategoryController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }

        if( $request->from == 'products') {
            $data = ProductController::ajaxDataDetails($request);
            return response()->json(['success'=>'data', 'data'=>$data]);
        }
        
    }

     public function ajaxgetAllCategory(Request $request) {
        
            $categories = Category::all();
            $users = array();
            $res = array('error' => false);
            foreach($categories as $values){
                    array_push($users, $values);
                }
                $res['users'] = $users;
                header("Content-type: application/json");
                echo json_encode($res);
     }

    public function productimagegallery(Request $request) {
        $rules = array(
          'image_name' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=100,min_height=300',
        );
       $validator = Validator::make(Input::all(), $rules);
        if ($validator->fails()) {
            echo '0';
            }
        else{
            $imageName = time().$request->image_name->getClientOriginalName();
            $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
            $path =  storage_path();
            $img_path = $request->image_name->storeAs('public/product_gallery', $imageName);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777);

             $image = $request->file('image_name');
            //$input['imagename'] = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = storage_path().('/app/public/product_gallery/thumbnail');
            $img = Image::make($image->getRealPath());
            $img->resize(100, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath.'/'.$imageName);

            $product_image = new Product_image;
            $product_image->image_name = $imageName;
            $product_image->product_id = $request->product_id;
            $product_image->title = $request->img_title;
            $product_image->save();
            echo '1';
            }
      }
      public function get_productimagegallery(Request $request) {
        
              $product_image = DB::table('product_image')
             ->where('product_id', [$request->product_id])
            ->get();
                echo json_encode($product_image);
     }

      public function delete_productimagegallery(Request $request) {
            
            $product = Product_image::find($request->id);
             $path =  storage_path().'/app/public/product_image/';
            //Storage::delete($path.$banner->image_name);
            \File::delete($path.$product->image_name);
            \File::delete($path.'thumbnail/'.$product->image_name);

            deleteFiles( $path, $product->image_name);
             $product->delete();
     }

     public function searchproducts(Request $request) {
        $products =  DB::select("select * from products where `name` LIKE '".$request->search_word."%' order by id desc");
        $response = array();
       
        foreach($products as $values){
            $response[] = array("value"=>$values->name,"label"=>$values->name);
            }
            echo json_encode($response);
       
     }

     
     public function searchbrand_category(Request $request) {

        $products =  DB::select("select category_id,category_name from products where `brand_id`='".$request->brand_id."' group by category_id order by id desc");
        $response = array();
    // echo "select * from products where `brand_id`='".$request->brand_id."' group by category_id order by id desc";

    echo json_encode($products);
         //   echo json_encode($response);
       
     }
         
     public function addquote(Request $request) {

        $data   = DB::table("products")->where([['id', $request->product_id]])->first();
        $user = auth()->user();

            $quote = new Quote;
            $quote->product_id = $request->product_id;
            $quote->product_name = $data->name;
            $quote->product_slug = $data->slug;

            $quote->user_id = $user->id;
            $quote->user_name = $user->name;
            $quote->user_email = $user->email;

            $quote->quote_id = $request->img_title;
            $quote->save();
          
            $quote_update = Quote::find($quote->id);
             $randno=substr($data->slug,0,5).$quote->id;
            $quote_update->quote_id=$randno;
            $quote_update->save();

     }

     public function get_product_company(Request $request) {
        $products =  DB::select("select * from products where `company_id`='".$request->company_id."' order by id desc");
        $response = array();
        echo json_encode($products);
         //   echo json_encode($response);
        }
    
    public function add_contact_person(Request $request) {
      
        if($request->image_name1!='')
        {
        $imageName1 = time().$request->image_name1->getClientOriginalName();
        $imageName1 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);
        $path =  storage_path();
        $img_path = $request->image_name1->storeAs('public/contact', $imageName1);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName1="";
        }

        if($request->image_name2!='')
        {
        $imageName2 = time().$request->image_name2->getClientOriginalName();
        $imageName2 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName2);
        $path =  storage_path();
        $img_path = $request->image_name2->storeAs('public/contact', $imageName2);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName2="";
        }

      
        $contact_person = new Contact_person;
        $contact_person->name = $request->name;
        $contact_person->email = $request->email;
        $contact_person->phone = $request->phone;

        $contact_person->image_name1 = $imageName1;
        $contact_person->image_name2 = $imageName2;
        $contact_person->remark = $request->remark;

        $contact_person->title = $request->title;
        $contact_person->mobile = $request->mobile;
        $contact_person->whatsapp = $request->whatsapp;
        $contact_person->last_name = $request->last_name;
        $contact_person->contact_type = $request->contact_type;
        
        $contact_person->department = $request->department;
        $contact_person->designation = $request->designation;
        $contact_person->user_id = $request->user_id;
        //$contact_person->password =  Hash::make($request->password);

        $admin_id = session('ADMIN_ID');
        if($admin_id>0)
        {
            $admin = DB::table('admin')->where([
                ['id', $admin_id]
            ])
            ->first();
            $contact_person->added_by = $admin->surname; 
            $contact_person->updated_by = $admin->surname;    
        }
        else{
            $staff_id = session('STAFF_ID');
            $staff = DB::table('staff')->where([
                ['id', $staff_id]
            ])
            ->first();
            $contact_person->added_by = $staff->name; 
            $contact_person->updated_by = $staff->name;  
        }
      
        $contact_person->save();

        }
        public function contactformedit(Request $request) {

          
     
        $current1 = $request->current_image1;
        $current2 = $request->current_image2;

        if(isset($request->image_name1)) {
            $imageName1 = time().$request->image_name1->getClientOriginalName();
            $imageName1 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);
            $path =  storage_path();
            $img_path = $request->image_name1->storeAs('public/contact', $imageName1);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777);
          

            $path =  storage_path().'/app/public/contact/';
           
            \File::delete($path.$current1);
          
         } else {
            $imageName1 = $current1;
         }


         if(isset($request->image_name2)) {
            $imageName2 = time().$request->image_name2->getClientOriginalName();
            $imageName2 =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName2);
            $path =  storage_path();
            $img_path = $request->image_name2->storeAs('public/contact', $imageName2);
            $path = $path.'/app/'.$img_path;
            chmod($path, 0777);
          

            $path =  storage_path().'/app/public/contact/';
           
            \File::delete($path.$current2);
         
         } else {
            $imageName2 = $current2;
         }

         $admin_id = session('ADMIN_ID');
        if($admin_id>0)
        {
            $admin = DB::table('admin')->where([
                ['id', $admin_id]
            ])
            ->first();
             $updated_by = $admin->surname;    
        }
        else{
            $staff_id = session('STAFF_ID');
            $staff = DB::table('staff')->where([
                ['id', $staff_id]
            ])
            ->first();
            $updated_by = $staff->name;  
        }

        $staff_id = session('STAFF_ID');   
         $user_list= DB::select('select * from assign_supervisor where  `supervisor_id`="'.$staff_id.'" ');
         if(count($user_list)>0)
         {
           
           $approved_by_id = $staff_id;
         }else{
            $approved_by_id = 0;
         }




         $update= DB::table('contact_person')->where('id',$request->contact_id)->update(['approved_by_id' => $approved_by_id,'updated_by' => $updated_by,'title' => $request->title,'status' => $request->status,'mobile' => $request->mobile,'whatsapp' => $request->whatsapp,'last_name' => $request->last_name,'contact_type' => $request->contact_type,'image_name1' => $imageName1,'image_name2' => $imageName2,'remark' => $request->remark,'name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'department' => $request->department, 'designation' => $request->designation]);
           /* exit;
            echo $id=$request->contact_id;
            $contact_persons = Contact_person::find($id);
            
            $contact_person->name = $request->name;
            $contact_person->email = $request->email;
            $contact_person->phone = $request->phone;
    
            $contact_person->department = $request->department;
            $contact_person->designation = $request->designation;
            $contact_person->user_id = $request->user_id;
          //  $contact_person->password =  Hash::make($request->password);
          
            $contact_person->save();*/
    
            }

            public function get_product_all_details(Request $request) {
                $products =  DB::select("select * from products where `id`='".$request->product_id."' order by id desc");
                $response = array();
                echo json_encode($products);
            }

            public function get_multiple_product_all_details(Request $request) {
                $products =  Product::whereIn('id',$request->product_id)->orderBy('id','desc')->get();//DB::select("select * from products where `id`='".$request->product_id."' order by id desc");
                $response = array();
                echo json_encode($products);
            }

            public function change_country(Request $request) {
                $state =  DB::select("select * from state where `country_id`='".$request->country_id."' order by id desc");
                $response = array();
                echo json_encode($state);
            }
        
            public function change_state(Request $request) {
                $district =  DB::select("select * from district where `state_id`='".$request->state_id."' order by name asc");
                $response = array();
                echo json_encode($district);
            }
        
            public function change_district(Request $request) {
                $state =  DB::select("select * from taluk where `district_id`='".$request->district_id."' order by name asc");
                $response = array();
                echo json_encode($state);
            }


            public function change_assigned_team(Request $request) {
                $state =  DB::select("select * from staff where `designation_id`='".$request->assigned_team."' order by id desc");
                $response = array();
                echo json_encode($state);
            }
        
            public function change_related_to(Request $request) {
                $state =  DB::select("select * from relatedto_subcategory where `relatedto_category_id`='".$request->related_to."' order by id desc");
                $response = array();
                echo json_encode($state);
            }
        
            public function change_task_status(Request $request) {
                $task = Task::find($request->id);
                if($request->status=="In Progress"){
                    $task->staff_status=$request->status;
                }
                $task->status=$request->status;
                $task->save();
            }

            public function change_task_status_total_task(Request $request) {

            //     $alltask = DB::table('task_comment')
            //     ->where('task_id', [$request->id])
            //    ->get();
               $alltask =  DB::select("select * from task_comment where `task_id`='".$request->id."'  order by id desc");
              // echo "select * from task_comment where `task_id`='".$request->id."' order by id desc";
                $count=0;
               foreach($alltask as $values)
               {
                   if($values->status=="N")
                   {
                    $count=$count+1;
                   }
               }
               if($count>0)
               {
               
                echo $count;
               }
               else{
                $task = Task::find($request->id);
                $task->staff_status=$request->status;
                $task->status=$request->status;
                $task->save();
                echo $count;
               }
                
            }

        
            public function change_task_priority(Request $request) {
                $task = Task::find($request->id);
                $task->priority=$request->status;
                $task->save();
            }

            
    public function view_task_details(Request $request) {
        $task = DB::table('task')
    ->where('id', [$request->id])
   ->get();

   if($task[0]->user_id>0)
    {
        $contact_person = DB::table('contact_person')
        ->where('user_id', [$task[0]->user_id])
    ->get();
    }
    else{
        $contact_person =array();
    }
  

   $alltask=array();
   foreach($task as $item) {
    $alltask[]=$item->name;
    $alltask[]=$item->description;
    $alltask[]=$item->created_at;
    $alltask[]=$item->start_date;
    $alltask[]=$item->due_date;
    $alltask[]=$item->priority;
   
    $alltask_name='';
    if($item->assigns>0){
        $staff_names=explode(",",$item->assigns);
        foreach($staff_names as $val_staff) {
            $staff = Staff::find($val_staff);
            $alltask_name .=$staff->name.',';
        }
        
    }

    $follower = Staff::find($item->followers);
    $alltask[]=substr($alltask_name, 0, -1);
    $alltask[]=$follower->name;
    $alltask[]=$item->user_id;
   }
   //print_r($alltask);
   $staff_id = session('STAFF_ID');
   $travel = DB::select("select * from dailyclosing_expence where travel_task_id='".$request->id."'  and expence_cat='travel' ");
   $expence = DB::select("select * from dailyclosing_expence where travel_task_id='".$request->id."'  and expence_cat='expence' ");
   $day_expence = DB::select("select * from dailyclosing_expence where travel_task_id='".$request->id."'   ");
   echo json_encode($alltask).'*'.json_encode($contact_person).'*'.json_encode($travel).'*'.json_encode($expence).'*'.json_encode($day_expence);
    }

    public function add_task_comment(Request $request) {
        
        $task_comment = new Task_comment;
        $cur_date=date('Y-m-d');
        if($request->image_name!='')
        {
        $imageName = time().$request->image_name->getClientOriginalName();
        $imageName =  preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);
        $path =  storage_path();
        $img_path = $request->image_name->storeAs('public/comment', $imageName);
        $path = $path.'/app/'.$img_path;
        chmod($path, 0777);
        }else{
            $imageName="";
        }

        $task_comment->call_status = $request->call_status;
        $task_comment->email = $request->email_status;
        $task_comment->visit = $request->visit_status;
        $task_comment->contact_id = $request->contact_id;
        if($request->contact_id!='')
        {
            $contact_names=explode(",",$request->contact_id);
            $contact_person_name='';
            foreach($contact_names as $valcon)
            {
                if($valcon>0){
                    $contact_person = Contact_person::find($valcon);
                    $contact_person_name .=$contact_person->name.',';
                }
            }
        }
        else{
            $contact_person_name='';
        }
       
        
        $task_comment->contact_name = substr($contact_person_name, 0, -1);
        
        $task_comment->comment = $request->comment;
        $task_comment->task_id = $request->task_id;
        $task_comment->image_name = $imageName;
        $staff_id = session('STAFF_ID');
       
        $task_comment->added_by_id = $staff_id;

        $task = Task::find($request->task_id);
        if($staff_id==$task->followers)
        {
            $task->staff_status ="Pending";
            $task_comment->added_by = 'admin';
        }
        else{
            $task->status ="Pending";  
            $task_comment->added_by = 'staff';


            /*****************************************/
            
            $task_exit= DB::select("select * from dailyclosing where task_id='".$request->task_id."' AND start_date='".$cur_date."'  AND assigns='".$staff_id."'");
            if(count($task_exit)==0)
            {
                            $dayclose = new Dailyclosing;
                            $dayclose->task_id = $request->task_id;
                            $dayclose->name = $task->name;
                            $dayclose->company_id = $task->company_id;
                            $dayclose->assigned_team = $task->assigned_team;
                            $dayclose->followers = $task->followers;
                            $dayclose->related_to = $task->related_to;
                            $dayclose->related_to_sub = $task->related_to_sub;
                            $dayclose->user_id = $task->user_id;
                            $dayclose->start_date = $cur_date;
                            $dayclose->due_date = $task->due_date;
                            $dayclose->priority = $task->priority;
                            $dayclose->repeat_every = $task->repeat_every;
                            $dayclose->cycles = $task->cycles;
                            $dayclose->unlimited_cycles = $task->unlimited_cycles;
                            $dayclose->custom_days = $task->custom_days;
                            $dayclose->custom_type = $task->custom_type;
                            $dayclose->description = $task->description;
                            $dayclose->assigns=$staff_id;
                            $dayclose->check_list_id =$task->check_list_id;
                            $dayclose->freq_hour = $task->freq_hour;
                            $dayclose->created_by_name=$task->created_by_name;
                            $dayclose->created_by_id=$task->created_by_id;
                            $dayclose->amount = $task->amount;
                            $dayclose->start_time = $task->start_time;
                            $dayclose->task_create_type = "auto";
                            $dayclose->save();
                            
                   
    
                }
                

                  
            
                /*****************************************/




        }

        $update= DB::table('dailyclosing_details')->where('staff_id',$staff_id)->where('start_date',$cur_date)->update(['approved_fair' => 'Pending','approved_work' => 'Pending']);


        $task_comment->save();
        $task->save();



    }

    
    public function view_task_comment(Request $request) {
        $task = DB::table('task_comment')
                ->where('task_id', [$request->task_id])
            ->get();
   $task_replay =  DB::select("select * from task_comment_replay where `task_id`='".$request->task_id."' order by id asc");
   $task_details = DB::table('task')
   ->where('id', [$request->task_id])
->get(); 
   //print_r($alltask);
   // echo json_encode($task);
      echo json_encode($task).'*'.json_encode($task_replay).'*'.json_encode($task_details);
    }

    public function view_task_comment_dailytask(Request $request) {
        $staff_id = session('STAFF_ID');
        $dailyclosing = DB::table('dailyclosing')
        ->where('start_date', [$request->start_date])
        ->where('assigns', [$staff_id])
         ->get();
    $i=0;
    $task=array();
    $task_replay=array();
    $k=0;
    foreach($dailyclosing as $values)
    {
       /* $task_data = DB::table('task_comment')
        ->where('task_id', [$values->task_id])
        ->get();*/
       // echo "select * from task_comment where created_at like '".$request->start_date."%' AND task_id='".$values->task_id."'   order by id asc";
        $task_data =  DB::select("select * from task_comment where created_at like '".$request->start_date."%' AND task_id='".$values->task_id."'   order by id asc");
 
        
        if(count($task_data)>0)
        {
        foreach($task_data as $task_com_val){
           
            $task_det = Task::find($task_com_val->task_id);
            $task[$i]["added_by"]=$task_com_val->added_by;
            $task[$i]["comment"]=$task_com_val->comment;
            $task[$i]["image_name"]=$task_com_val->image_name;
            $task[$i]["contact_name"]=$task_com_val->contact_name;
            $task[$i]["created_at"]=$task_com_val->created_at;
            $task[$i]["email"]=$task_com_val->email;
            $task[$i]["call_status"]=$task_com_val->call_status;
            $task[$i]["visit"]=$task_com_val->visit;
            $task[$i]["status"]=$task_com_val->status;
            $task[$i]["id"]=$task_com_val->id;
            $task[$i]["task_name"]=$task_det->name;
            if($task_com_val->added_by=="staff")
            {
                $staff_det = Staff::find($task_com_val->added_by_id);
                $task[$i]["added_by_name"]=$staff_det->name;
            }else{
                $task[$i]["added_by_name"]='admin';
            }
           

            $task_replays=DB::select("select * from task_comment_replay where `task_comment_id`='".$task_com_val->id."' order by id asc");
            if(count( $task_replays)>0)
            {
                foreach($task_replays as $task_rep_val){
                $rowval=$k;
                $task_replay[$rowval]["comment"]=$task_rep_val->comment;
                $task_replay[$rowval]["created_at"]=$task_rep_val->created_at;
                $task_replay[$rowval]["task_comment_id"]=$task_rep_val->task_comment_id;
                $task_replay[$rowval]["added_by"]=$task_rep_val->added_by;
                if($task_rep_val->added_by=="staff")
                {
                    $staff_det = Staff::find($task_rep_val->added_by_id);
                    $task_replay[$rowval]["added_by_name"]=$staff_det->name;
                }else{
                    $task_replay[$rowval]["added_by_name"]='admin';
                }

                    $k++;
                }
                
            }
            $i++;
        }
        }
        
       
       

        $task_details[] = DB::table('task')
        ->where('id', [$values->task_id])
            ->get(); 
     
    }
    $staff_id = session('STAFF_ID');
    $expencedetails = DB::table('dailyclosing_details')
    ->where('start_date', [$request->start_date])
    ->where('staff_id', [$staff_id])
     ->get();
    
     $fair=array();
     if(count($expencedetails)>0)
     {
        foreach($expencedetails as $expence_val)
        {
        $p=0;
             $fair_data = DB::table('dailyclosing_expence')
            ->where('dailyclosing_details_id', [$expence_val->id])
             ->get();
             if(count($fair_data)>0)
            {
             foreach($fair_data as $expence)
             {
             $fair[$p]["expence_type"]=$expence->expence_type;
             $fair[$p]["fair"]=$expence->fair;
             $p++;
             }
            }
        }
     }

     $approval_comment=DB::select("select * from dailyclosing_comment where `staff_id`='".$staff_id."' AND start_date='".$request->start_date."' order by updated_at desc");
     

    //  print_r($task);
    //  echo '---';
   //  print_r($task_replay);

   $travel = DB::select("select * from dailyclosing_expence where staff_id='".$staff_id."' and start_date='".$request->start_date."' and expence_cat='travel' ");
   $expence = DB::select("select * from dailyclosing_expence where staff_id='".$staff_id."' and start_date='".$request->start_date."' and expence_cat='expence' ");
   
 echo json_encode($task).'*'.json_encode($task_replay).'*'.json_encode($task_details).'*'.json_encode($expencedetails).'*'.json_encode($fair).'*'.json_encode($approval_comment).'*'.json_encode($travel).'*'.json_encode($expence);
    }


    

    
    public function view_task_commentall(Request $request) {

        $cur_date=date('Y-m-d');
        $staff_id = session('STAFF_ID');
        $task =  DB::select("select * from task where start_date='".$cur_date."' AND (FIND_IN_SET('".$staff_id."', assigns) OR  `created_by_id`='".$staff_id."' )");

        // $task = DB::table('task_comment')
        //         ->where('task_id', [$request->task_id])
        //     ->get();
   $task_replay =  DB::select("select * from task_comment_replay where `task_id`='".$request->task_id."' order by id asc");
   $task_details = DB::table('task')
   ->where('id', [$request->task_id])
->get(); 
   //print_r($alltask);
   // echo json_encode($task);
      echo json_encode($task).'*'.json_encode($task_replay).'*'.json_encode($task_details);
    }



    
    public function delete_task_comment(Request $request) {
          $task_comment = Task_comment::find($request->id);
         $task_comment->delete();
    }

    public function viewchecklist_details(Request $request) {
        $state =  DB::select("select * from  checklist where `related_subcategory_id`='".$request->related_to_sub."' order by id desc");

        
        $response = array();
        echo json_encode($state);
    }


        
    public function add_task_replay_comment(Request $request) {
        $staff_id = session('STAFF_ID');
        $cur_date=date('Y-m-d');
        $task_comment = Task_comment::find($request->task_comment_id);
        $task_comment->status = $request->status;
        $task_comment->save();
        $task_comment_replay = new Task_comment_replay;
        $task_comment_replay->task_id = $request->task_id;
        $task_comment_replay->comment = $request->replay_comment;
        $task_comment_replay->task_comment_id = $request->task_comment_id;
        $task_comment_replay->parent_id = $request->parent_id;
       
        $task_comment_replay->added_by_id = $staff_id;
        

        if($request->parent_id>0){
            $task_comment_replay_update = Task_comment_replay::find($request->parent_id);
            $task_comment_replay_update->replay_status ="Y";
            $task_comment_replay_update->save();
            }

            $task = Task::find($request->task_id);

            if($staff_id==$task->followers)
            {
                if($request->status=="Y")
                {
                    $task->status ="In Progress";
                    $task->staff_status ="In Progress";
                    $task_comment_replay->added_by = 'admin';
                }
                else{
                    $task->status ="In Progress";
                    $task->staff_status ="Pending";
                    $task_comment_replay->added_by = 'admin';
                }
               
            }
            else{
                $task->staff_status = "In Progress";
                $task->status = "Pending";
                $task_comment_replay->added_by = 'staff';
            }

                // $task->staff_status = "In Progress";
                // $task->status = "Pending";
            $task->save();
            $task_comment_replay->save();
            $update= DB::table('dailyclosing_details')->where('staff_id',$staff_id)->where('start_date',$cur_date)->update(['approved_fair' => 'Pending','approved_work' => 'Pending']);

            $update= DB::table('task_comment')->where('task_id',$request->task_id)->update(['admin_view' => "Y"]);
    }


    public function change_repeat_every(Request $request) {
        $repeat_type=$request->repeat_type;
        $start_date=$request->start_date;
        $start_time=$request->start_time;
        $custom_type=$request->custom_type;
        $custom_days=$request->custom_days;
        $unlimited_cycles=$request->unlimited_cycles;
        $days_book=array();
        if($request->cycles>0)
        {
         $count=$request->cycles;
        }
        else{
            if($request->cycles==0  && $unlimited_cycles==0)
            {
                $count=0;
            }else{
                $count=5;    
            }
        }
       
        
        if($repeat_type=="Days")
        {
         $due_date=$request->start_date;
         for($i=1;$i<=$count;$i++)
         {
          $add=$i.' day';
          $start_date_time=$start_date.' '.$start_time;
          $days_book[] =  date("Y-m-d H:i:s",strtotime("+".$add,strtotime($start_date_time)));
         }
 
        }
 
        if($repeat_type=="Week")
        {
        $due_date=date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +1 week"));
        for($i=1;$i<=$count;$i++)
         {
           $add=$i.' week';
           $start_date_time=$start_date.' '.$start_time;
          $days_book[] =  date("Y-m-d H:i:s",strtotime("+".$add,strtotime($start_date_time)));
         }
 
        }
 
        if($repeat_type=="2weeks")
        {
         $due_date=date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +2 week"));
         $start_date_time=$start_date.' '.$start_time;
         for($i=1;$i<=$count;$i++)
         {
           
           if($i==1)
           {
             $days_book[] =  date("Y-m-d H:i:s",strtotime(" +2 week",strtotime($start_date_time)));
             $add_date=date("Y-m-d H:i:s",strtotime(" +2 week",strtotime($start_date_time)));
           }  
           else{
             $days_book[] =  date("Y-m-d H:i:s",strtotime(" +2 week",strtotime($add_date)));
             $add_date=date("Y-m-d H:i:s",strtotime(" +2 week",strtotime($add_date)));
           }
         
          
         }
         
        }
 
        if($repeat_type=="1Month")
        {
         $due_date=date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +1 month"));
         $start_date_time=$start_date.' '.$start_time;
         for($i=1;$i<=$count;$i++)
         {
           $add=$i.' month';
          $days_book[] =  date("Y-m-d H:i:s",strtotime("+".$add,strtotime($start_date_time)));
         }
         
        }
 
        if($repeat_type=="2Month")
        {
         $due_date=date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +2 month"));
         $start_date_time=$start_date.' '.$start_time;
         for($i=1;$i<=$count;$i++)
         {
           
           if($i==1)
           {
             $days_book[] =  date("Y-m-d H:i:s",strtotime(" +2 month",strtotime($start_date_time)));
             $add_date=date("Y-m-d H:i:s",strtotime(" +2 month",strtotime($start_date_time)));
           }  
           else{
             $days_book[] =  date("Y-m-d H:i:s",strtotime(" +2 month",strtotime($add_date)));
             $add_date=date("Y-m-d H:i:s",strtotime(" +2 month",strtotime($add_date)));
           }
         
          
         }
 
        }
 
        if($repeat_type=="3Month")
        {
         $start_date_time=$start_date.' '.$start_time;  
         $due_date=date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +3 month"));
         for($i=1;$i<=$count;$i++)
         {
           
           if($i==1)
           {
             $days_book[] =  date("Y-m-d H:i:s",strtotime(" +3 month",strtotime($start_date_time)));
             $add_date=date("Y-m-d H:i:s",strtotime(" +3 month",strtotime($start_date_time)));
           }  
           else{
             $days_book[] =  date("Y-m-d H:i:s",strtotime(" +3 month",strtotime($add_date)));
             $add_date=date("Y-m-d H:i:s",strtotime(" +3 month",strtotime($add_date)));
           }
         
          
         }
        }
 
        if($repeat_type=="6Month")
        {
         $due_date=date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +6 month"));
         $start_date_time=$start_date.' '.$start_time;  
         for($i=1;$i<=$count;$i++)
         {
           
           if($i==1)
           {
             $days_book[] =  date("Y-m-d H:i:s",strtotime(" +6 month",strtotime($start_date_time)));
             $add_date=date("Y-m-d",strtotime(" +6 month",strtotime($start_date_time)));
           }  
           else{
             $days_book[] =  date("Y-m-d H:i:s",strtotime(" +6 month",strtotime($add_date)));
             $add_date=date("Y-m-d H:i:s",strtotime(" +6 month",strtotime($add_date)));
           }
         
          
         }
 
        }
 
        if($repeat_type=="1year")
        {$start_date_time=$start_date.' '.$start_time; 
         $due_date=date('Y-m-d', strtotime('+1 year', strtotime($start_date)) );
         for($i=1;$i<=$count;$i++)
         {
           $add=$i.' year';
          $days_book[] =  date("Y-m-d H:i:s",strtotime("+".$add,strtotime($start_date_time)));
         }
         
 
        }
 
        if($repeat_type=="Custom" && $custom_days>0)
        {
 
    
         if($custom_type=="Weeks")
         {
           $week_hour=24*7;
           $add_hour= $week_hour / $custom_days;
           $start_date_time=$start_date.' '.$start_time;
           $add=$add_hour.' hours';
           $due_date=date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +1 week"));  
          
         for($j=1;$j<=$count;$j++)
         {
             
         for($i=1;$i<=$custom_days;$i++)
             {
                 if(empty($days_book)) 
                 {
                     $add_date=date("Y-m-d H:i:s", strtotime('+'.$add, strtotime($start_date_time))); 
                     $days_book[]=$add_date;
                 }
                 else{
                     $days_book[] =  date("Y-m-d H:i:s", strtotime('+'.$add, strtotime($add_date)));
             $add_date=date("Y-m-d H:i:s", strtotime('+'.$add, strtotime($add_date)));
                 }
            
             }
         }
 
      
         }
 
 
         if($custom_type=="Months")
         {
           $due_date=date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +1 month"));
           $total_days=cal_days_in_month(CAL_GREGORIAN, date("m",strtotime($start_date)), date("Y",strtotime($start_date)));
           $week_hour=24*$total_days;
           $add_hour= $week_hour / $custom_days;
           $start_date_time=$start_date.' '.$start_time;
           $add=$add_hour.' hours';
          
         for($j=1;$j<=$count;$j++)
         {
             
          for($i=1;$i<=$custom_days;$i++)
             {
                 if(empty($days_book)) 
                 {
                     $add_date=date("Y-m-d H:i:s", strtotime('+'.$add, strtotime($start_date_time)));
                     $days_book[]=$add_date;
                 }else{
                     $days_book[] =  date("Y-m-d H:i:s", strtotime('+'.$add, strtotime($add_date)));
                     $add_date=date("Y-m-d H:i:s", strtotime('+'.$add, strtotime($add_date)));
                 }
               
            
             }
         }
 
      
         }
 
 
         if($custom_type=="Years")
         {
           $due_date=date('Y-m-d', strtotime('+1 year', strtotime($start_date)) );  
           $leap= (date('L', mktime(0, 0, 0, 1, 1, date("Y",strtotime($start_date))))==1);
             if($leap=="1")
             { $total_days=365;
             }else{$total_days=366;}
           $week_hour=24*$total_days;
           $add_hour= $week_hour / $custom_days;
           $start_date_time=$start_date.' '.$start_time;
           $add=$add_hour.' hours';
          
         for($j=1;$j<=$count;$j++)
         {
             
          for($i=1;$i<=$custom_days;$i++)
             {
                 if(empty($days_book)) 
                 {
                     $add_date=date("Y-m-d H:i:s", strtotime('+'.$add, strtotime($start_date_time)));
                     $days_book[]=$add_date;
                 }
                 else{
                     $days_book[] =  date("Y-m-d H:i:s", strtotime('+'.$add, strtotime($add_date)));
                     $add_date=date("Y-m-d H:i:s", strtotime('+'.$add, strtotime($add_date)));  
                 }
                  
               
             }
         }
 
      
         }
 
 
 
        }
 
 
        $days_book_check=array();
        if($request->infinity_end_date!='')
        {
             foreach($days_book as $val)
             {
                 $dates=date("Y-m-d",strtotime($val));
                 if(strtotime($request->infinity_end_date)<strtotime($dates))
                 {
                     $days_book_check[]=$val;
                 }
                
             }
             $total_val_arr=array_diff($days_book, $days_book_check);
             echo $due_date.'*'.json_encode($total_val_arr);
        }else{
         echo $due_date.'*'.json_encode($days_book);
        }
       // print_r($days_book);
       //echo $due_date.'*'.json_encode($days_book);
 
        
 
     }


     public function get_client_use_state_district(Request $request)
     {
      $users =  DB::select("select business_name,id from users where `state_id`='".$request->state_id."' AND `district_id`='".$request->district_id."' ");
      echo json_encode($users);
      
     }

     
public function change_assignes(Request $request) {

    $i=0;
    $sql='';
    $search_cond_array=array();
    foreach($request->assigns as $val)
    {
        $search_cond_array[]    = "id!='".$val."'";
        
        $i++;
    }
    if(count($search_cond_array)>0)
    
                {
    
                    $search_condition	= " WHERE ".join(" AND ",$search_cond_array);
    
                    $sql				.= $search_condition;
    
                }
           //     echo $sql;exit;
    
    $state =  DB::select("select * from staff ".$sql." ");
    $response = array();
    echo json_encode($state);
    }
    
 
public function get_contact_details(Request $request)
{
 $users =  DB::select("select * from contact_person where `id`='".$request->contact_id."'  ");
 echo json_encode($users);
 
}    


public function get_user_contact_list(Request $request)
{
 $users =  DB::select("select * from contact_person where `user_id`='".$request->user_id."'  ");
 echo json_encode($users);
 
}

public function save_first_responce(Request $request)
{
    
    if($request->service_responce_id>0)
    {
       
        $service_responce = Service_responce::find($request->service_responce_id);
        $service_responce->response_date = $request->resp_date;
     
        $service_responce->responce = $request->resp_responce;
        $service_responce->status = $request->resp_status;
        $service_responce->action_plan = $request->resp_action_plan;
        $service_responce->schedule_date = $request->resp_schedule_date;
        $service_responce->schedule_time = $request->resp_schedule_time;
        $service_responce->contact_id = $request->resp_contact_id_val;
        $service_responce->contact_no = $request->resp_contact_no_val;
        $service_responce->service_task_id = $request->resp_service_task_id;
        $service_responce->save();
    }
    else{
        $service_responce = new Service_responce;

            $service_responce->response_date = $request->resp_date;
          
            $service_responce->responce = $request->resp_responce;
            $service_responce->status = $request->resp_status;
            $service_responce->action_plan = $request->resp_action_plan;
            $service_responce->schedule_date = $request->resp_schedule_date;
            $service_responce->schedule_time = $request->resp_schedule_time;
            $service_responce->contact_id = $request->resp_contact_id_val;
            $service_responce->contact_no = $request->resp_contact_no_val;
            $service_responce->service_task_id = $request->resp_service_task_id;
            $service_responce->save();
    }
    
    $service_responce =  DB::select("select * from service_responce where `service_task_id`='".$request->resp_service_task_id."' order by schedule_date desc ");
    $service_visit =  DB::select("select * from service_visit where `service_task_id`='".$request->resp_service_task_id."'  order by travel_start_time desc");
    $service_part =  DB::select("select * from service_part where `service_task_id`='".$request->resp_service_task_id."' order by intened_date desc ");
   
    echo json_encode($service_responce).'*'.json_encode($service_visit).'*'.json_encode($service_part);

}

public function save_visit(Request $request)
{
    
    if($request->service_visit_id>0)
    {
        $service_visit = Service_visit::find($request->service_visit_id);
        $service_visit->travel_start_time = $request->travel_start_time;
        $service_visit->travel_end_time = $request->travel_end_time;
        $service_visit->work_start = $request->work_start;
        $service_visit->work_end = $request->work_end;
        $service_visit->observed_prob = $request->observed_prob;
        $service_visit->action_taken = $request->visit_action_taken;
        $service_visit->status = $request->visit_status;
        $service_visit->action_plan = $request->visit_action_plan;
        $service_visit->return_travel_start_time = $request->return_travel_start_time;
        $service_visit->return_travel_end_time = $request->return_travel_end_time;
        $service_visit->test_status = $request->test_status;
        $service_visit->save();
    }
    else{
            $service_visit = new Service_visit;

            $service_visit->travel_start_time = $request->travel_start_time;
            $service_visit->travel_end_time = $request->travel_end_time;
            $service_visit->work_start = $request->work_start;
            $service_visit->work_end = $request->work_end;
            $service_visit->observed_prob = $request->observed_prob;
            $service_visit->action_taken = $request->visit_action_taken;
            $service_visit->status = $request->visit_status;
            $service_visit->action_plan = $request->visit_action_plan;
           
            $service_visit->service_task_id = $request->resp_service_task_id;
            $service_visit->return_travel_start_time = $request->return_travel_start_time;
            $service_visit->return_travel_end_time = $request->return_travel_end_time;
            $service_visit->test_status = $request->test_status;
            $service_visit->save();
    }
    
    $service_responce =  DB::select("select * from service_responce where `service_task_id`='".$request->resp_service_task_id."' order by schedule_date desc ");
    $service_visit =  DB::select("select * from service_visit where `service_task_id`='".$request->resp_service_task_id."'  order by travel_start_time desc");
    $service_part =  DB::select("select * from service_part where `service_task_id`='".$request->resp_service_task_id."' order by intened_date desc ");
   
    echo json_encode($service_responce).'*'.json_encode($service_visit).'*'.json_encode($service_part);

}



public function save_part(Request $request)
{
    
    if($request->service_part_id>0)
    {
        $service_part = Service_part::find($request->service_part_id);
        $service_part->part_no = $request->part_no;
        $service_part->description = $request->description;
        $service_part->part_description = $request->part_description;
        $service_part->intened_date = $request->intened_date;
        $service_part->expect_date = $request->expect_date;
        $service_part->reference = $request->part_reference;
        $service_part->action_plan = $request->part_action_plan;
       $service_part->service_task_id = $request->resp_service_task_id;
     
        $service_part->save();
    }
    else{
            $service_part = new Service_part;

            $service_part->part_no = $request->part_no;
            $service_part->description = $request->description;
            $service_part->part_description = $request->part_description;
            $service_part->intened_date = $request->intened_date;
            $service_part->expect_date = $request->expect_date;
            $service_part->reference = $request->part_reference;
            $service_part->action_plan = $request->part_action_plan;
           $service_part->service_task_id = $request->resp_service_task_id;
            $service_part->save();
    }
    
    $service_responce =  DB::select("select * from service_responce where `service_task_id`='".$request->resp_service_task_id."' order by schedule_date desc ");
    $service_visit =  DB::select("select * from service_visit where `service_task_id`='".$request->resp_service_task_id."'  order by travel_start_time desc");
    $service_part =  DB::select("select * from service_part where `service_task_id`='".$request->resp_service_task_id."' order by intened_date desc ");
   
    echo json_encode($service_responce).'*'.json_encode($service_visit).'*'.json_encode($service_part);


}


public function edit_part(Request $request)
{
    
    if($request->type=="search")
    {
    $brand_id=$request->brand_id;
    $category_type_id=$request->category_type_id;

  $html='';
   if($brand_id!=''){
        for($i=0;$i<count($brand_id);$i++)
         {
             if($i==0)
             {
                $html .=' AND brand_id='.$request->brand_id[$i];
             }
             else{
                $html .=' OR brand_id='.$request->brand_id[$i];  
             }
           
         }
        
    }

    if($category_type_id!=''){
        for($i=0;$i<count($category_type_id);$i++)
         {
            if($i==0)
            {
                $html .=' AND category_type_id='.$request->category_type_id[$i];
            }
            else{
                $html .=' AND category_type_id='.$request->category_type_id[$i];
            }
           
         }
        
    }
          $products_sql=DB::select("select * from products where id>0  ".$html."");

 echo json_encode($products_sql);
   
    }
    if($request->type=="product")
    {
        $data='';
        $j=0;
        //
        foreach($request->product_id as $values)
        {
        $product =  DB::select("select id,name from  products where id='".$values."'  ");
          $data .= ' <tr id="row_'.$values.'">
       <td>
        '.$product[0]->name.' <a onclick="remove_product('.$values.')">Remove</a>
         </td> 
         <input type="hidden" name="product_val[]" id="product_val" value='.$values.'>
       
            
       </tr>';
        $j++;
      }
  echo $data; 
    }

    exit;
 
}


public function edit_first_responce(Request $request)
{
 $service_responce =  DB::select("select * from service_responce where `id`='".$request->id."'  ");
 echo json_encode($service_responce);
}

public function edit_visit(Request $request)
{
 $service_visit =  DB::select("select * from service_visit where `id`='".$request->id."'  ");
 echo json_encode($service_visit);
}


          
               
     

}
