<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\admin\CmsController;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;

use Image;
use Storage;
use URL;
use App\Http\Controllers\ThumbImage;

use Imagick;
use App\Category;
use App\Product_image;
use Validator;
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
    public function get_client_use_state_district(Request $request)
    {
        $users =  DB::select("select business_name,id from users where `state_id`='".$request->state_id."' AND `district_id`='".$request->district_id."' ");
        echo json_encode($users);
    
    }

}
