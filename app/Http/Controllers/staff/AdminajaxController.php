<?php

namespace App\Http\Controllers\staff;

use App\Brand;
use App\Category;
use App\Chatter;
use App\Contact_person;
use App\CoordinatorPermission;
use App\District;
use App\Http\Controllers\admin\CmsController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ThumbImage;
use App\Models\StaffFollower;
use App\MsaContract;
use App\Msp;
use App\Oppertunity;
use App\OppertunityTask;
use App\PmDetails;
use App\Product;
use App\Product_image;
use App\Quote;
use App\Service;
use App\ServicePart;
use App\Staff;
use App\Taluk;
use App\Task;
use App\Task_comment;
use App\Task_comment_replay;
use App\User;
use App\User_permission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Image;
use Imagick;
use Input;
use URL;
use Validator;

class AdminajaxController extends Controller
{

    public function ajaxRequest()
    {

        return view('admin.ajaxRequest');

    }

    public function ajaxRequestPost(Request $request)
    {

        $input = $request->all();

        return response()->json(['success' => 'Got Simple Ajax Request.']);

    }

    public function ajaxGet()
    {

        return view('admin.ajaxRequest');

    }

    public function update_current_location(Request $request)
    {
        $staff = Staff::findOrFail(session('STAFF_ID'));
        $staff->latitude = $request->latitude;
        $staff->longitude = $request->longitude;
        $staff->located_at = Carbon::now();
        $staff->save();
    }

    public function get_user_all_details(Request $request)
    {

        $user_all = DB::select("select * from users where `id`='" . $request->user_id . "'  ");

        $contact_all = DB::select("select * from contact_person where `user_id`='" . $request->user_id . "'  ");
        $oppertunity = Oppertunity::select('id', 'oppertunity_name')->where('deal_stage', 8)->where('user_id', $request->user_id)->get();
        echo json_encode($user_all) . '*' . json_encode($contact_all) . '*' . json_encode($oppertunity);

    }

    public function ajaxPost(Request $request)
    {

        if ($request->controller == 'CMS') {

            CmsController::getAjax();

            $input = $request->all();

            return response()->json(['success' => 'Got Simple Ajax Request.']);

        }

    }

    public function ajaxDataTables(Request $request)
    {

        $user = DB::table('content')->get();

        //print_r($user);

        //exit;

        foreach ($user as $item) {

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

            "aaData" => $user2];

        return json_encode($results);

    }

    public function get_contactperson_all_details(Request $request)
    {

        $contact_person = DB::select("select * from contact_person where `id`='" . $request->id . "'  ");

        $desig = Hosdesignation::find($contact_person[0]->designation);

        echo json_encode($contact_person) . '*' . $desig->name;

    }

    public function popup_imageCrop(Request $request)
    {

        $id = (isset($request->id) && $request->id > 0) ? $request->id : 0;

        $thumb_width = (isset($request->thumb_width) && $request->thumb_width > 0) ? $request->thumb_width : 0;

        $thumb_height = (isset($request->thumb_height) && $request->thumb_height > 0) ? $request->thumb_height : 0;

        $folder = $request->folder;

        $from = $request->from;

        $img = $request->img;

        $table = $folder;

        if ($folder == 'contacts') {

            $table = 'contact_persons';

        }

        if ($folder == 'category') {

            $table = 'categories';

        }

        $image_class = $data = DB::table($table)->where([['id', $request->id]])->first();

        $dir_file = storage_path() . '/app/public/' . $folder . '/';

        $uri_file = URL::to('/') . '/storage/app/public/' . $folder . '/';

        $save = (isset($request->save) && $request->save > 0) ? $request->save : 0;

        if ($save > 0) {

            $x1 = (isset($request->x1) && $request->x1 != '') ? $request->x1 : 0;

            $y1 = (isset($request->y1) && $request->y1 != '') ? $request->y1 : 0; //functions::clean_string($_REQUEST["y1"]);

            $x2 = (isset($request->x2) && $request->x2 != '') ? $request->x2 : 0; //functions::clean_string($_REQUEST["x2"]);

            $y2 = (isset($request->y2) && $request->y2 != '') ? $request->y2 : 0; //functions::clean_string($_REQUEST["y2"]);

            $width = (isset($request->w) && $request->w != '') ? $request->w : 0; //functions::clean_string($_REQUEST["w"]);

            $height = (isset($request->h) && $request->h != '') ? $request->h : 0; //functions::clean_string($_REQUEST["h"]);

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

            $thumbImage = new ThumbImage($dir_file . $image_class->image_name);

            $thumbImage->thumb_width = $thumb_width;

            $thumbImage->thumb_height = $thumb_height;

            // $cropped        = $thumbImage->cropThumbImage($dir_file.'thumb_'.$image_class->image_name, $dir_file.$image_class->image_name, $width, $height, $x1, $y1);

            //  $x1 = floor($x1);

            // $y1 = floor($y1);

            // $width = floor($width);

            // $height = floor($height);

            $img = Image::make($dir_file . $image_class->image_name);

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

            $image = new Imagick($dir_file . $image_class->image_name);

            // if (($srgb = file_get_contents('http://www.color.org/sRGB_IEC61966-2-1_no_black_scaling.icc')) !== false)

            // {

            //     $image->profileImage('icc', $srgb);

            //     $image->setImageColorSpace(Imagick::COLORSPACE_SRGB);

            // }

            $image->cropImage($width, $height, $x1, $y1);

            //$image->thumbnailImage($thumb_width, $thumb_height);

            list($imagewidth, $imageheight, $imageType) = getimagesize($dir_file . $image_class->image_name);

            $imageType = image_type_to_mime_type($imageType);

            switch ($imageType) {

                case "image/gif":

                    $image->setImageFormat("gif");

                    $image->setImageCompression(Imagick::COMPRESSION_LZW);

                    break;

                case "image/pjpeg":

                case "image/jpeg":

                case "image/jpg":

                    $image->setImageFormat("jpeg");

                    $image->setImageCompression(Imagick::COMPRESSION_JPEG);

                    break;

                case "image/png":

                case "image/x-png":

                    $image->setImageFormat("png");

                    $image->setImageCompression(Imagick::COMPRESSION_LZW);

                    break;

            }

            //$image->setImageColorspace(255);

            //$image->setImageCompression(Imagick::COMPRESSION_JPEG);

            $image->setImageCompressionQuality(100);

            //file_put_contents ("test_1.jpg", $image); // works, or:

            $image->writeImage($dir_file . 'thumb_' . $image_class->image_name); //also works

//$uri_file.'thumb_'.$image_class->image_name

            //$returnData = URL::to('/').$returnData;

            $returnData = $uri_file . 'thumb_' . $image_class->image_name;

            return response()->json(['success' => "Default status updated successfully.", 'file' => $returnData]);

        }

        return view('admin.popup_imageCrop', compact('image_class'),

            [

                'id' => $id,

                'thumb_width' => $thumb_width,

                'thumb_height' => $thumb_height,

                'folder' => $folder,

                'from' => $from,

                'img' => $img,

                'dir_file' => $dir_file,

                'uri_file' => $uri_file,

            ]

        );

    }

    public function ajaxChangeStatus(Request $request)
    {

        $table = $request->from; //($request->from == 'cms') ? 'content': $from;

        $data = DB::table($table)->where([['id', $request->id]])->first();

        $status = ($data->status == 'Y') ? 'N' : 'Y';

        DB::table($table)

            ->where('id', $request->id)

            ->update(['status' => $status]);

        //print_r($data);

        return response()->json(['success' => "Status updated successfully.", 'status' => $status]);

    }

    public function ajaxChangeStatus_product(Request $request)
    {

        $table = $request->from; //($request->from == 'cms') ? 'content': $from;

        $data = DB::table($table)->where([['id', $request->id]])->first();

        $status = ($data->status == 'Y') ? 'N' : 'Y';

        DB::table($table)

            ->where('id', $request->id)

            ->update(['verify' => $status]);

        //print_r($data);

        return response()->json(['success' => "Status updated successfully.", 'status' => $status]);

    }

    public function ajaxChangeDefaultStatus(Request $request)
    {

        $table = $request->from; //($request->from == 'cms') ? 'content': $from;

        $data = DB::table($table)->where([['id', $request->id]])->first();

        $status = ($data->default_status == 'Y') ? 'N' : 'Y';

        DB::table($table)

            ->update(['default_status' => 'N']);

        DB::table($table)

            ->where('id', $request->id)

            ->update(['default_status' => $status]);

        //print_r($data);

        return response()->json(['success' => "Default status updated successfully.", 'status' => $status]);

    }

    public function updateOrder(Request $request)
    {

        $table = $request->updateTable;

        $ids = $request->ids;

        $orderBy = $request->orderBy;

        if (strtoupper($orderBy) == 'DESC') {

            krsort($ids);

        }

        $order_id = 1;

        foreach ($ids as $key => $val) {

            DB::table($table)

                ->where('id', $val)

                ->update(['order_id' => $order_id]);

            $order_id++;

        }

        return response()->json(['success' => "Order updated successfully."]);

    }

    public function ajaxDataDetails(Request $request)
    {

        $table = $request->from; //($request->from == 'cms') ? 'cms': $from;

        //$data   = DB::table($table)->where([['id', $request->id ]])->first();

        //return response()->json(['success'=>"data", 'status'=>compact('data')]);

        if ($request->from == 'cms') {

            $data = CmsController::ajaxDataDetails($request);

            //$input = $request->all();

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'banner') {

            $data = BannerController::ajaxDataDetails($request);

            //$input = $request->all();

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'industries') {

            $data = IndustriesController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'downloads_category') {

            $data = DownloadsCategoryController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'downloads') {

            $data = DownloadsController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'contact-persons') {

            $data = ContactPersonsController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'feedbacks') {

            $data = FeedbacksController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'references') {

            $data = ReferencesController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'history') {

            $data = HistoryController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'cooperations') {

            $data = CooperationsController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'category') {

            $data = CategoryController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

        if ($request->from == 'products') {

            $data = ProductController::ajaxDataDetails($request);

            return response()->json(['success' => 'data', 'data' => $data]);

        }

    }

    public function ajaxgetAllCategory(Request $request)
    {

        $categories = Category::all();

        $users = array();

        $res = array('error' => false);

        foreach ($categories as $values) {

            array_push($users, $values);

        }

        $res['users'] = $users;

        header("Content-type: application/json");

        echo json_encode($res);

    }

    public function productimagegallery(Request $request)
    {

        $rules = array(

            'image_name' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=100,min_height=300',

        );

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()) {

            echo '0';

        } else {

            $imageName = time() . $request->image_name->getClientOriginalName();

            $imageName = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName);

            $path = storage_path();

            $img_path = $request->image_name->storeAs('public/product_gallery', $imageName);

            $path = $path . '/app/' . $img_path;

            chmod($path, 0777);

            $image = $request->file('image_name');

            //$input['imagename'] = time().'.'.$image->getClientOriginalExtension();

            $destinationPath = storage_path() . ('/app/public/product_gallery/thumbnail');

            $img = Image::make($image->getRealPath());

            $img->resize(100, 100, function ($constraint) {

                $constraint->aspectRatio();

            })->save($destinationPath . '/' . $imageName);

            $product_image = new Product_image;

            $product_image->image_name = $imageName;

            $product_image->product_id = $request->product_id;

            $product_image->title = $request->img_title;

            $product_image->save();

            echo '1';

        }

    }

    public function get_productimagegallery(Request $request)
    {

        $product_image = DB::table('product_image')

            ->where('product_id', [$request->product_id])

            ->get();

        echo json_encode($product_image);

    }

    public function delete_productimagegallery(Request $request)
    {

        $product = Product_image::find($request->id);

        $path = storage_path() . '/app/public/product_image/';

        //Storage::delete($path.$banner->image_name);

        \File::delete($path . $product->image_name);

        \File::delete($path . 'thumbnail/' . $product->image_name);

        deleteFiles($path, $product->image_name);

        $product->delete();

    }

    public function searchproducts(Request $request)
    {

        $products = DB::select("select * from products where `name` LIKE '" . $request->search_word . "%' order by id desc");

        $response = array();

        foreach ($products as $values) {

            $response[] = array("value" => $values->name, "label" => $values->name);

        }

        echo json_encode($response);

    }

    public function searchbrand_category(Request $request)
    {

        $products = DB::select("select category_id,category_name from products where `brand_id`='" . $request->brand_id . "' group by category_id order by id desc");

        $response = array();

        // echo "select * from products where `brand_id`='".$request->brand_id."' group by category_id order by id desc";

        echo json_encode($products);

        //   echo json_encode($response);

    }

    public function addquote(Request $request)
    {

        $data = DB::table("products")->where([['id', $request->product_id]])->first();

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

        $randno = substr($data->slug, 0, 5) . $quote->id;

        $quote_update->quote_id = $randno;

        $quote_update->save();

    }

    public function get_product_company(Request $request)
    {

        $products = DB::select("select * from products where `company_id`='" . $request->company_id . "' order by id desc");

        $response = array();

        echo json_encode($products);

        //   echo json_encode($response);

    }

    public function add_contact_person(Request $request)
    {

        if ($request->image_name1 != '') {

            $imageName1 = time() . $request->image_name1->getClientOriginalName();

            $imageName1 = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);

            $path = storage_path();

            $img_path = $request->image_name1->storeAs('public/contact', $imageName1);

            $path = $path . '/app/' . $img_path;

            chmod($path, 0777);

        } else {

            $imageName1 = "";

        }

        if ($request->image_name2 != '') {

            $imageName2 = time() . $request->image_name2->getClientOriginalName();

            $imageName2 = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName2);

            $path = storage_path();

            $img_path = $request->image_name2->storeAs('public/contact', $imageName2);

            $path = $path . '/app/' . $img_path;

            chmod($path, 0777);

        } else {

            $imageName2 = "";

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

        if ($admin_id > 0) {

            $admin = DB::table('admin')->where([

                ['id', $admin_id],

            ])

                ->first();

            $contact_person->added_by = $admin->surname;

            $contact_person->updated_by = $admin->surname;

        } else {

            $staff_id = session('STAFF_ID');

            $staff = DB::table('staff')->where([

                ['id', $staff_id],

            ])

                ->first();

            $contact_person->added_by = $staff->name;

            $contact_person->updated_by = $staff->name;

        }

        $contact_person->save();

    }

    public function contactformedit(Request $request)
    {

        $current1 = $request->current_image1;

        $current2 = $request->current_image2;

        if (isset($request->image_name1)) {

            $imageName1 = time() . $request->image_name1->getClientOriginalName();

            $imageName1 = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);

            $path = storage_path();

            $img_path = $request->image_name1->storeAs('public/contact', $imageName1);

            $path = $path . '/app/' . $img_path;

            chmod($path, 0777);

            $path = storage_path() . '/app/public/contact/';

            \File::delete($path . $current1);

        } else {

            $imageName1 = $current1;

        }

        if (isset($request->image_name2)) {

            $imageName2 = time() . $request->image_name2->getClientOriginalName();

            $imageName2 = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName2);

            $path = storage_path();

            $img_path = $request->image_name2->storeAs('public/contact', $imageName2);

            $path = $path . '/app/' . $img_path;

            chmod($path, 0777);

            $path = storage_path() . '/app/public/contact/';

            \File::delete($path . $current2);

        } else {

            $imageName2 = $current2;

        }

        $admin_id = session('ADMIN_ID');

        if ($admin_id > 0) {

            $admin = DB::table('admin')->where([

                ['id', $admin_id],

            ])

                ->first();

            $updated_by = $admin->surname;

        } else {

            $staff_id = session('STAFF_ID');

            $staff = DB::table('staff')->where([

                ['id', $staff_id],

            ])

                ->first();

            $updated_by = $staff->name;

        }

        $staff_id = session('STAFF_ID');

        $user_list = DB::select('select * from assign_supervisor where  `supervisor_id`="' . $staff_id . '" ');

        if (count($user_list) > 0) {

            $approved_by_id = $staff_id;

        } else {

            $approved_by_id = 0;

        }

        $update = DB::table('contact_person')->where('id', $request->contact_id)->update(['approved_by_id' => $approved_by_id, 'updated_by' => $updated_by, 'title' => $request->title, 'status' => $request->status, 'mobile' => $request->mobile, 'whatsapp' => $request->whatsapp, 'last_name' => $request->last_name, 'contact_type' => $request->contact_type, 'image_name1' => $imageName1, 'image_name2' => $imageName2, 'remark' => $request->remark, 'name' => $request->name, 'email' => $request->email, 'phone' => $request->phone, 'department' => $request->department, 'designation' => $request->designation]);

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

    public function get_product_all_details(Request $request)
    {

        $products = DB::select("select * from products where `id`='" . $request->product_id . "' order by id desc");

        $response = array();
        $msp_det = DB::select("select * from  msp where product_id='" . $request->product_id . "'  order by id desc limit 1");
        echo json_encode($products) . '*1*' . json_encode($msp_det);

    }

    public function get_oppurtunitydetails(Request $request)
    {

        $users = DB::select("select * from oppertunities where `id`='" . $request->opper_id . "' ");

        echo json_encode($users);

    }

    public function get_multiple_product_all_details(Request $request)
    {
        $ids_ordered = implode(',', $request->product_id);
        $products = Product::whereIn('id', $request->product_id)->orderByRaw("FIELD(id, $ids_ordered)")->get(); //DB::select("select * from products where `id`='".$request->product_id."' order by id desc");
        $response = array();
        $limits = count($request->product_id);
        $msp_det = Msp::whereIn('product_id', $request->product_id)->where('pro_quote_price', '>', 0)->groupBy('product_id')->orderBy('id', 'desc')->limit($limits)->get();

        $arr_pro = array();
        if (count($msp_det) > 0) {$i = 0;
            foreach ($msp_det as $val) {

                $products_det = DB::select("select * from  products where id='" . $val->product_id . "'");
                if (in_array($products_det[0]->id, $arr_pro)) {

                } else {
                    $arr_pro[] = $products_det[0]->id;
                    $val->id = $products_det[0]->id;
                    $val->pro_quote_price = $val->pro_quote_price;
                    $val->name = $products_det[0]->name;
                    $val->company_id = $products_det[0]->company_id;
                    $val->image_name = $products_det[0]->image_name;
                    $response[$i] = $val;
                }

                $i++;
            }}

        echo json_encode($response);
    }

    public function change_country(Request $request)
    {

        $state = DB::select("select * from state where `country_id`='" . $request->country_id . "' order by id desc");

        $response = array();

        echo json_encode($state);

    }

    public function change_state(Request $request)
    {

        $district = DB::select("select * from district where `state_id`='" . $request->state_id . "' order by name asc");

        $response = array();

        echo json_encode($district);

    }

    public function opp_change_state(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $opper = Oppertunity::select('oppertunities.*');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        $techsure_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'techsure')->first();
       
        // $opper->where(function ($qry) use ($staff_id) {

        //     $qry->where('created_by_id', $staff_id)->orWhere('coordinator_id', $staff_id)
        //     orwhere('created_by_id',$staff_id);

        // });

        if (optional($permission)->oppertechsure_view == 'view' || optional($techsure_permission)->opper_view == 'view') {

            if (optional($techsure_permission)->opper_view == 'view' && optional($permission)->oppertechsure_view != 'view') {
                $opper->where(function ($qry) use ($staff_id) {
                    $qry->where('created_by_id', '!=', $staff_id);

                });
            } else if (optional($techsure_permission)->opper_view != 'view' && optional($permission)->oppertechsure_view == 'view') {
                $opper->where(function ($qry) use ($staff_id) {
                    $qry->where('created_by_id', $staff_id);

                });
            }

        }

        $districtIds = $opper->pluck('district')->unique()->toArray();

        $district = District::whereIn('id', $districtIds)
            ->where('state_id', $request->state_id)
            ->orderBy('name', 'asc')
            ->get();

        // $district =  DB::select("select * from district where `state_id`='".$request->state_id."' order by name asc");

        $response = array();

        echo json_encode($district);

    }

    public function msa_change_state(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $opperids = MsaContract::pluck('oppertunity_id')->unique()->toArray();
        $opper = Oppertunity::whereIn('id', $opperids)->get();

        $districtIds = $opper->pluck('district')->unique()->toArray();

        $district = District::whereIn('id', $districtIds)
            ->where('state_id', $request->state_id)
            ->orderBy('name', 'asc')
            ->get();

        $response = array();

        echo json_encode($district);

    }

    public function pm_change_state(Request $request)
    {
        $staff_id = session('STAFF_ID');

        $opperids = PmDetails::pluck('service_id')->unique()->toArray();
        $opper = Oppertunity::whereIn('id', $opperids)->get();

        $districtIds = $opper->pluck('district')->unique()->toArray();

        $district = District::whereIn('id', $districtIds)
            ->where('state_id', $request->state_id)
            ->orderBy('name', 'asc')
            ->get();

        $response = array();

        echo json_encode($district);

    }

    public function service_change_state(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $service = Service::with('serviceUser', 'serviceProduct', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus')
            ->where('service_type', $request->id)->orderByRaw("FIELD(status , 'Open', 'Pending', 'created', 'Approved', 'Completed') ASC");

        $service->where('engineer_id', $staff_id);

        $opperids = $service->pluck('user_id')->unique()->toArray();
        $opper = User::whereIn('id', $opperids)->get();

        $districtIds = $opper->pluck('district_id')->unique()->toArray();

        $district = District::whereIn('id', $districtIds)
            ->where('state_id', $request->state_id)

            ->orderBy('name', 'asc')
            ->get();

        $response = array();

        echo json_encode($district);

    }

    public function user_change_state(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $districtIds = User::pluck('district_id')->unique()->toArray();

        $district = District::whereIn('id', $districtIds)
            ->where('state_id', $request->state_id)
            ->orderBy('name', 'asc')
            ->get();

        // $district =  DB::select("select * from district where `state_id`='".$request->state_id."' order by name asc");

        $response = array();

        echo json_encode($district);

    }

    public function emailvalidation(Request $request)
    {

        $this->validate($request, array(

            'email' => 'required|email:rfc,dns|unique:users,email',
        ));

        // $users =  DB::select("select * from users where `email`='".$request->email."' ");
        return response()->json([
            'success' => true,
            'message' => 'Email is valid and available.',
        ]);

    }

    public function emailvalidationusers(Request $request)
    {

        $contacts = $request->input('contact', []);

        $this->validate($request, [

            'contact.*' => 'email:rfc,dns',
            'customer_mail.*' => 'nullable|email:rfc,dns',
        ], [
            'customer_mail.*.email' => 'The email must be a valid email address.',
            'contact.*.email' => 'The contact email must be a valid email address.',
        ]);
        // foreach ($contacts as $key => $contactEmail) {
        //     $this->validate($request, [
        //         "contact.$key" => 'email:rfc,dns',
        //     ], [
        //         "contact.$key.email" => "The contact email at index $key must be a valid email address.",
        //     ]);
        // }

        return response()->json([
            'success' => true,
            'message' => 'Email is valid and available.',
        ]);
    }

    public function change_district(Request $request)
    {

        $state = DB::select("select * from taluk where `district_id`='" . $request->district_id . "' order by name asc");

        $response = array();

        echo json_encode($state);

    }

    public function change_assigned_team(Request $request)
    {

        $state = DB::select("select * from staff where `designation_id`='" . $request->assigned_team . "' order by id desc");

        $response = array();

        echo json_encode($state);

    }

    public function change_related_to(Request $request)
    {

        $state = DB::select("select * from relatedto_subcategory where `relatedto_category_id`='" . $request->related_to . "' order by id desc");

        $response = array();

        echo json_encode($state);

    }

    public function change_task_status(Request $request)
    {

        $task = Task::find($request->id);

        if ($request->status == "In Progress") {

            $task->staff_status = $request->status;

        }

        $task->status = $request->status;

        $task->save();

    }

    public function change_task_status_total_task(Request $request)
    {

        //     $alltask = DB::table('task_comment')

        //     ->where('task_id', [$request->id])

        //    ->get();

        $alltask = DB::select("select * from task_comment where `task_id`='" . $request->id . "'  order by id desc");

        // echo "select * from task_comment where `task_id`='".$request->id."' order by id desc";

        $count = 0;

        foreach ($alltask as $values) {

            if ($values->status == "N") {

                $count = $count + 1;

            }

        }

        if ($count > 0) {

            echo $count;

        } else {

            $task = Task::find($request->id);

            $task->staff_status = $request->status;

            $task->status = $request->status;

            $task->save();

            echo $count;

        }

    }

    public function change_task_priority(Request $request)
    {

        $task = Task::find($request->id);

        $task->priority = $request->status;

        $task->save();

    }

    public function view_task_details(Request $request)
    {

        //$task = DB::table('task')->where('id', [$request->id])->get();

        $task = Task::where('id', $request->id)->first();
        $contract = "";

        if (($task->user_id ?? 0) > 0) {
            // $contact_person = DB::table('contact_person')->where('user_id', [$task->user_id])->get();
            $contact_person = Contact_person::where('user_id', $task->user_id)->get();
            if ($task->service_id != '') {
                $service = Service::where('id', $task->service_id)->first();
                $products = Product::whereRaw('FIND_IN_SET(' . $service->equipment_id . ',related_products)')->get();
                if (!empty($service->pmContract)) {
                    $contract = json_encode($service->pmContract ?? []);
                }
            } else {
                $products = array();
            }
        } else {
            $contact_person = array();
            $products = array();
        }

        $alltask = array();
        $item = $task;
        // foreach($task as $item) {

        $alltask[] = $item->name;

        $alltask[] = $item->description;

        $alltask[] = $item->service_id;

        $alltask[] = $item->created_at ? Carbon::parse($item->created_at)->format('d-m-Y') : '';

        $alltask[] = $item->start_date ? Carbon::parse($item->created_at)->format('d-m-Y') : '';

        $alltask[] = $item->due_date ? Carbon::parse($item->due_date)->format('d-m-Y') : '';

        $alltask[] = $item->priority;

        $alltask_name = '';
        $admin_followe_name = "";

        if ($item->assigns > 0) {

            $staff_names = explode(",", $item->assigns);

            foreach ($staff_names as $val_staff) {
                if (Staff::where('id', $val_staff)->exists()) {

                    $staff = Staff::where('id', $val_staff)->first();

                    if (StaffFollower::where('staff_id', $staff->id)->exists()) {
                        $folowstaff = Staff::find(StaffFollower::where('staff_id', $staff->id)->first()->follower_id);
                        $admin_followe_name .= $staff->name . " - " . $folowstaff->name . ' ,';
                    }
                    $alltask_name .= $staff->name . ',';
                }

            }

        }

        if ($admin_followe_name != "") {
            $admin_followe_name = "Admin assign : " . substr($admin_followe_name, 0, -1) . "";
        }
        $follower = Staff::find($item->followers);

        $alltask[] = substr($alltask_name, 0, -1);

        $alltask[] = optional($follower)->name;

        $alltask[] = $item->user_id ?? 0;
        $alltask[] = $admin_followe_name;

        //}

        //print_r($alltask);

//    $staff_id = session('STAFF_ID');

//    $travel = DB::select("select * from dailyclosing_expence where travel_task_id='".$request->id."'  and expence_cat='travel' ");

//    $expence = DB::select("select * from dailyclosing_expence where travel_task_id='".$request->id."'  and expence_cat='expence' ");

//    $day_expence = DB::select("select * from dailyclosing_expence where travel_task_id='".$request->id."'   ");

        echo json_encode($alltask) . '|*|' . json_encode($contact_person) . '|*|' . json_encode($products) . '|*|' . $contract;

    }

    

    public function view_staff_task(Request $request)
    {

        $task = OppertunityTask::where('id', $request->id)->first();

        $chatter = Chatter::find($task->chatter_id);

        $opper = Oppertunity::find($task->oppertunity_id);

        $staff = Staff::find($task->staff_id);

        $follower = Staff::find($task->followers);

        $alltask = array();

        $alltask[] = $staff->name;  //0

        $alltask[] = $follower->name; //1

        
            $deal_stage = array('Lead Qualified/Key Contact Identified',
                'Customer needs analysis',
                'Clinical and technical presentation/Demo',
                'CPQ(Configure,Price,Quote)',
                'Customer Evaluation',
                'Final Negotiation',
                'Closed-Lost',
                'Closed-Cancel',
                'Closed Won - Implement'
            );

            $order_forcast =  array('Unqualified',
                    'Not addressable',
                    'Open',
                    'Upside',
                    'Committed w/risk',
                    'Committed'
            );

            $support =  array('Demo',
                'Application/ clinical support',
                'Direct company support',
                'Senior Engineer Support',
                'Price deviation'
            );

        $alltask[] = $deal_stage[$task->deal_stage]; //2

        $alltask[] = $order_forcast[$task->order_forcast]; //3
 
        $alltask[] = $support[$task->support]; //4



        $contact_person_ids   = explode(",",$task->contact);

        $contact_person_names = "";

        $count =0;

        foreach($contact_person_ids as  $ids)
        {
            $contact_person  = Contact_person::where('id',$ids)->first();

            if($contact_person)
            {
                if($count !=0)
                {
                    $contact_person_names.= ', '.$contact_person->name;
                }
                else
                {
                    $contact_person_names.= $contact_person->name;
                }

                $count++;
            }
            
        }
          
        echo json_encode($task). '*' . json_encode($alltask) . '*' . json_encode($chatter) . '*' . json_encode($opper) . '*' . json_encode($contact_person_names);

    }

    public function approve_staff(Request $request)
    {

        $id = $request->id;

        $opp_task = OppertunityTask::find($id);

        if($opp_task)
        {
            $opp_task->staff_status ="Approved";

            $opp_task->save();

            return response()->json([
                'message' => 'The Task has been approved successfully.'
            ], 200);
        }
        else {
            
            return response()->json([
                'error' => 'Task not found.'
            ], 404);
        }
    }


    public function add_task_comment(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $task_comment = new Task_comment;

        $cur_date = date('Y-m-d');
        $imageName = [];
        if (isset($request->image_name)) {

            foreach ($request->image_name as $request_image) {

                $imgname = time() . $request_image->getClientOriginalName();

                $imgname = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imgname);

                $path = storage_path();

                $img_path = $request_image->storeAs('public/comment', $imgname);

                $path = $path . '/app/' . $img_path;

                chmod($path, 0777);
                $imageName[] = $imgname;

            }

        }

        $task_comment->call_status = $request->call_status;

        $task_comment->email = $request->email_status;

        if (!empty($request->service_id)) {
            $task_comment->visit = 'Y';
        } else {
            $task_comment->visit = $request->visit_status;
        }

        $task_comment->contact_id = $request->contact_id;

        $task_comment->service_id = $request->service_id;

        $task_comment->service_task_problem = $request->service_task_problem;

        $task_comment->service_task_action = $request->service_task_action;

        $task_comment->service_task_final_status = $request->service_task_final_status;

        $task_comment->service_task_status = $request->service_task_status;

        $task_comment->service_part_status = $request->service_part_status;

        $task_comment->part_no = $request->part_no;

        if (!empty($request->service_id)) {
            $task_comment->task_remark = $request->comment;
        }

        if ($request->contact_id != '') {

            $contact_names = explode(",", $request->contact_id);

            $contact_person_name = '';

            foreach ($contact_names as $valcon) {

                if ($valcon > 0) {

                    $contact_person = Contact_person::find($valcon);

                    $contact_person_name .= $contact_person->name . ',';

                }

            }

        } else {

            $contact_person_name = '';

        }

        $task_comment->contact_name = substr($contact_person_name, 0, -1);

        $task_comment->comment = $request->comment;

        $task_comment->task_id = $request->task_id;

        $task_comment->image_name = implode(",", $imageName);

        $task_comment->added_by_id = $staff_id;

        $task = Task::find($request->task_id);

        $pm_detail = PmDetails::find($task->pm_detail_id);

        if(!empty($pm_detail))
        {
            $pm_detail->status ='reported';

            $pm_detail->save();
        }

        Task::where('id', $request->task_id)->update(['service_task_status' => 'Task Updated']);

        if ($staff_id == $task->followers) {

            $task->staff_status = "Pending";

            $task_comment->added_by = 'admin';

        } else {

            $task->status = "Pending";

            $task_comment->added_by = 'staff';

            /*****************************************/

            /*

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

            }*/

            /*****************************************/

        }

        $task->save();

        $update = DB::table('dailyclosing_details')->where('staff_id', $staff_id)->where('start_date', $cur_date)->update(['approved_fair' => 'Pending', 'approved_work' => 'Pending']);

        $task_comment->save();
        $taskCommentId = $task_comment->id;

        if ($request->product_part_id != '') {

            $product = explode(",", $request->product_part_id);

            foreach ($product as $value) {

                $servicePart = new ServicePart;

                $servicePart->product_id = $value;
                $servicePart->task_comment_id = $taskCommentId;
                $servicePart->service_id = $request->service_id;
                $servicePart->task_id = $request->task_id;
                $servicePart->staff_id = $staff_id;
                $servicePart->status = "Requested";
                $servicePart->service_part_status = $request->service_part_status;
                $servicePart->save();
            }

        }

    }

    public function view_task_comment(Request $request)
    {

        $task = Task_comment::with('taskCommentServiceParts.servicePartProduct')->where('task_id', $request->task_id)->get();

        // $task_replay =  DB::select("select * from task_comment_replay where `task_id`='".$request->task_id."' order by id asc");
        $task_replay = Task_comment_replay::where('task_id', $request->task_id)->orderBy('id', 'ASC')->get();

        //$task_replay =  Task_comment_replay::where('task_id',$request->task_id)->orderBy('id','ASC')->get();
        $task_details = Task::where('id', $request->task_id)->get();

        //print_r($alltask);

        // echo json_encode($task);
        $contract = "";

        //////////////////////// 12-04-2022 Start ////////////////////////////////

        $staff_names = [];
        $adminassigns = [];
        foreach ($task_details as $row) {
            $staff_names = array_merge($staff_names, explode(",", $row->assigns));
            $service = Service::find($row->service_id);
            if (!empty($service) && !empty($service->pmContract)) {
                $contract = json_encode($service->pmContract);
            }
        }
        foreach ($staff_names as $val_staff) {

            if (StaffFollower::where('staff_id', $val_staff)->exists()) {
                array_push($adminassigns, StaffFollower::where('staff_id', $val_staff)->first()->follower_id);
            }

        }

        //////////////////////// 12-04-2022 End ////////////////////////////////

        echo json_encode($task) . '|*|' . json_encode($task_replay) . '|*|' . json_encode($task_details) . '|*|' . json_encode($adminassigns) . '|*|' . $contract;

    }

    public function view_task_comment_dailytask(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $dailyclosing = DB::table('dailyclosing')

            ->where('start_date', [$request->start_date])

            ->where('assigns', [$staff_id])

            ->get();

        $i = 0;

        $task = array();

        $task_replay = array();

        $k = 0;

        foreach ($dailyclosing as $values) {

            /* $task_data = DB::table('task_comment')

            ->where('task_id', [$values->task_id])

            ->get();*/

            // echo "select * from task_comment where created_at like '".$request->start_date."%' AND task_id='".$values->task_id."'   order by id asc";

            $task_data = DB::select("select * from task_comment where created_at like '" . $request->start_date . "%' AND task_id='" . $values->task_id . "'   order by id asc");

            if (count($task_data) > 0) {

                foreach ($task_data as $task_com_val) {

                    $task_det = Task::find($task_com_val->task_id);

                    $task[$i]["added_by"] = $task_com_val->added_by;

                    $task[$i]["comment"] = $task_com_val->comment;

                    $task[$i]["image_name"] = $task_com_val->image_name;

                    $task[$i]["contact_name"] = $task_com_val->contact_name;

                    $task[$i]["created_at"] = $task_com_val->created_at;

                    $task[$i]["email"] = $task_com_val->email;

                    $task[$i]["call_status"] = $task_com_val->call_status;

                    $task[$i]["visit"] = $task_com_val->visit;

                    $task[$i]["status"] = $task_com_val->status;

                    $task[$i]["id"] = $task_com_val->id;

                    $task[$i]["task_name"] = $task_det->name;

                    if ($task_com_val->added_by == "staff") {

                        $staff_det = Staff::find($task_com_val->added_by_id);

                        $task[$i]["added_by_name"] = $staff_det->name;

                    } else {

                        $task[$i]["added_by_name"] = 'admin';

                    }

                    $task_replays = DB::select("select * from task_comment_replay where `task_comment_id`='" . $task_com_val->id . "' order by id asc");

                    if (count($task_replays) > 0) {

                        foreach ($task_replays as $task_rep_val) {

                            $rowval = $k;

                            $task_replay[$rowval]["comment"] = $task_rep_val->comment;

                            $task_replay[$rowval]["created_at"] = $task_rep_val->created_at;

                            $task_replay[$rowval]["task_comment_id"] = $task_rep_val->task_comment_id;

                            $task_replay[$rowval]["added_by"] = $task_rep_val->added_by;

                            if ($task_rep_val->added_by == "staff") {

                                $staff_det = Staff::find($task_rep_val->added_by_id);

                                $task_replay[$rowval]["added_by_name"] = $staff_det->name;

                            } else {

                                $task_replay[$rowval]["added_by_name"] = 'admin';

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

        $fair = array();

        if (count($expencedetails) > 0) {

            foreach ($expencedetails as $expence_val) {

                $p = 0;

                $fair_data = DB::table('dailyclosing_expence')

                    ->where('dailyclosing_details_id', [$expence_val->id])

                    ->get();

                if (count($fair_data) > 0) {

                    foreach ($fair_data as $expence) {

                        $fair[$p]["expence_type"] = $expence->expence_type;

                        $fair[$p]["fair"] = $expence->fair;

                        $p++;

                    }

                }

            }

        }

        $approval_comment = DB::select("select * from dailyclosing_comment where `staff_id`='" . $staff_id . "' AND start_date='" . $request->start_date . "' order by updated_at desc");

        //  print_r($task);

        //  echo '---';

        //  print_r($task_replay);

        $travel = DB::select("select * from dailyclosing_expence where staff_id='" . $staff_id . "' and start_date='" . $request->start_date . "' and expence_cat='travel' ");

        $expence = DB::select("select * from dailyclosing_expence where staff_id='" . $staff_id . "' and start_date='" . $request->start_date . "' and expence_cat='expence' ");

        echo json_encode($task) . '*' . json_encode($task_replay) . '*' . json_encode($task_details) . '*' . json_encode($expencedetails) . '*' . json_encode($fair) . '*' . json_encode($approval_comment) . '*' . json_encode($travel) . '*' . json_encode($expence);

    }

    public function view_task_commentall(Request $request)
    {

        $cur_date = date('Y-m-d');

        $staff_id = session('STAFF_ID');

        $task = DB::select("select * from task where start_date='" . $cur_date . "' AND (FIND_IN_SET('" . $staff_id . "', assigns) OR  `created_by_id`='" . $staff_id . "' )");

        // $task = DB::table('task_comment')

        //         ->where('task_id', [$request->task_id])

        //     ->get();

        $task_replay = DB::select("select * from task_comment_replay where `task_id`='" . $request->task_id . "' order by id asc");

        $task_details = DB::table('task')

            ->where('id', [$request->task_id])

            ->get();

        //print_r($alltask);

        // echo json_encode($task);

        echo json_encode($task) . '*' . json_encode($task_replay) . '*' . json_encode($task_details);

    }

    public function delete_task_comment(Request $request)
    {

        $task_comment = Task_comment::find($request->id);

        $task_comment->delete();

    }

    public function viewchecklist_details(Request $request)
    {

        $state = DB::select("select * from  checklist where `related_subcategory_id`='" . $request->related_to_sub . "' order by id desc");

        $response = array();

        echo json_encode($state);

    }

    public function add_task_replay_comment(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $cur_date = date('Y-m-d');

        $task_comment = Task_comment::find($request->task_comment_id);

        $task_comment->status = $request->status;

        // $task_comment->service_task_problem = $request->service_task_problem;

        // $task_comment->service_task_action = $request->service_task_action;

        // $task_comment->service_task_final_status = $request->service_task_final_status;

        $task_comment->save();

        $task_comment_replay = new Task_comment_replay;

        $task_comment_replay->task_id = $request->task_id;

        $task_comment_replay->comment = $request->replay_comment;

        $task_comment_replay->task_comment_id = $request->task_comment_id;

        $task_comment_replay->parent_id = $request->parent_id;

        $task_comment_replay->added_by_id = $staff_id;

        if ($request->parent_id > 0) {

            $task_comment_replay_update = Task_comment_replay::find($request->parent_id);

            $task_comment_replay_update->replay_status = "Y";

            $task_comment_replay_update->save();

        }

        //Task::where('id',$request->task_id)->update(['service_task_status' => 'Task Updated']);

        $task = Task::find($request->task_id);

        if ($staff_id == $task->followers) {

            if ($request->status == "Y") {

                $task->status = "In Progress";
                $task->staff_status = "Approved";
                $task_comment_replay->added_by = 'admin';
                $task->service_task_status = 'Task Approved';

            } else {

                $task->status = "In Progress";
                $task->staff_status = "Pending";
                $task_comment_replay->added_by = 'admin';
                $task->service_task_status = 'Task Rejected';

            }
        } else {

            $task->staff_status = "In Progress";
            $task->status = "Pending";
            $task_comment_replay->added_by = 'staff';
            $task->service_task_status = 'Task Rejected';

        }
        // $task->staff_status = "In Progress";

        // $task->status = "Pending";

        $task->save();

        $task_comment_replay->save();

        if (!empty($task->service_id)) {

            if ($request->status == "Y") {
                Service::where('id', $task->service_id)->update(['status' => 'Open']);
                ServicePart::where('task_comment_id', $request->task_comment_id)->update(['status' => 'Approved']);
            }

        }

        $update = DB::table('dailyclosing_details')->where('staff_id', $staff_id)->where('start_date', $cur_date)->update(['approved_fair' => 'Pending', 'approved_work' => 'Pending']);

        $update = DB::table('task_comment')->where('task_id', $request->task_id)->update(['admin_view' => "Y"]);

    }

    public function change_repeat_every(Request $request)
    {

        $repeat_type = $request->repeat_type;

        $start_date = $request->start_date;

        $start_time = $request->start_time;

        $custom_type = $request->custom_type;

        $custom_days = $request->custom_days;

        $unlimited_cycles = $request->unlimited_cycles;

        $days_book = array();

        if ($request->cycles > 0) {

            $count = $request->cycles;

        } else {

            if ($request->cycles == 0 && $unlimited_cycles == 0) {

                $count = 0;

            } else {

                $count = 5;

            }

        }

        if ($repeat_type == "Days") {

            $due_date = $request->start_date;

            for ($i = 1; $i <= $count; $i++) {

                $add = $i . ' day';

                $start_date_time = $start_date . ' ' . $start_time;

                $days_book[] = date("Y-m-d H:i:s", strtotime("+" . $add, strtotime($start_date_time)));

            }

        }

        if ($repeat_type == "Week") {

            $due_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +1 week"));

            for ($i = 1; $i <= $count; $i++) {

                $add = $i . ' week';

                $start_date_time = $start_date . ' ' . $start_time;

                $days_book[] = date("Y-m-d H:i:s", strtotime("+" . $add, strtotime($start_date_time)));

            }

        }

        if ($repeat_type == "2weeks") {

            $due_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +2 week"));

            $start_date_time = $start_date . ' ' . $start_time;

            for ($i = 1; $i <= $count; $i++) {

                if ($i == 1) {

                    $days_book[] = date("Y-m-d H:i:s", strtotime(" +2 week", strtotime($start_date_time)));

                    $add_date = date("Y-m-d H:i:s", strtotime(" +2 week", strtotime($start_date_time)));

                } else {

                    $days_book[] = date("Y-m-d H:i:s", strtotime(" +2 week", strtotime($add_date)));

                    $add_date = date("Y-m-d H:i:s", strtotime(" +2 week", strtotime($add_date)));

                }

            }

        }

        if ($repeat_type == "1Month") {

            $due_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +1 month"));

            $start_date_time = $start_date . ' ' . $start_time;

            for ($i = 1; $i <= $count; $i++) {

                $add = $i . ' month';

                $days_book[] = date("Y-m-d H:i:s", strtotime("+" . $add, strtotime($start_date_time)));

            }

        }

        if ($repeat_type == "2Month") {

            $due_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +2 month"));

            $start_date_time = $start_date . ' ' . $start_time;

            for ($i = 1; $i <= $count; $i++) {

                if ($i == 1) {

                    $days_book[] = date("Y-m-d H:i:s", strtotime(" +2 month", strtotime($start_date_time)));

                    $add_date = date("Y-m-d H:i:s", strtotime(" +2 month", strtotime($start_date_time)));

                } else {

                    $days_book[] = date("Y-m-d H:i:s", strtotime(" +2 month", strtotime($add_date)));

                    $add_date = date("Y-m-d H:i:s", strtotime(" +2 month", strtotime($add_date)));

                }

            }

        }

        if ($repeat_type == "3Month") {

            $start_date_time = $start_date . ' ' . $start_time;

            $due_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +3 month"));

            for ($i = 1; $i <= $count; $i++) {

                if ($i == 1) {

                    $days_book[] = date("Y-m-d H:i:s", strtotime(" +3 month", strtotime($start_date_time)));

                    $add_date = date("Y-m-d H:i:s", strtotime(" +3 month", strtotime($start_date_time)));

                } else {

                    $days_book[] = date("Y-m-d H:i:s", strtotime(" +3 month", strtotime($add_date)));

                    $add_date = date("Y-m-d H:i:s", strtotime(" +3 month", strtotime($add_date)));

                }

            }

        }

        if ($repeat_type == "6Month") {

            $due_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +6 month"));

            $start_date_time = $start_date . ' ' . $start_time;

            for ($i = 1; $i <= $count; $i++) {

                if ($i == 1) {

                    $days_book[] = date("Y-m-d H:i:s", strtotime(" +6 month", strtotime($start_date_time)));

                    $add_date = date("Y-m-d", strtotime(" +6 month", strtotime($start_date_time)));

                } else {

                    $days_book[] = date("Y-m-d H:i:s", strtotime(" +6 month", strtotime($add_date)));

                    $add_date = date("Y-m-d H:i:s", strtotime(" +6 month", strtotime($add_date)));

                }

            }

        }

        if ($repeat_type == "1year") {$start_date_time = $start_date . ' ' . $start_time;

            $due_date = date('Y-m-d', strtotime('+1 year', strtotime($start_date)));

            for ($i = 1; $i <= $count; $i++) {

                $add = $i . ' year';

                $days_book[] = date("Y-m-d H:i:s", strtotime("+" . $add, strtotime($start_date_time)));

            }}

        if ($repeat_type == "Custom" && $custom_days > 0) {

            if ($custom_type == "Weeks") {

                $week_hour = 24 * 7;

                $add_hour = $week_hour / $custom_days;

                $start_date_time = $start_date . ' ' . $start_time;

                $add = $add_hour . ' hours';

                $due_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +1 week"));

                for ($j = 1; $j <= $count; $j++) {

                    for ($i = 1; $i <= $custom_days; $i++) {

                        if (empty($days_book)) {

                            $add_date = date("Y-m-d H:i:s", strtotime('+' . $add, strtotime($start_date_time)));

                            $days_book[] = $add_date;

                        } else {

                            $days_book[] = date("Y-m-d H:i:s", strtotime('+' . $add, strtotime($add_date)));

                            $add_date = date("Y-m-d H:i:s", strtotime('+' . $add, strtotime($add_date)));

                        }

                    }

                }

            }

            if ($custom_type == "Months") {

                $due_date = date("Y-m-d", strtotime(date("Y-m-d", strtotime($start_date)) . " +1 month"));

                $total_days = cal_days_in_month(CAL_GREGORIAN, date("m", strtotime($start_date)), date("Y", strtotime($start_date)));

                $week_hour = 24 * $total_days;

                $add_hour = $week_hour / $custom_days;

                $start_date_time = $start_date . ' ' . $start_time;

                $add = $add_hour . ' hours';

                for ($j = 1; $j <= $count; $j++) {

                    for ($i = 1; $i <= $custom_days; $i++) {

                        if (empty($days_book)) {

                            $add_date = date("Y-m-d H:i:s", strtotime('+' . $add, strtotime($start_date_time)));

                            $days_book[] = $add_date;

                        } else {

                            $days_book[] = date("Y-m-d H:i:s", strtotime('+' . $add, strtotime($add_date)));

                            $add_date = date("Y-m-d H:i:s", strtotime('+' . $add, strtotime($add_date)));

                        }

                    }

                }

            }

            if ($custom_type == "Years") {

                $due_date = date('Y-m-d', strtotime('+1 year', strtotime($start_date)));

                $leap = (date('L', mktime(0, 0, 0, 1, 1, date("Y", strtotime($start_date)))) == 1);

                if ($leap == "1") {$total_days = 365;} else { $total_days = 366;}

                $week_hour = 24 * $total_days;

                $add_hour = $week_hour / $custom_days;

                $start_date_time = $start_date . ' ' . $start_time;

                $add = $add_hour . ' hours';

                for ($j = 1; $j <= $count; $j++) {

                    for ($i = 1; $i <= $custom_days; $i++) {

                        if (empty($days_book)) {

                            $add_date = date("Y-m-d H:i:s", strtotime('+' . $add, strtotime($start_date_time)));

                            $days_book[] = $add_date;

                        } else {

                            $days_book[] = date("Y-m-d H:i:s", strtotime('+' . $add, strtotime($add_date)));

                            $add_date = date("Y-m-d H:i:s", strtotime('+' . $add, strtotime($add_date)));

                        }

                    }

                }

            }

        }

        $days_book_check = array();

        if ($request->infinity_end_date != '') {

            foreach ($days_book as $val) {

                $dates = date("Y-m-d", strtotime($val));

                if (strtotime($request->infinity_end_date) < strtotime($dates)) {

                    $days_book_check[] = $val;

                }

            }

            $total_val_arr = array_diff($days_book, $days_book_check);

            echo $due_date . '*' . json_encode($total_val_arr);

        } else {

            echo $due_date . '*' . json_encode($days_book);

        }

        // print_r($days_book);

        //echo $due_date.'*'.json_encode($days_book);

    }

    public function get_client_use_state_district(Request $request)
    {
        if ($request->type == 2) {
            $users = User::whereHas('userIb')->where('state_id', $request->state_id)->where('district_id', $request->district_id)->get();

        } else {
            $users = DB::select("select business_name,id from users where `state_id`='" . $request->state_id . "' AND `district_id`='" . $request->district_id . "' ");
        }
        echo json_encode($users);

    }

    public function opp_get_client_use_state_district(Request $request) 
    {

        $staff_id = session('STAFF_ID');

        $opper = Oppertunity::select('oppertunities.*');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        $techsure_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'techsure')->first();
           
        // $opper->where(function ($qry) use ($staff_id) {

        //     $qry->where('staff_id', $staff_id)->orWhere('coordinator_id', $staff_id);

        // });

        if (optional($permission)->oppertechsure_view == 'view' || optional($techsure_permission)->opper_view == 'view') {

            if (optional($techsure_permission)->opper_view == 'view' && optional($permission)->oppertechsure_view != 'view') {
                $opper->where(function ($qry) use ($staff_id) {
                    $qry->where('created_by_id', '!=', $staff_id);

                });
            } else if (optional($techsure_permission)->opper_view != 'view' && optional($permission)->oppertechsure_view == 'view') {
                $opper->where(function ($qry) use ($staff_id) {
                    $qry->where('created_by_id', $staff_id);

                });
            }

        }

        $UserIds = $opper->pluck('user_id')->unique()->toArray();

        $users = User::whereIn('id', $UserIds)
            ->where('state_id', $request->state_id)
            ->where('district_id', $request->district_id)
            ->orderBy('business_name', 'asc')
            ->get();

        // $users =  DB::select("select business_name,id from users where `state_id`='".$request->state_id."' AND `district_id`='".$request->district_id."' ");

        echo json_encode($users);

    }

    public function msa_get_client_use_state_district(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $service = Service::with('serviceUser', 'serviceProduct', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus')
            ->where('service_type', $request->id)->orderByRaw("FIELD(status , 'Open', 'Pending', 'created', 'Approved', 'Completed') ASC");

        $service->where('engineer_id', $staff_id);



        $opperids = MsaContract::pluck('oppertunity_id')->unique()->toArray();
        $opper = Oppertunity::whereIn('id', $opperids)->get();

        $UserIds = $opper->pluck('user_id')->unique()->toArray();

        $users = User::whereIn('id', $UserIds)
            ->where('state_id', $request->state_id)
            ->where('district_id', $request->district_id)
            ->orderBy('business_name', 'asc')
            ->get();

        // $users =  DB::select("select business_name,id from users where `state_id`='".$request->state_id."' AND `district_id`='".$request->district_id."' ");

        echo json_encode($users);

    } 

    public function pm_get_client_use_state_district(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $opperids = PmDetails::pluck('service_id')->unique()->toArray();
        $opper = Oppertunity::whereIn('id', $opperids)->get();

        $UserIds = $opper->pluck('user_id')->unique()->toArray();

        $users = User::whereIn('id', $UserIds)
            ->where('state_id', $request->state_id)
            ->where('district_id', $request->district_id)
            ->orderBy('business_name', 'asc')
            ->get();

        // $users =  DB::select("select business_name,id from users where `state_id`='".$request->state_id."' AND `district_id`='".$request->district_id."' ");

        echo json_encode($users);

    }

    public function service_get_client_use_state_district(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $service = Service::with('serviceUser', 'serviceProduct', 'serviceContactPerson', 'serviceEngineer', 'serviceMachineStatus', 'serviceEquipmentStatus')
            ->where('service_type', $request->id)->orderByRaw("FIELD(status , 'Open', 'Pending', 'created', 'Approved', 'Completed') ASC");

        $service->where('engineer_id', $staff_id);

        $opperids = $service->pluck('user_id')->unique()->toArray();
        $opper = User::whereIn('id', $opperids)->get();

        $UserIds = $opper->pluck('id')->unique()->toArray();

        $users = User::whereIn('id', $UserIds)
            ->where('state_id', $request->state_id)
            ->where('district_id', $request->district_id)
            ->orderBy('business_name', 'asc')
            ->get();

        // $users =  DB::select("select business_name,id from users where `state_id`='".$request->state_id."' AND `district_id`='".$request->district_id."' ");

        echo json_encode($users);

    }

    public function taluk_state_district(Request $request)
    {

        $staff_id = session('STAFF_ID');

        $talukIds = User::pluck('taluk_id')->unique()->toArray();

        $taluk = Taluk::whereIn('id', $talukIds)
            ->where('state_id', $request->state_id)
            ->where('district_id', $request->district_id)
            ->orderBy('name', 'asc')
            ->get();

        echo json_encode($taluk);

    }

    public function change_assignes(Request $request)
    {

        $i = 0;

        $sql = '';

        $search_cond_array = array();

        foreach ($request->assigns as $val) {

            $search_cond_array[] = "id!='" . $val . "'";

            $i++;

        }

        if (count($search_cond_array) > 0) {

            $search_condition = " WHERE " . join(" AND ", $search_cond_array);

            $sql .= $search_condition;

        }

        //     echo $sql;exit;

        $state = DB::select("select * from staff " . $sql . " ");

        $response = array();

        echo json_encode($state);

    }

    public function get_contact_details(Request $request)
    {

        $users = DB::select("select * from contact_person where `id`='" . $request->contact_id . "'  ");

        echo json_encode($users);

    }

    public function get_user_contact_list(Request $request)
    {

        $users = DB::select("select * from contact_person where `user_id`='" . $request->user_id . "'  ");

        echo json_encode($users);

    }

    public function save_first_responce(Request $request)
    {

        if ($request->service_responce_id > 0) {

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

        } else {

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

        $service_responce = DB::select("select * from service_responce where `service_task_id`='" . $request->resp_service_task_id . "' order by schedule_date desc ");

        $service_visit = DB::select("select * from service_visit where `service_task_id`='" . $request->resp_service_task_id . "'  order by travel_start_time desc");

        $service_part = DB::select("select * from service_part where `service_task_id`='" . $request->resp_service_task_id . "' order by intened_date desc ");

        echo json_encode($service_responce) . '*' . json_encode($service_visit) . '*' . json_encode($service_part);

    }

    public function save_visit(Request $request)
    {

        if ($request->service_visit_id > 0) {

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

        } else {

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

        $service_responce = DB::select("select * from service_responce where `service_task_id`='" . $request->resp_service_task_id . "' order by schedule_date desc ");

        $service_visit = DB::select("select * from service_visit where `service_task_id`='" . $request->resp_service_task_id . "'  order by travel_start_time desc");

        $service_part = DB::select("select * from service_part where `service_task_id`='" . $request->resp_service_task_id . "' order by intened_date desc ");

        echo json_encode($service_responce) . '*' . json_encode($service_visit) . '*' . json_encode($service_part);

    }

    public function save_part(Request $request)
    {

        if ($request->service_part_id > 0) {

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

        } else {

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

        $service_responce = DB::select("select * from service_responce where `service_task_id`='" . $request->resp_service_task_id . "' order by schedule_date desc ");

        $service_visit = DB::select("select * from service_visit where `service_task_id`='" . $request->resp_service_task_id . "'  order by travel_start_time desc");

        $service_part = DB::select("select * from service_part where `service_task_id`='" . $request->resp_service_task_id . "' order by intened_date desc ");

        echo json_encode($service_responce) . '*' . json_encode($service_visit) . '*' . json_encode($service_part);

    }

    public function edit_part(Request $request)
    {

        if ($request->type == "search") {

            $brand_id = $request->brand_id;

            $category_type_id = $request->category_type_id;

            $html = '';

            if ($brand_id != '') {

                for ($i = 0; $i < count($brand_id); $i++) {

                    if ($i == 0) {

                        $html .= ' AND brand_id=' . $request->brand_id[$i];

                    } else {

                        $html .= ' OR brand_id=' . $request->brand_id[$i];

                    }

                }

            }

            if ($category_type_id != '') {

                for ($i = 0; $i < count($category_type_id); $i++) {

                    if ($i == 0) {

                        $html .= ' AND category_type_id=' . $request->category_type_id[$i];

                    } else {

                        $html .= ' AND category_type_id=' . $request->category_type_id[$i];

                    }

                }

            }

            $products_sql = DB::select("select * from products where id>0  " . $html . "");

            echo json_encode($products_sql);

        }

        if ($request->type == "product") {

            $data = '';

            $j = 0;

            //

            foreach ($request->product_id as $values) {

                $product = DB::select("select id,name from  products where id='" . $values . "'  ");

                $data .= ' <tr id="row_' . $values . '">

       <td>

        ' . $product[0]->name . ' <a onclick="remove_product(' . $values . ')">Remove</a>

         </td>

         <input type="hidden" name="product_val[]" value=' . $values . '>





       </tr>';

                $j++;

            }

            echo $data;

        }

        exit;

    }

    public function edit_first_responce(Request $request)
    {

        $service_responce = DB::select("select * from service_responce where `id`='" . $request->id . "'  ");

        echo json_encode($service_responce);

    }

    public function edit_visit(Request $request)
    {

        $service_visit = DB::select("select * from service_visit where `id`='" . $request->id . "'  ");

        echo json_encode($service_visit);

    }

    public function save_mspusing_ajax(Request $request)
    {

        if (count($request->product_id) > 0) {
            $brand = Brand::find($request->brand_id);
            //print_r($request->product_id);
            /* echo count($request->product_id);
            echo '<br>';
            echo count($request->percent_online);
            exit;*/
            for ($i = 0; $i < count($request->product_id); $i++) {
                //echo $request->incentive_amount[$i];
                //die('eee');
                $msp = new Msp();

                $msp->brand_id = $request->brand_id;
                $msp->product_id = $request->product_id[$i];
                $msp->cost = $request->cost[$i];
                $msp->trans_cost = $request->trans_cost[$i];
                $msp->customs_cost = $request->customs_cost[$i];
                $msp->other_cost = $request->other_cost[$i];
                $msp->total_cost = $request->total_cost[$i];
                $msp->profit = $request->profit[$i];
                $msp->pro_msp = $request->pro_msp[$i];
                $msp->quote = $request->quote[$i];
                $msp->pro_quote_price = $request->pro_quote_price[$i];
                $msp->tax_per = $request->tax_per[$i];
                $msp->hsn_code = $request->hsn_code[$i];
                $msp->percent_online = $request->percent_online[$i];
                $msp->online_price = $request->online_price[$i];
                $msp->discount = $request->discount[$i];
                $msp->incentive = $request->incentive[$i];
                $msp->incentive_amount = $request->incentive_amount[$i];
                $msp->discount_price = $request->discount_price[$i];
                $product = Product::find($request->product_id[$i]);
                if ($product) {
                    $msp->product_name = $product->name;
                } else {
                    $msp->product_name = '';
                }

                if ($brand) {
                    $msp->brand_name = $brand->name;
                } else {
                    $msp->brand_name = '';
                }

                $msp->save();

            }
        }

    }

    public function show_prv_msp(Request $request)
    {
        $msp = DB::select("select * from msp   where product_id='" . $request->product_id . "'   order by id desc");
        echo json_encode($msp);
    }

    public function change_brand_for_product(Request $request)
    {

        if ($request->search_word != '') {
            $search = "AND product.name LIKE '%" . $request->search_word . "'";
        } else {
            $search = '';
        }

        if ($request->brand_id > 0 && $request->category_type_id == "" && $request->category_id == "") {

            $product = DB::select("select product.id,product.show_inPage,product.name,product.unit_price,product.tax_percentage,product.hsn_code from products as product INNER JOIN brand as brand ON product.brand_id=brand.id where product.brand_id='" . $request->brand_id . "' " . $search . " ");
        }

        if ($request->brand_id > 0 && $request->category_type_id > 0 && $request->category_id == "") {
            $product = DB::select("select product.id,product.show_inPage,product.name,product.unit_price,product.tax_percentage,product.hsn_code from products as product INNER JOIN brand as brand ON product.brand_id=brand.id where product.brand_id='" . $request->brand_id . "' AND product.category_type_id='" . $request->category_type_id . "'  " . $search . "");
        }

        if ($request->brand_id > 0 && $request->category_type_id > 0 && $request->category_id > 0) {

            $product = DB::select("select product.id,product.show_inPage,product.name,product.unit_price,product.tax_percentage,product.hsn_code from products as product INNER JOIN brand as brand ON product.brand_id=brand.id where product.brand_id='" . $request->brand_id . "' AND product.category_type_id='" . $request->category_type_id . "' AND find_in_set('$request->category_id',product.category_id)  " . $search . " ");
        }

        if ($request->brand_id == "" && $request->category_type_id == "" && $request->category_id == "" && $request->search_word != '') {
            $product = DB::select("select product.id,product.show_inPage,product.name,product.unit_price,product.tax_percentage,product.hsn_code from products as product where product.name LIKE '%" . $request->search_word . "%' ");
        }
        $output = array();
        if (count($product) > 0) {

            $i = 0;
            foreach ($product as $values) {
                $msp = DB::select("select * from msp   where product_id='" . $values->id . "'   order by id desc");
                if (count($msp) > 0) {
                    $output[$i]["id"] = $values->id;
                    $output[$i]["name"] = $values->name;
                    $output[$i]["unit_price"] = $msp[0]->cost;
                    $output[$i]["trans_cost"] = $msp[0]->trans_cost;
                    $output[$i]["customs_cost"] = $msp[0]->customs_cost;
                    $output[$i]["other_cost"] = $msp[0]->other_cost;
                    $output[$i]["profit"] = $msp[0]->profit;
                    $output[$i]["quote"] = $msp[0]->quote;
                    $output[$i]["quote_price"] = $msp[0]->pro_quote_price;
                    $output[$i]["tax_per"] = $msp[0]->tax_per;
                    $output[$i]["hsn_code"] = $msp[0]->hsn_code;
                    $output[$i]["percent_online"] = $msp[0]->percent_online;
                    $output[$i]["online_price"] = $msp[0]->online_price;
                    $output[$i]["discount"] = $msp[0]->discount;
                    $output[$i]["discount_price"] = $msp[0]->discount_price;
                    $output[$i]["incentive"] = $msp[0]->incentive;
                    $output[$i]["show_inPage"] = $values->show_inPage == 'Y' ? 'checked' : "";
                } else {
                    $output[$i]["id"] = $values->id;
                    $output[$i]["name"] = $values->name;
                    $output[$i]["unit_price"] = 0;
                    $output[$i]["trans_cost"] = 10;
                    $output[$i]["customs_cost"] = 10;
                    $output[$i]["other_cost"] = 1;
                    $output[$i]["profit"] = 15;
                    $output[$i]["quote"] = 20;
                    $output[$i]["quote_price"] = 0;
                    $output[$i]["tax_per"] = $values->tax_percentage;
                    $output[$i]["hsn_code"] = $values->hsn_code;
                    $output[$i]["percent_online"] = 5;
                    $output[$i]["online_price"] = 0;
                    $output[$i]["discount"] = 1;
                    $output[$i]["discount_price"] = 0;
                    $output[$i]["incentive"] = 0;
                    $output[$i]["show_inPage"] = $values->show_inPage == 'Y' ? 'checked' : "";
                }

                $i++;
            }
        }
        echo json_encode($output);

    }

    public function sort_brand_categorytypeuse_carearea(Request $request)
    {

        $category_type = DB::select("select categories.id,categories.name from categories as categories inner join products as products ON categories.id=products.category_id where products.brand_id='" . $request->brand_id . "' AND products.category_type_id='" . $request->category_type_id . "'  group by categories.id ORDER BY categories.name asc  ");
        echo json_encode($category_type);

    }

    public function change_particular_product_details(Request $request)
    {
        $product = DB::select("select * from products where `id`='" . $request->product_id . "'  ");
        echo json_encode($product);

    }

    public function sort_brand_use_category_type(Request $request)
    {

        $category_type = DB::select("select category_type.id,category_type.name from category_type as category_type inner join products as products ON category_type.id=products.category_type_id where products.brand_id='" . $request->brand_id . "'  group by category_type.id ORDER BY category_type.name asc  ");
        echo json_encode($category_type);

    }

    public function get_last_product_price_msp(Request $request)
    {
        $msp = DB::select("select * from msp where `product_id`='" . $request->product_id . "'  order by id desc limit 1");
        $diff = 0;
        if ($msp) {
            echo $diff = $request->proposed_val - $msp[0]->pro_msp;
        } else {echo '0';}

    }

}
