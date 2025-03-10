<?php
/*
 |--------------------------------------------------------------------------
 | Web Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register web routes for your application. These
 | routes are loaded by the RouteServiceProvider within a group which
 | contains the "web" middleware group. Now create something great!!
 |
 */

//test git

use App\Http\Controllers\ProductsController;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\DealerAuthenticate;
use App\Http\Middleware\StaffAuthenticate;
use App\Http\Middleware\MarketspaceAuthenticate;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactusController;

use App\AppVersionControll;


Route::get('testtaskv', 'staff\TaskController@test_v_task');
// use App\Staff;
// Route::get("staffpassreset",function(){
//   Staff::where("email","rakhil@bechealthcare.com")->update(["password"=>md5("koyilandy")]);
//   print_r(json_encode(Staff::where("email","rakhil@bechealthcare.com")->get()));
// });
// Auth::routes();
Route::get('/', [PageController::class, 'home']);
Route::get('/about', [ContactusController::class, 'about'])->name('contactus');
Route::post('/contact-us', [ContactusController::class, 'store'])->name('contactus.store');
Route::post('/enquirysave', [ContactusController::class, 'enquirysave'])->name('contactus.enquirysave');
Route::post('/requestsave', [ContactusController::class, 'requestsave'])->name('contactus.requestsave');
Route::get('/about', [ContactusController::class, 'about'])->name('contactus');

Route::get('/privacy', [ContactusController::class, 'privacy'])->name('privacy');
Route::get('/terms', [ContactusController::class, 'terms'])->name('terms');
Route::get('/products', [ProductsController::class, 'productindex']);
Route::get('/allproducts', [ProductsController::class, 'productindex']);
Route::get('/products/{category}', [ProductsController::class, 'categories']);
Route::get('/quote_details/{pd_slug}', [ContactusController::class, 'quote_details_cat'])->name('quote_details');


Route::get(
  '/download/storage/comment/{filename}',
  function ($filename) {
    return response()->download(public_path("/storage/comment/$filename"));
  }
);
// Route::get("gitupdate",function(){
//   $output = [];
//   exec("cd /home/biomedic/public_html ;git pull", $output);
//   dd($output);
// });
// Route::get("fix-permission",function(){
//   $output = [];
//   exec("cd /home/biomedic/public_html ;chmod -R 777 storage/logs", $output);
//   dd($output);
// });
//Route::get('/marketspace', 'PageController@marketspace');
Route::get('/marketspace', 'marketspace\MarketspaceController@marketspaceregister');
Route::get('/404', 'PageController@notfound');
Route::get('/hero', 'PageController@hero');
Route::post('/hero', 'PageController@heroSubmit')->name("hero.submit");
Route::get('/thankyou', 'PageController@thankyou')->name('form.thankyou');
Route::get('/marketspace/search', 'PageController@marketspacesearch');
Route::get('/marketspace/profile/{id}', 'PageController@profile')->name('profile');
Route::get('/marketspace/verify/{id}', 'PageController@verify')->name('verify');
Route::get('fetch_data', 'PageController@fetch_data')->name('fetch_data');

Route::get('marketspace/user', 'marketspace\MarketspaceController@getMessage')->name('marketspace/user');
Route::post('marketspace/message', 'marketspace\MarketspaceController@sendMessage');
Route::get('marketspace/chat', 'marketspace\MarketspaceController@chat')->name('marketspace/chat');

Route::post('search_skills', 'PageController@search_skills')->name('search_skills');

Route::get('/sitemap.xml', 'SitemapXmlController@index')->name('sitemap.xml');
/***********************************Marketspace Login Start****************************************************/
Route::get('marketspace/login', 'marketspace\MarketspaceController@marketspaceregister')->name('marketspace/login');
Route::get('marketspace/register', 'marketspace\MarketspaceController@marketspaceregister')->name('marketspace/register');
;
Route::post('postLogin', 'marketspace\MarketspaceController@postLogin')->name('postLogin');
Route::post('postRegistration', 'marketspace\MarketspaceController@postRegistration')->name('postRegistration');
Route::post('otpsend', 'marketspace\MarketspaceController@otpsend')->name('otpsend');
Route::post('verifyotp', 'marketspace\MarketspaceController@verifyotp')->name('verifyotp');


Route::get('marketspace/marketspaceforgot', 'marketspace\MarketspaceController@marketspaceforgot');
Route::get('marketspace/logout', 'marketspace\DashboardController@logout')->name('marketspace/logout');

Route::get('marketspace/dashboard', 'marketspace\DashboardController@dashboard')->name('dashboard');
Route::get('marketspace/account', 'marketspace\DashboardController@account')->name('marketspace/account');

Route::get('marketspace/skills', 'marketspace\DashboardController@skills')->name('skills');
Route::get('marketspace/ib', 'marketspace\DashboardController@ib')->name('ib');
Route::get('marketspace/training-attended', 'marketspace\DashboardController@training_attended')->name('training-attended');
Route::get('marketspace/reference', 'marketspace\DashboardController@reference')->name('reference');
Route::get('marketspace/education', 'marketspace\DashboardController@education')->name('education');
Route::get('marketspace/marketspace-ib', 'marketspace\DashboardController@marketspace_ib')->name('marketspace-ib');
Route::get('marketspace/work-experience', 'marketspace\DashboardController@work_experience')->name('work-experience');


Route::get('marketspace/editprofile', 'marketspace\DashboardController@editprofile')->name('marketspace/editprofile');
Route::get('marketspace/hospitalcreate', 'marketspace\DashboardController@hospitalcreate')->name('marketspace/hospitalcreate');
Route::get('marketspace/servicestaffprofile', 'marketspace\DashboardController@servicestaffprofile')->name('marketspace/servicestaffprofile');
Route::get('marketspace/iblist', 'marketspace\DashboardController@iblist')->name('marketspace/iblist');
Route::get('marketspace/availabledate', 'marketspace\DashboardController@availabledate')->name('marketspace/availabledate');
Route::get('marketspace/allservicerequest', 'marketspace\DashboardController@allservicerequest')->name('marketspace/allservicerequest');
Route::get('marketspace/location', 'marketspace\DashboardController@location')->name('marketspace/location');

Route::post('marketspace/get-anotherservice-staff-form', 'marketspace\DashboardController@get_anotherservice_staff_form')->name('get-anotherservice-staff-form');
Route::get('marketspace/ibcreate', 'marketspace\DashboardController@ibcreate')->name('marketspace/ibcreate');
Route::get('marketspace/kyc', 'marketspace\DashboardController@kyc')->name('marketspace/kyc');
Route::post('marketspace/kyc_store', 'marketspace\DashboardController@kyc_store')->name('kyc_store');
Route::post('marketspace/approve_service_staff', 'marketspace\DashboardController@approve_service_staff')->name('approve_service_staff');
Route::post('marketspace/approve_service_customer', 'marketspace\DashboardController@approve_service_customer')->name('approve_service_customer');
Route::post('marketspace/complete_service_request', 'marketspace\DashboardController@complete_service_request')->name('complete_service_request');
Route::post('marketspace/reject_service_request', 'marketspace\DashboardController@reject_service_request')->name('reject_service_request');

Route::post('marketspace/reject_service_request_auth_user', 'marketspace\DashboardController@reject_service_request_auth_user')->name('reject_service_request_auth_user');
Route::post('marketspace/accept_service_request_auth_user', 'marketspace\DashboardController@accept_service_request_auth_user')->name('accept_service_request_auth_user');

Route::post('marketspace/ibstore', 'marketspace\DashboardController@ibstore')->name('ibstore');
Route::post('marketspace/available_date_store', 'marketspace\DashboardController@available_date_store')->name('available_date_store');
Route::post('marketspace/location_store', 'marketspace\DashboardController@location_store')->name('location_store');
Route::post('marketspace/available_date_update', 'marketspace\DashboardController@available_date_update')->name('available_date_update');
Route::post('marketspace/available_location_update', 'marketspace\DashboardController@available_location_update')->name('available_location_update');
Route::post('marketspace/save_service_request', 'marketspace\DashboardController@save_service_request')->name('save_service_request');
Route::post('marketspace/send_sms_message', 'marketspace\DashboardController@send_sms_message')->name('send_sms_message');

Route::get('available_date_edit/{id}', 'marketspace\DashboardController@available_date_edit')->name('available_date_edit');
Route::get('location_edit/{id}', 'marketspace\DashboardController@location_edit')->name('location_edit');

Route::get('marketspace/request-service/', 'marketspace\DashboardController@requestservice')->name('marketspace/request-service');
Route::get('marketspace/request-service-edit/{id}', 'marketspace\DashboardController@requestservice_edit')->name('marketspace/request-service-edit');
Route::post('marketspace/check_servicereq_date', 'marketspace\DashboardController@check_servicereq_date')->name('check_servicereq_date');

Route::post('marketspace/add_contact_person', 'marketspace\DashboardController@add_contact_person')->name('add_contact_person');

Route::get('viewbid/{id}', 'marketspace\DashboardController@viewbid')->name('viewbid');

Route::get('marketspace/changepassword', 'marketspace\DashboardController@changepassword')->name('marketspace/changepassword');
Route::get('marketspace/rating/{id}/{user_id}/{hire_id}', 'marketspace\DashboardController@rating')->name('marketspace/rating');

Route::post('save_rating', 'marketspace\DashboardController@save_rating')->name('save_rating');
Route::post('saveQualification', 'marketspace\DashboardController@saveQualification')->name('saveQualification');
Route::post('saveEducation', 'marketspace\DashboardController@saveEducation')->name('saveEducation');
Route::post('saveExperience', 'marketspace\DashboardController@saveExperience')->name('saveExperience');
Route::post('deleteExperience', 'marketspace\DashboardController@deleteExperience')->name('deleteExperience');
Route::post('deleteEducation', 'marketspace\DashboardController@deleteEducation')->name('deleteEducation');
Route::post('deleteTraining', 'marketspace\DashboardController@deleteTraining')->name('deleteTraining');
Route::post('deleteReference', 'marketspace\DashboardController@deleteReference')->name('deleteReference');

Route::post('saveTraining', 'marketspace\DashboardController@saveTraining')->name('saveTraining');
Route::post('saveReference', 'marketspace\DashboardController@saveReference')->name('saveReference');
Route::post('saveAbout', 'marketspace\DashboardController@saveAbout')->name('saveAbout');
Route::post('saveChangepassword', 'marketspace\DashboardController@saveChangepassword')->name('saveChangepassword');

Route::post('saveHire', 'marketspace\Marketspace_hireController@saveHire')->name('saveHire');
Route::post('chat', 'marketspace\Marketspace_hireController@chat')->name('chat');
Route::post('saveChat', 'marketspace\Marketspace_hireController@saveChat')->name('saveChat');
Route::post('statuscompleted', 'marketspace\Marketspace_hireController@statuscompleted')->name('statuscompleted');


Route::get('marketspace/mywork', 'marketspace\Marketspace_hireController@mywork')->name('marketspace/mywork');

Route::post('saveSkill', 'marketspace\DashboardController@saveSkill')->name('saveSkill');
Route::post('deleteSkill', 'marketspace\DashboardController@deleteSkill')->name('deleteSkill');
Route::post('saveAccount', 'marketspace\DashboardController@saveAccount')->name('saveAccount');

Route::post('searchproduct_typemarketspace', 'marketspace\DashboardController@searchproduct_typemarketspace')->name('searchproduct_typemarketspace');
Route::post('searchcategory_typemarketspace', 'marketspace\DashboardController@searchcategory_typemarketspace')->name('searchcategory_typemarketspace');
Route::post('search_request_service', 'marketspace\DashboardController@search_request_service')->name('search_request_service');

Route::post('saveEditprofile', 'marketspace\DashboardController@saveEditprofile')->name('saveEditprofile');
Route::post('savehospital', 'marketspace\DashboardController@savehospital')->name('savehospital');

Route::post('deleteprofilephoto', 'marketspace\DashboardController@deleteprofilephoto')->name('deleteprofilephoto');
Route::post('/partOneSaveImage', 'marketspace\DashboardController@partOneSaveImage')->name('partOneSaveImage');
Route::post('/pan_image', 'marketspace\DashboardController@pan_image')->name('pan_image');
Route::post('/adhar_image', 'marketspace\DashboardController@adhar_image')->name('adhar_image');
Route::post('/exp_cert', 'marketspace\DashboardController@exp_cert')->name('exp_cert');
Route::post('/edu_cert', 'marketspace\DashboardController@edu_cert')->name('edu_cert');
Route::post('/quali_cert', 'marketspace\DashboardController@quali_cert')->name('quali_cert');
Route::post('change_country', 'marketspace\DashboardController@change_country')->name('change_country');
Route::post('change_state', 'marketspace\DashboardController@change_state')->name('change_state');
;
Route::post('change_district', 'marketspace\DashboardController@change_district')->name('change_district');
Route::post('change_taluk', 'marketspace\DashboardController@change_taluk')->name('change_taluk');
Route::post('ajaxChangeStatus', 'marketspace\DashboardController@ajaxChangeStatus')->name('ajaxChangeStatus');


/*******************************  Dealer Control Start************************************************/
Route::get('dealer', 'dealer\DealerController@index');
Route::post('dealer', 'dealer\DealerController@login');
Route::get('dealer/register', function () {
  return view('dealer.register');
})->name('dealer.register');
Route::post('dealer/register', 'dealer\DealerController@register');

Route::middleware(DealerAuthenticate::class)->prefix('dealer')->name('dealer.')->group(
  function () {

    Route::get('logout', 'dealer\DealerController@logout');
    Route::get('change-password', 'dealer\DealerController@changePassword');
    Route::get('profile/emailverify', 'dealer\ProfileController@emailverify')->name('profile.emailverify');

    Route::post('profile/emailverify', 'dealer\ProfileController@emailverify')->name('profile.emailverify');

    Route::put('profile/upload', 'dealer\ProfileController@upload')->name('profile.upload');

    Route::resource('products', 'dealer\ProductController');
    Route::resource('profile', 'dealer\ProfileController');



  }
);
/*******************************  Dealer Control End************************************************/

Route::middleware(MarketspaceAuthenticate::class)->prefix('marketspace')->name('marketspace.')->group(
  function () {
    /***********************************marketspace Admin control****************************************************/





  }
);
/***********************************Admin Login Start****************************************************/
Route::get('admin', 'admin\AdminController@index');
Route::post('admin', 'admin\AdminController@login');
Route::get('admin/logout', 'admin\AdminController@logout');
Route::get('admin/sql', 'admin\AdminController@sql');
/***********************************Admin Login Start****************************************************/
Route::resource('service_responce', 'staff\Service_responceController');
Route::post('service_responce/deleteAll', 'staff\Service_responceController@deleteAll');
Route::resource('service_visit', 'staff\Service_visitController');
Route::post('service_visit/deleteAll', 'staff\Service_visitController@deleteAll');
Route::resource('create_quote', 'admin\QuoteCreateController');
/***********************************Staff Login Start****************************************************/
Route::get('staff', 'staff\StaffController@index'); //->name('contact.create');
Route::post('stafflogin', 'staff\StaffController@stafflogin');
Route::get('staff/logout', 'staff\StaffController@logout');
/***********************************Staff Login End****************************************************/
Route::get('elfinder/ckeditor', '\Barryvdh\Elfinder\ElfinderController@showCKeditor4');
Route::get('elfinder/connector', '\Barryvdh\Elfinder\ElfinderController@showConnector');
Route::post('elfinder/connector', '\Barryvdh\Elfinder\ElfinderController@showConnector');
Route::middleware(StaffAuthenticate::class)->prefix('staff')->name('staff.')->group(
  function () {


    Route::get(
      'android/download',
      function () {
        // $file= public_path("/apk/v1/Beczone-v114.apk");
        $applatest = AppVersionControll::where("vid", "android")->orderBy("id", "DESC")->first();
        $file = storage_path() . "/app/public/android/" . $applatest->filename;
        $vcode = $applatest->code . "";
        return response()->download($file, "Beczone-v" . ($vcode != "" ? $vcode[0] : 1) . ".apk", ['Content-Type' => 'application/vnd.android.package-archive']);
      }
    )->name(
        'android.download'
      );

    Route::resource('/manage-task', 'staff\TaskManageController');

    Route::post('view_task_comment_staff', 'staff\AdminStaffController@view_task_comment_staff')->name('view_task_comment_staff');

    Route::post('add_task_replay_comment_staff', 'staff\AdminStaffController@add_task_replay_comment_staff')->name('add_task_replay_comment_staff');

    Route::post('view_task_details_staff', 'staff\AdminStaffController@view_task_details_staff');

    Route::post('save_expence_edit_details_staff', 'staff\AdminStaffController@save_expence_edit_details_staff')->name('save_expence_edit_details_staff');

    Route::post('save_travel_edit_details_staff', 'staff\AdminStaffController@save_travel_edit_details_staff');

    Route::post('delete_admin_generate_leave_staff', 'staff\AdminStaffController@delete_admin_generate_leave_staff');

    Route::get('/manage-staff-task-location_staff', 'staff\AdminStaffController@staff_task_location_staff')->name('manage-staff-task-location_staff');

    Route::get('/manage-staff-task-time_staff', 'staff\TaskManageController@staff_task_time_staff')->name('manage-staff-task-time_staff');

    Route::get('freez/date', 'staff\TaskController@freezdate')->name('freezdate');

    Route::get('WorkReport', 'staff\TaskController@WorkReport')->name('WorkReport');

    Route::get('work/WorkReportLoad', 'staff\TaskController@WorkReportLoad')->name('WorkReportLoad');

    Route::get('work/workcalender', 'staff\TaskController@workcalender')->name('workcalender');
    
    Route::get('OppertunityWork', 'staff\TaskController@OppertunityWork')->name('OppertunityWork');

    Route::get('car_permission', 'staff\TaskController@carPermission')->name('car_permission');
 
    Route::get('Staffstatus', 'staff\TaskController@Staffstatus')->name('Staffstatus');

    /***********************************Staff Admin control****************************************************/
    Route::get('dashboard', 'staff\StaffController@dashboard')->name('dashboard');
    Route::get('change-password', 'staff\StaffController@changePassword');
    Route::post('change-password', 'staff\StaffController@updatePassword');
    /***********************************Staff Admin control****************************************************/
    Route::resource('dailyclosing_details', 'staff\Dailyclosing_detailsController');
    /***********************************Leadoption Staff start****************************************************/
    Route::get('staff_lead_option', 'staff\LeadoptionController@index')->name('lead_option_staff');
    Route::get('cancel_lead_option/{id}', 'staff\LeadoptionController@cancel')->name('cancel_lead_option_staff');
    Route::get('convert_opportunity/{id}', 'staff\LeadoptionController@convert')->name('convert_opportunity');
    Route::get('create_lead_option', 'staff\LeadoptionController@create')->name('create_lead_option');
    Route::post('create_lead_option', 'staff\LeadoptionController@insert');
    Route::get('edit_lead_option/{id}', 'staff\LeadoptionController@edit')->name('edit_lead_option');
    Route::post('edit_lead_option/{id}', 'staff\LeadoptionController@update');
    Route::get('loadcontacts/{id}', 'staff\LeadoptionController@loadcontacts')->name('loadcontacts');
    Route::get('my_lead_option', 'staff\LeadoptionController@my_lead')->name('my_lead_option');
    /***********************************Leadoption Staff end****************************************************/

    /***********************************Service Task start****************************************************/
    Route::get('serviceTask', 'staff\ServiceTaskController@index')->name('serviceTask-index');
    /***********************************Service Task start****************************************************/
  
    /***********************************Service staff start****************************************************/

    Route::get('service/{id}', 'staff\ServiceController@index')->name('service-index');
    Route::get('service-create/{id}', 'staff\ServiceController@create')->name('service-create');

    Route::get('service-staffIndex', 'staff\ServiceController@staffIndex')->name('service-staffIndex');
    // Route::get('ib-create', 'admin\IbController@create')->name('ib-create');
    Route::post('service-store', 'staff\ServiceController@store')->name('service-store');
    Route::post('service-destroy', 'staff\ServiceController@destroy')->name('service-destroy');
    Route::get('service-edit/{id}', 'staff\ServiceController@edit')->name('service-edit');
    Route::get('service-detail/{id}', 'staff\ServiceController@serviceDetail')->name('service-detail');

    Route::post('service-update/{id}', 'staff\ServiceController@update')->name('service-update');
    Route::post('service-newTask', 'staff\ServiceController@serviceNewTask')->name('service-newTask');

    Route::post('service-newVisitTask', 'staff\ServiceController@newServiceVistTask')->name('service-newVisitTask');
    Route::post('service-callResponse', 'staff\ServiceController@serviceCallResponse')->name('service-callResponse');
    Route::post('service-RequestPart', 'staff\ServiceController@serviceRequestPart')->name('service-RequestPart');

    Route::get('service-partApprove/{id}', 'staff\ServiceController@servicePartApprove')->name('service-partApprove');
    Route::get('service-techApprove/{id}', 'staff\ServiceController@serviceTechApprove')->name('service-techApprove');

    Route::post('service-partDelete', 'staff\ServiceController@servicePartDelete')->name('service-partDelete');
    Route::get('service-task-delete/{id}', 'staff\ServiceController@serviceTaskDelete')->name('service-task-delete');


    Route::get('service-createOppertunity/{id}', 'staff\ServiceController@serviceCreateOppertunity')->name('service-createOppertunity');


    Route::post('service-product_order', 'staff\ServiceController@serviceProductOrder')->name('service-product_order');

    Route::post('service_add_part', 'staff\ServiceController@serviceAddProduct')->name('service_add_part');
    Route::post('service-technical_support', 'staff\ServiceController@serviceTechnicalSupport')->name('service-technical_support');

    Route::get('service-Approve/{id}', 'staff\ServiceController@serviceApprove')->name('service-Approve');

    Route::get('pm_approve/{id}', 'staff\ServiceController@pm_approve')->name('pm_approve');

    Route::get('service-Complete/{id}', 'staff\ServiceController@serviceComplete')->name('service-Complete');


    Route::post('service-feedback', 'staff\ServiceController@serviceFeedback')->name('service-feedback');

    Route::post('service-response-reply', 'staff\ServiceController@addReplyToTaskComment')->name('service-response-reply');

    Route::post('service-response-details', 'staff\ServiceController@taskResponseDetails')->name('service-response-details');


    Route::post('service-audit', 'staff\ServiceController@serviceAudit')->name('service-audit');
    Route::post('service-uploadRemark', 'staff\ServiceController@serviceUploadRemark')->name('service-uploadRemark');

    Route::get('service-response-history/{id}', 'staff\ServiceController@serviceResponseHistory')->name('service-response-history');


    /***********************************Service staff start****************************************************/

    /***********************************Contact Customer start****************************************************/
    Route::post('service_task_staff', 'staff\ServiceController@serviceTaskStaff');
    // Route::post('service_approve','staff\ServiceController@serviceApprove');
    Route::post('service_TaskDetails', 'staff\ServiceController@serviceTaskDetails');
    Route::post('customer_contact_person', 'staff\ServiceController@customerContactEquipment');
    Route::post('customer_contact_detail', 'staff\ServiceController@customerContactDetail');

    Route::post('equipment_serial', 'staff\ServiceController@equipmentSerial');
    Route::post('equipment_detail', 'staff\ServiceController@equipmentDetail');

    /***********************************IB staff start****************************************************/

    Route::get('ib', 'staff\IbController@index')->name('ib-index');
    Route::get('ib-create', 'staff\IbController@create')->name('ib-create');
    Route::post('ib-store', 'staff\IbController@store')->name('ib-store');
    Route::get('ib-destroy/{id}', 'staff\IbController@destroy')->name('ib-destroy');
    Route::delete('ib-destroy/{id}', 'staff\IbController@destroy')->name('ib-destroy');
    Route::get('ib-edit/{id}', 'staff\IbController@edit')->name('ib-edit');
    Route::post('ib-update/{id}', 'staff\IbController@update')->name('ib-update');
    //Route::post('ib-import', 'admin\IbController@import')->name('ib-import');
    Route::post('/addproduct', 'ProductController@addproduct')->name('addproduct');

    Route::post('/check_equp_no', 'staff\IbController@check_equp_no')->name('check_equp_no');



    Route::get('import-ib', 'staff\StaffImportController@index')->name('staff_import_ib');

    Route::get('import-edit/{id}', 'staff\StaffImportController@edit')->name('import-edit-staff');

    Route::post('import-update/{id}', 'staff\StaffImportController@import_update')->name('import_update-staff');

    // Route::get('import-destroy/{id}', 'staff\StaffImportController@destroy')->name('import-destroy-staff');
  
    Route::delete('import-destroy/{id}', 'staff\StaffImportController@destroy')->name('import-destroy-staff');


    /***********************************IB staff End****************************************************/

    /***********************************Contract staff start****************************************************/

    Route::get('contract', 'staff\ContractController@index')->name('contract-index');
    Route::get('contract-create', 'staff\ContractController@create')->name('contract-create');
    Route::post('contract-store', 'staff\ContractController@store')->name('contract-store');
    Route::get('contract-destroy/{id}', 'staff\ContractController@destroy')->name('contract-destroy');
    Route::get('contract-edit/{id}', 'staff\ContractController@edit')->name('contract-edit');
    Route::post('contract-update/{id}', 'staff\ContractController@update')->name('contract-update');
    Route::post('contract-product', 'staff\ContractController@contractProduct')->name('contract-product');
    Route::post('contract-oppertunity', 'staff\ContractController@contractOppertunity')->name('contract-oppertunity');
    Route::post('contract-quote', 'staff\ContractController@contractOppertunityQuote')->name('contract-quote');
    Route::post('contract-quote-product', 'staff\ContractController@contractOppertunityQuoteProduct')->name('contract-quote-product');

    Route::get('oppertunity_contact/{id}', 'staff\ContractController@oppertunity_contact')->name('oppertunity_contact');
    Route::get('view_oppertunity_contact/{id}', 'staff\ContractController@view_oppertunity_contact')->name('view_oppertunity_contact');
    Route::get('oppertunity_contactsales/{id}', 'staff\ContractSalesController@oppertunity_contactsales')->name('oppertunity_contactsales');

    Route::get('view_sales_contract/{id}', 'staff\ContractSalesController@view_sales_contract')->name('view_sales_contract');

        
    Route::post('create_bill', 'staff\ContractSalesController@create_bill')->name('create_bill');
    

    Route::post('fetch_optional_products', 'staff\ContractSalesController@fetch_optional_products')->name('fetch_optional_products');

    Route::post('reject-opportunity', 'staff\ContractSalesController@rejectOpportunity')->name('reject.opportunity');

    Route::post('oppertunity_store', 'staff\ContractController@oppertunity_store')->name('oppertunity_store');
    Route::post('oppertunity_storesales', 'staff\ContractSalesController@oppertunity_storesales')->name('oppertunity_storesales');

    Route::post('customer-equpment-details', 'staff\ContractController@getequpmentdetails')->name('customer-equpment-details');

    Route::post('/get-opportunities-by-user', 'staff\ContractController@getOpportunitiesByUser')->name('getOpportunitiesByUser');

    Route::post('/get-products-by-opportunity', 'staff\ContractController@getProductsByOpportunity')->name('getProductsByOpportunity');

    /****************************************************pm order *************************************************** */

    Route::get('pm_order/{id}', 'staff\PmOrderController@pm_order')->name('pm_order');

    Route::get('pm_order', 'staff\PmOrderController@index')->name('pm_order.index');

    Route::get('sales', 'staff\PmOrdersalesController@index')->name('sales.index');

    Route::post('verify_sales', 'staff\PmOrdersalesController@verify_sales')->name('verify_sales');

    Route::post('update_payment', 'staff\PmOrdersalesController@update_payment')->name('update_payment');

    Route::post('update_comment', 'staff\PmOrdersalesController@update_comment')->name('update_comment');

    Route::post('pm_order_submit', 'staff\PmOrderController@pm_order_submit')->name('pm_order_submit');

    Route::get('pm_create/{id}', 'staff\PmCreateController@pm_create')->name('pm_create');

    Route::get('show_pmdetails', 'staff\PmCreateController@show_pmdetails')->name('show_pmdetails');

    Route::get('show_feed_back', 'staff\PmCreateController@show_feed_back')->name('show_feed_back');
    
    Route::get('pm_servide_edit/{id}', 'staff\PmCreateController@pm_servide_edit')->name('pm_servide_edit');
    // Route::post('destroypm', 'staff\PmCreateController@destroypm')->name('destroypm');
    // Route::post('destroypm/{id}', 'staff\PmCreateController@destroypm')->name('destroypm');
    Route::post('destroypm', 'staff\PmCreateController@destroypm')->name('destroypm');



    //Route::post('ib-import', 'admin\IbController@import')->name('ib-import');
  
    /***********************************Contract staff End****************************************************/

    /***********************************Staff Oppertunity start****************************************************/
    Route::post('view_oppertunity_products', 'staff\OppertunityController@viewOppertunityProductModal')->name('view_oppertunity_products');
    Route::get('list_oppertunity', 'staff\OppertunityController@index')->name('list_oppertunity');
    Route::get('create_oppertunity', 'staff\OppertunityController@create')->name('create_oppertunity');
    Route::post('create_oppertunity', 'staff\OppertunityController@insert');
    Route::get('edit_oppertunity/{id}', 'staff\OppertunityController@edit')->name('edit_oppertunity');
    Route::post('edit_oppertunity/{id}', 'staff\OppertunityController@update');
    Route::post('delete_oppertunity', 'staff\OppertunityController@delete');
    Route::post('delete_oppertunity', 'staff\OppertunityController@delete_oppertunity')->name('delete_oppertunity');
    Route::get('view_customer/{id}', 'staff\OppertunityController@view_customer')->name('view_customer');
    Route::get('view_contact/{id}', 'staff\OppertunityController@view_contact')->name('view_contact');
    Route::get('list_oppertunity_products/{id}', 'staff\OppertunityController@list_products')->name('list_oppertunity_products');

    Route::post('save-comment', 'staff\OppertunityController@saveComment')->name('save.comment');

 
    Route::get('preview_products', 'staff\OppertunityController@preview_products')->name('preview_products');

    Route::get('get_quote_product', 'staff\OppertunityController@get_quote_product')->name('get_quote_product');
    

    Route::post('delete_opp_product', 'staff\OppertunityController@delete_opp_product')->name('delete_opp_product');

    Route::post('update_product_status', 'staff\OppertunityController@update_product_status')->name('update_product_status');

    

    Route::get('getProductsByBrand', 'staff\OppertunityController@getProductsByBrand');

    Route::get('create_oppertunity_product/{id}', 'staff\OppertunityController@add_product');
    Route::post('create_oppertunity_product/{id}', 'staff\OppertunityController@insert_product');
    Route::post('delete_oppertunity_product', 'staff\OppertunityController@delete_product');
    Route::get('delete_oppertunity_eachproduct/{id}/{op_id}', 'staff\OppertunityController@delete_oppertunity_eachproduct');
    Route::get('edit_oppertunity_product/{id}/{op_id}', 'staff\OppertunityController@edit_product');
    Route::post('edit_oppertunity_product/{id}/{op_id}', 'staff\OppertunityController@update_product');
    Route::post('/oppertunity_product_detail', 'staff\OppertunityController@oppertunity_product_detail')->name('oppertunity_product_detail');
    Route::post('generate_quote', 'staff\OppertunityController@generate_quote');
    Route::get('preview_quote/{id}', 'staff\OppertunityController@quotepdf');
    Route::get('preview_contract_quote/{id}', 'staff\OppertunityController@contractQuotePdf');
    Route::get('send_quote/{id}', 'staff\OppertunityController@sendquote');
    Route::post('quote_send', 'staff\OppertunityController@quote_send');
    Route::post('send_mail', 'staff\OppertunityController@send_mail');
    Route::get('prospectus/{id}', 'staff\OppertunityController@prospectus')->name('prospectus');
    Route::post('delete_prospectus', 'staff\OppertunityController@delete_prospectus');
    Route::get('update_prospectus/{id}', 'staff\OppertunityController@update_prospectus')->name('update_prospectus');
    Route::post('update_prospectus/{id}', 'staff\OppertunityController@store_prospectus');
    Route::post('chatterdetail', 'staff\OppertunityController@chatterdetail');
    Route::post('chattersave', 'staff\OppertunityController@chattersave');
    Route::get('loadproductnames/{id}', 'staff\OppertunityController@loadproductnames')->name('loadproductnames');
    Route::get('approve_quote', 'staff\OppertunityController@approve_quote')->name('approve_quote');
    Route::post('approve_quote_history', 'staff\OppertunityController@approve_quote_history');
    Route::post('delete_quote_history', 'staff\OppertunityController@delete_quote_history');
    Route::post('save_quote_terms', 'staff\OppertunityController@save_quote_terms');
    Route::post('get_oppurtunitydetails', 'staff\AdminajaxController@get_oppurtunitydetails');
    Route::post('get_opportunity_all_details_transation', 'admin\AdminajaxController@get_opportunity_all_details_transation')->name('get_opportunity_all_details_transation');
    Route::post('oppertunity_contract_product', 'staff\OppertunityController@oppertunityContractProduct')->name('oppertunity_contract_product');
    Route::post('oppertunity_contract_list_product', 'staff\OppertunityController@oppertunityContractListProduct')->name('oppertunity_contract_list_product');
    Route::post('oppertunity_contract_product_store', 'staff\OppertunityController@oppertunityContractProductStore')->name('oppertunity_contract_product_store');
    Route::post('edit_oppertunity_contract_product', 'staff\OppertunityController@updateContractProduct')->name('edit_oppertunity_contract_product');
    Route::post('oppertunity/{id}/quote/won', 'staff\OppertunityController@quotewonupdate')->name('quote.won');
    Route::post('oppertunity/data/clone', 'staff\OppertunityController@oppertunityClone')->name('oppertunity.clone');
    Route::post('oppertunity/{id}/close/won', 'staff\OppertunityController@wonCloseOppertunity')->name('oppertunity.closeWon');
    Route::post('oppertunity/{id}/close/cancell', 'staff\OppertunityController@cancellCloseOppertunity')->name('oppertunity.closeCancell');
    Route::post('oppertunity/{id}/close/lost', 'staff\OppertunityController@lossCloseOppertunity')->name('oppertunity.closeLost');

    Route::post('opportunity/addon/product_store', 'staff\OppertunityController@addon_product_store')->name('addon.product_store');



    /***********************************Staff Oppertunity end****************************************************/
    /***********************************Quote  Start****************************************************/
    Route::resource('quote', 'staff\StaffquoteController');
    Route::post('quote/deleteAll', 'staff\StaffquoteController@deleteAll');
    Route::get('quotepdf/{id}', 'staff\StaffquoteController@quotepdf')->name('quotepdf');
    Route::get('sendquote/{id}', 'staff\StaffquoteController@sendquote')->name('sendquote');
    /***********************************Quote  End****************************************************/
    /***********************************Product Staff Star   t****************************************************/

    Route::resource('products', 'staff\ProductController');

    Route::post('modality_change', 'staff\ProductController@modality_change')->name('modality_change');

    Route::post('products/deleteAll', 'staff\ProductController@deleteAll');

    /***********************************Product Staff  End****************************************************/
    /***********************************Brand Staff Start****************************************************/
    Route::resource('brand', 'staff\BrandController');
    Route::post('brand/deleteAll', 'staff\BrandController@deleteAll');
    /***********************************Brand Staff End****************************************************/
    /***********************************Category type Product staff Start***********************************************/
    Route::resource('category_type', 'staff\Category_typeController');
    Route::post('category_type/deleteAll', 'staff\Category_typeController@deleteAll');
    /***********************************Category type Product  Staff End****************************************************/
    /***********************************Product Category  Start****************************************************/
    Route::resource('category', 'staff\CategoryController');
    Route::post('category/deleteAll', 'staff\CategoryController@deleteAll');
    /***********************************Product Category  End****************************************************/
    /***********************************Modality Product Start****************************************************/
    Route::resource('modality', 'staff\ModalityController');
    Route::post('modality/deleteAll', 'staff\ModalityController@deleteAll');
    /***********************************Modality Product  End****************************************************/
    /***********************************Competition Product  End****************************************************/
    Route::resource('competition_product', 'staff\Competition_productController');
    Route::post('competition_product/deleteAll', 'staff\Competition_productController@deleteAll');
    /***********************************Competition Product  End****************************************************/
    /***********************************Product Type End****************************************************/
    Route::resource('product_type', 'staff\Product_typeController');
    Route::post('product_type/deleteAll', 'staff\Product_typeController@deleteAll');
    /***********************************Product Type End****************************************************/
    Route::resource('service_task', 'staff\Service_taskController');
    Route::post('service_task/deleteAll', 'staff\Service_taskController@deleteAll');
    /***********************************Order Staff Start****************************************************/
    Route::get('list_order', 'staff\OrderController@index')->name('list_order');
    Route::get('create_order', 'staff\OrderController@create')->name('create_order');
    Route::post('create_order', 'staff\OrderController@insert');
    Route::post('delete_order', 'staff\OrderController@delete_order');
    Route::post('orderdetail', 'staff\OrderController@orderdetail');
    Route::get('edit_order/{id}', 'staff\OrderController@edit')->name('create_order');
    Route::post('update_order', 'staff\OrderController@update');
    Route::post('delete_order_product', 'staff\OrderController@delete_order_product');
    Route::post('save_order_gov_sale', 'staff\OrderController@save_order_gov_sale');
    Route::post('save_order_export_sale', 'staff\OrderController@save_order_export_sale');
    Route::post('save_order_purchase_sale', 'staff\OrderController@save_order_purchase_sale');
    Route::post('save_order_registered_sale', 'staff\OrderController@save_order_registered_sale');
    Route::post('save_order_unregistered_sale', 'staff\OrderController@save_order_unregistered_sale');
    Route::post('save_order_tender_sale', 'staff\OrderController@save_order_tender_sale');
    Route::get('add_order_product/{id}', 'staff\OrderController@add_order_product')->name('add_order_product');
    Route::post('add_order_product/{id}', 'staff\OrderController@insert_order_product');
    /***********************************Order Staff ENd****************************************************/

    /***********************************Invoice  Start****************************************************/

    Route::resource('invoice', 'staff\InvoiceController');
    Route::post('invoice/deleteAll', 'staff\InvoiceController@deleteAll');
    Route::post('change_purchase_in_out', 'staff\InvoiceController@change_purchase_in_out');
    Route::post('get_inout_details_search', 'staff\InvoiceController@get_inout_details_search');
    Route::post('get_sales_outout_details_search', 'staff\InvoiceController@get_sales_outout_details_search');
    Route::post('get_transaction_details_for_invoice', 'staff\InvoiceController@get_transaction_details_for_invoice');
    Route::post('get_dispatch_details_for_invoice', 'staff\InvoiceController@get_dispatch_details_for_invoice');

    Route::post('get_transaction_product_qty_check_invoice', 'staff\InvoiceController@get_transaction_product_qty_check_invoice');
    Route::get('preview_invoice/{id}', 'staff\InvoiceController@preview_invoice');

    Route::post('check_invoice_id_exit', 'staff\InvoiceController@check_invoice_id_exit');


    /***********************************Invoice  End****************************************************/

    /***********************************Dispatch  Start****************************************************/
    Route::resource('dispatch', 'staff\DispatchController');
    Route::post('dispatch/deleteAll', 'staff\DispatchController@deleteAll');
    Route::post('get_transaction_for_dispatch', 'staff\DispatchController@get_transaction_for_dispatch')->name('get_transaction_for_dispatch');
    /***********************************Invoice  End****************************************************/


    /***********************************Transation  Start****************************************************/
    Route::resource('transation', 'staff\TransationController');
    Route::post('transation/deleteAll', 'staff\TransationController@deleteAll');
    Route::post('save_shipping_address_user', 'staff\TransationController@save_shipping_address_user')->name('save_shipping_address_user');
    Route::post('select_shipping_address_user', 'staff\TransationController@select_shipping_address_user')->name('select_shipping_address_user');
    Route::post('approval_transation', 'staff\TransationController@approval_transation');
    Route::post('delete_product_transation', 'staff\TransationController@delete_product_transation');
    Route::post('save_transation_insentive', 'staff\TransationController@save_transation_insentive');
    Route::post('approval_transation_mspowner', 'staff\TransationController@approval_transation_mspowner');
    Route::post('update_qty_transation', 'staff\TransationController@update_qty_transation');
    Route::post('change_transation_type_oppurtunity', 'staff\TransationController@change_transation_type_oppurtunity');
    Route::post('save_config_transation', 'staff\TransationController@save_config_transation');
    Route::post('save_po_transation', 'staff\TransationController@save_po_transation');
    Route::post('save_certifi_transation', 'staff\TransationController@save_certifi_transation');
    Route::post('save_payment_transation', 'staff\TransationController@save_payment_transation');
    Route::post('save_delivery_transation', 'staff\TransationController@save_delivery_transation');
    Route::post('save_other_transation', 'staff\TransationController@save_other_transation');
    Route::post('view_transation_all_product', 'staff\TransationController@view_transation_all_product');
    Route::post('get_sort_product_transaction', 'staff\TransationController@get_sort_product_transaction');
    Route::post('approval_transaction_staff', 'staff\TransationController@approval_transaction_staff');

    Route::get('create_dispatch/{id}', 'staff\TransationController@create_dispatch')->name('create_dispatch');
    Route::get('dispatch_verify/{id}', 'staff\TransationController@dispatch_verify')->name('dispatch_verify');
    Route::get('dispatch_verify_view/{id}', 'staff\TransationController@dispatch_verify_view')->name('dispatch_verify_view');

    Route::post('delivery_approve', 'staff\TransationController@delivery_approve')->name('delivery_approve');
    Route::post('after_user_approve', 'staff\TransationController@after_user_approve')->name('after_user_approve');
    Route::post('get_user_contact_details', 'staff\TransationController@get_user_contact_details')->name('get_user_contact_details');

    Route::get('sales_order', 'staff\TransationController@sales_order')->name('sales_order');
    Route::get('transactionindex', 'staff\TransationController@transactionindex')->name('transactionindex');
    Route::get('Pendingtransaction', 'staff\TransationController@Pendingtransaction')->name('Pendingtransaction');

    Route::get('transation_details/{id}', 'staff\TransationController@transation_details')->name("transation_details");

    Route::post('get_test_retun_product', 'staff\TransationController@get_test_retun_product');
    Route::post('get_all_product', 'staff\TransationController@get_all_product');
    /***********************************Transation End****************************************************/


    /***********************************Dispatch End****************************************************/
    Route::resource('dispatch', 'staff\DispatchController');
    Route::post('dispatch/deleteAll', 'staff\DispatchController@deleteAll');
    Route::post('dispatch_approval_update', 'staff\DispatchController@dispatch_approval_update')->name('dispatch_approval_update');
    Route::get('cron_for_create_updateinvoice_status', 'staff\DispatchController@cron_for_create_updateinvoice_status')->name('cron_for_create_updateinvoice_status');

    /***********************************Dispatch Start****************************************************/

     /***********************************Customer Location ****************************************************/

    Route::get('/customer-location','staff\CustomerLocationController@index')->name('customer_location');

    Route::post('/staff/location/state','staff\CustomerLocationController@staff_location_state')->name('staff_location_state');

    Route::post('/staff/location/district','staff\CustomerLocationController@staff_location_district')->name('staff_location_district');
    
    Route::post('/staff/location/taluk','staff\CustomerLocationController@staff_location_taluk')->name('staff_location_taluk');
    
    Route::post('/staff/location/customer','staff\CustomerLocationController@staff_location_customer')->name('staff_location_customer');
    
    Route::post('/staff/location/save','staff\CustomerLocationController@cusomer_loacation_save')->name('cusomer_loacation_save');
    
    Route::post('/staff/location/brand','staff\CustomerLocationController@staff_location_brand')->name('staff_location_brand');

    Route::get('/staff/location/undo','staff\CustomerLocationController@undo_action')->name('undo_action');
    
    /***********************************End of Customer Location ****************************************************/

    /***********************/
    Route::get('asset', 'staff\AssetController@index')->name('asset');
    Route::get('createasset', 'staff\AssetController@show')->name('asset.show');
    Route::post('asset/show', 'staff\AssetController@store')->name('asset.store');
    Route::get('asset/edit/{id}', 'staff\AssetController@edit')->name('asset.edit');
    Route::post('asset/update/{id}', 'staff\AssetController@update')->name('asset.update');
    Route::post('asset/save', 'staff\AssetController@save')->name('asset.save');
    Route::delete('asset/destroy/{id}', 'staff\AssetController@destroy');
    Route::post('asset/deleteAll', 'staff\AssetController@deleteAll');
    Route::post('get_contact_details', 'staff\AdminajaxController@get_contact_details');
    Route::post('get_user_contact_list', 'staff\AdminajaxController@get_user_contact_list');

    Route::post('save_first_responce', 'staff\AdminajaxController@save_first_responce');
    Route::post('save_visit', 'staff\AdminajaxController@save_visit');
    Route::post('save_part', 'staff\AdminajaxController@save_part');
    Route::post('edit_part', 'staff\AdminajaxController@edit_part');
    Route::post('edit_first_responce', 'staff\AdminajaxController@edit_first_responce');
    Route::post('edit_visit', 'staff\AdminajaxController@edit_visit');
    Route::get('AllTaskservice', 'staff\Service_taskController@AllTaskservice')->name('AllTaskservice');
    Route::resource('task', 'staff\TaskController');


    Route::post('productimagegallery', 'staff\AdminajaxController@productimagegallery');
    Route::post('get_productimagegallery', 'staff\AdminajaxController@get_productimagegallery');
    Route::post('delete_productimagegallery', 'staff\AdminajaxController@delete_productimagegallery');
    /***********************************Customer Mange ****************************************************/
    Route::get('changestatus', 'staff\UserController@changestatus')->name('changestatus');
    Route::resource('customer', 'staff\UserController');
    Route::get('/{id}', 'staff\UserController@contact')->name('contact');
    Route::post('customer/deleteAll', 'staff\UserController@deleteAll');
    /***********************************Customer Mange End****************************************************/

    Route::post('change_assigned_team', 'staff\AdminajaxController@change_assigned_team');
    Route::post('change_related_to', 'staff\AdminajaxController@change_related_to');
    Route::post('change_task_status', 'staff\AdminajaxController@change_task_status');
    Route::post('change_task_status_total_task', 'staff\AdminajaxController@change_task_status_total_task');
    Route::post('change_task_priority', 'staff\AdminajaxController@change_task_priority');
    Route::post('viewchecklist_details', 'staff\AdminajaxController@viewchecklist_details');
    Route::post('add_task_replay_comment', 'staff\AdminajaxController@add_task_replay_comment');
    Route::post('add_daily_closing', 'staff\AdminajaxController@add_daily_closing');
    Route::any('get_product_company', 'staff\AdminajaxController@get_product_company');
    Route::any('get_product_all_details', 'staff\AdminajaxController@get_product_all_details');
    Route::any('get_multiple_product_all_details', 'staff\AdminajaxController@get_multiple_product_all_details');
    Route::any('generate_pdf', 'staff\AdminajaxController@generate_pdf');
    Route::any('delete_product_staff', 'staff\AdminajaxController@delete_product_staff');
    Route::get('loadproductnames/{id}', 'admin\OppertunityController@loadproductnames')->name('loadproductnames');

    /**************** *******************Task staff start ****************************************************/


    Route::post('task/deleteAll', 'staff\TaskController@deleteAll');
    Route::get('inprogressTask', 'staff\TaskController@inprogressTask')->name('inprogressTask');
    Route::get('completeTask', 'staff\TaskController@completeTask')->name('completeTask');
    Route::get('pendingTask', 'staff\TaskController@pendingTask')->name('pendingTask');
    Route::get('approvedTask', 'staff\TaskController@approvedTask')->name('approvedTask');
    Route::get('AllTask', 'staff\TaskController@AllTask')->name('AllTask');
    Route::get('verify', 'staff\TaskController@verify')->name('verify');
    Route::get('dailyclosing', 'staff\TaskController@dailyclosing')->name('dailyclosing');

    Route::get('quicktask', 'staff\TaskController@quicktask')->name('quicktask');
    Route::get('quicktaskcreate', 'staff\TaskController@quicktaskcreate')->name('quicktaskcreate');
    Route::post('tasksave', 'staff\TaskController@tasksave')->name('tasksave');
    Route::post('change_user_get_contact_person', 'staff\TaskController@change_user_get_contact_person')->name('change_user_get_contact_person');
    Route::get('WorkReporttest', 'staff\TaskController@WorkReporttest')->name('WorkReporttest');

    Route::post('view_travel_all_details', 'staff\TaskController@view_travel_all_details')->name('view_travel_all_details');
    Route::post('get_date_sort_task', 'staff\TaskController@get_date_sort_task')->name('get_date_sort_task');
    Route::post('get_hospital_sort_task', 'staff\TaskController@get_hospital_sort_task')->name('get_hospital_sort_task');
    Route::post('get_hospital_sortbytravel_task', 'staff\TaskController@get_hospital_sortbytravel_task')->name('get_hospital_sortbytravel_task');
    Route::post('get_all_hospital_list', 'staff\TaskController@get_all_hospital_list')->name('get_all_hospital_list');
    Route::post('save_field_staff_moretask', 'staff\TaskController@save_field_staff_moretask')->name('save_field_staff_moretask');
    Route::post('get_current_monthdates', 'staff\TaskController@get_current_monthdates')->name('get_current_monthdates');
    Route::post('save_start_travel', 'staff\TaskController@save_start_travel')->name('save_start_travel');
    Route::post('save_staff_expence', 'staff\TaskController@save_staff_expence')->name('save_staff_expence');
    Route::post('save_staff_expence_office', 'staff\TaskController@save_staff_expence_office')->name('save_staff_expence_office');
    Route::post('save_staff_workleave', 'staff\TaskController@save_staff_workleave')->name('save_staff_workleave');
    Route::post('save_office_staff_start_time', 'staff\TaskController@save_office_staff_start_time')->name('save_office_staff_start_time');
    Route::post('save_office_staff_task', 'staff\TaskController@save_office_staff_task')->name('save_office_staff_task');
    Route::post('filter_office_staff_task', 'staff\TaskController@filter_office_staff_task')->name('filter_office_staff_task');
    Route::post('save_office_staff_end_time', 'staff\TaskController@save_office_staff_end_time')->name('save_office_staff_end_time');
    Route::post('get_office_staff_all_details', 'staff\TaskController@get_office_staff_all_details')->name('get_office_staff_all_details');
    Route::post('save_expence_edit_details', 'staff\TaskController@save_expence_edit_details')->name('save_expence_edit_details');
    Route::post('get_request_leave', 'staff\TaskController@get_request_leave')->name('get_request_leave');
    Route::post('check_travel_from_status', 'staff\TaskController@check_travel_from_status')->name('check_travel_from_status');
    Route::post('get_travel_expence_staff', 'staff\TaskController@get_travel_expence_staff')->name('get_travel_expence_staff');
    Route::post('get_travel_expence_staff_office', 'staff\TaskController@get_travel_expence_staff_office')->name('get_travel_expence_staff_office');
    Route::post('add_task_replay_comment_expence', 'staff\TaskController@add_task_replay_comment_expence')->name('add_task_replay_comment_expence');

    Route::post('check_attendence_lock', 'staff\TaskController@check_attendence_lock')->name('check_attendence_lock');
    /***********************************Task staff end ****************************************************/

    Route::post('add_contact_person', 'staff\AdminajaxController@add_contact_person');
    Route::post('contactformedit', 'staff\AdminajaxController@contactformedit');
    Route::resource('contact_person', 'staff\Contact_personController');
    Route::post('contact_person/deleteAll', 'staff\Contact_personController@deleteAll');
    Route::post('ajaxChangeStatus', 'staff\AdminajaxController@ajaxChangeStatus');
    Route::post('ajaxChangeStatus_product', 'staff\AdminajaxController@ajaxChangeStatus_product');
    Route::post('view_task_details', 'staff\AdminajaxController@view_task_details');
    Route::post('view_staff_task', 'staff\AdminajaxController@view_staff_task');
    Route::post('approve_staff', 'staff\AdminajaxController@approve_staff');

    Route::post('add_task_comment', 'staff\AdminajaxController@add_task_comment'); 
    Route::post('view_task_comment', 'staff\AdminajaxController@view_task_comment');
    Route::post('view_task_commentall', 'staff\AdminajaxController@view_task_commentall');
    Route::post('change_repeat_every', 'staff\AdminajaxController@change_repeat_every');
    Route::post('get_client_use_state_district', 'staff\AdminajaxController@get_client_use_state_district');
    Route::post('opp_get_client_use_state_district', 'staff\AdminajaxController@opp_get_client_use_state_district');
    Route::post('taluk_state_district', 'staff\AdminajaxController@taluk_state_district');
    Route::post('save_task_type', 'staff\TaskController@save_task_type');
    Route::post('change_assignes', 'staff\AdminajaxController@change_assignes');
    Route::post('view_task_comment_dailytask', 'staff\AdminajaxController@view_task_comment_dailytask');
    Route::post('delete_task_comment', 'staff\AdminajaxController@delete_task_comment');
    Route::post('emailvalidation', 'staff\AdminajaxController@emailvalidation');
    Route::post('change_country', 'staff\AdminajaxController@change_country');
    Route::post('change_state', 'staff\AdminajaxController@change_state');
    Route::post('opp_change_state', 'staff\AdminajaxController@opp_change_state');
    Route::post('user_change_state', 'staff\AdminajaxController@user_change_state');
    Route::post('change_district', 'staff\AdminajaxController@change_district');

    Route::post('get_user_all_details', 'staff\AdminajaxController@get_user_all_details');






    // /  *********************************filtering staff***************************************/
  
    Route::post('msa_change_state', 'staff\AdminajaxController@msa_change_state');
    Route::post('msa_get_client_use_state_district', 'staff\AdminajaxController@msa_get_client_use_state_district');

    Route::post('pm_change_state', 'staff\AdminajaxController@pm_change_state');
    Route::post('pm_get_client_use_state_district', 'staff\AdminajaxController@pm_get_client_use_state_district');

    Route::post('service_change_state', 'staff\AdminajaxController@service_change_state');
    Route::post('service_get_client_use_state_district', 'staff\AdminajaxController@service_get_client_use_state_district');


    // /  *********************************filtering admin***************************************/
  

    Route::post('pm_change_state', 'admin\AdminajaxController@pm_change_state');
    Route::post('pm_get_client_use_state_district', 'admin\AdminajaxController@pm_get_client_use_state_district');


    Route::post('service_change_state', 'admin\AdminajaxController@service_change_state');
    Route::post('service_get_client_use_state_district', 'admin\AdminajaxController@service_get_client_use_state_district');




    Route::get('opportunity/{oppertunity}/billing/edit', 'staff\StaffTargetController@approveStatusEdit')->name("oppertunity.approve.edit");
    Route::post('opportunity/{oppertunity}/billing/status', 'staff\StaffTargetController@approveStatus')->name("oppertunity.approve.status");
    Route::get('opportunity/{oppertunity}/billing/history', 'staff\StaffTargetController@showstatus')->name("oppertunity.approve.history");
    Route::post('opportunity/billing/attachement', 'staff\StaffTargetController@addOpurtunityStatusAttaches')->name("oppertunity.approve.attachement");

    Route::get('opportunity/billing', 'staff\StaffTargetController@targetCommission')->name("target.commission.index");


    Route::post('get_multiple_product_all_details_transation', 'admin\AdminajaxController@get_multiple_product_all_details_transation')->name('get_multiple_product_all_details_transation');
    Route::post('get_contactperson_all_details', 'admin\AdminajaxController@get_contactperson_all_details');

    Route::resource('msp', 'staff\MspController');
    Route::post('msp/deleteAll', 'staff\MspController@deleteAll');

    Route::get('product-show-in-page', 'staff\MspController@product_show_status')->name('product_show_status');

    Route::post('save_mspusing_ajax', 'staff\AdminajaxController@save_mspusing_ajax')->name('save_mspusing_ajax');

    Route::post('emailvalidationusers', 'staff\AdminajaxController@emailvalidationusers');

    Route::post('show_prv_msp', 'staff\AdminajaxController@show_prv_msp')->name('show_prv_msp');

    Route::post('change_brand_for_product', 'staff\AdminajaxController@change_brand_for_product')->name('change_brand_for_product');

    Route::post('sort_brand_categorytypeuse_carearea', 'staff\AdminajaxController@sort_brand_categorytypeuse_carearea')->name('sort_brand_categorytypeuse_carearea');

    Route::post('change_particular_product_details', 'staff\AdminajaxController@change_particular_product_details')->name('change_particular_product_details');

    Route::post('sort_brand_use_category_type', 'staff\AdminajaxController@sort_brand_use_category_type')->name('sort_brand_use_category_type');

    Route::post('get_last_product_price_msp', 'staff\AdminajaxController@get_last_product_price_msp')->name('get_last_product_price_msp');

  }
);
Route::middleware(AdminAuthenticate::class)->prefix('admin')->name('admin.')->group(
  function () {

    Route::resource('app', 'admin\VersionController');
    Route::resource('inoutactivity', 'admin\InOutActivityController');
    Route::get('inoutactivity/{id}/history', 'admin\InOutActivityController@view')->name("inoutactivity.view");
    Route::get('inoutactivity/{id}/accept', 'admin\InOutActivityController@accept')->name("inoutactivity.accept");


    /***********************************Admin Basic Routes start****************************************************/
    Route::any('get_multiple_product_all_details', 'admin\AdminajaxController@get_multiple_product_all_details');

    Route::get('dashboard', 'admin\AdminController@dashboard');
    Route::get('change-password', 'admin\AdminController@changePassword');
    Route::post('change-password', 'admin\AdminController@updatePassword');
    Route::get('settings', 'admin\AdminController@viewSettings');
    Route::post('settings', 'admin\AdminController@updateSettings');
    Route::get('elfinder/ckeditor', '\Barryvdh\Elfinder\ElfinderController@showCKeditor4');
    Route::get('elfinder/connector', '\Barryvdh\Elfinder\ElfinderController@showConnector');
    Route::post('elfinder/connector', '\Barryvdh\Elfinder\ElfinderController@showConnector');

    /***********************************Admin Basic Routes End****************************************************/


    Route::post('followerstafflist', 'admin\StaffFollowerController@followerstaffstaff')->name('followerstafflist');
    Route::get('staffFollower', 'admin\StaffFollowerController@index')->name('staffFollower');
    Route::post('staffFollower', 'admin\StaffFollowerController@index')->name('staffFollower');
    Route::post('storeStaffFollower', 'admin\StaffFollowerController@store')->name('storeStaffFollower');
    Route::post('stafffollower/{staffollower}/delete', 'admin\StaffFollowerController@destroy')->name('stafffollower.delete');
    Route::resource('cms', 'admin\CmsController');
    Route::post('cms/deleteAll', 'admin\CmsController@deleteAll');
    Route::resource('cms.downloads', 'admin\DownloadsController');
    Route::post('downloads/deleteAll', 'admin\DownloadsController@deleteAll');
    Route::resource('cms.contact-persons', 'admin\ContactPersonsController');
    Route::post('contact-persons/deleteAll', 'admin\ContactPersonsController@deleteAll');
    Route::resource('contact_person', 'admin\Contact_personController');
    Route::post('contact_person/deleteAll', 'admin\Contact_personController@deleteAll');
    Route::resource('cms.history', 'admin\HistoryController');
    Route::post('history/deleteAll', 'admin\HistoryController@deleteAll');

    Route::resource('cms.cooperations', 'admin\CooperationsController');
    Route::post('cooperations/deleteAll', 'admin\CooperationsController@deleteAll');
    Route::resource('cms.references', 'admin\ReferencesController');
    Route::post('references/deleteAll', 'admin\ReferencesController@deleteAll');
    Route::resource('banners', 'admin\BannerController');
    Route::post('banners/deleteAll', 'admin\BannerController@deleteAll');

    /***********************************User permission  Start***********************************************/

    Route::resource('user_permission', 'admin\User_permissionController');

    Route::post('permission_update', 'admin\User_permissionController@permission_update');

    Route::post('cord_permission_update', 'admin\User_permissionController@cord_permission_update');
    Route::post('cord_permission_state_update', 'admin\User_permissionController@cord_permission_state_update');
    Route::post('cord_permission_delete', 'admin\User_permissionController@cord_permission_delete');
    Route::post('permission_staff_update', 'admin\User_permissionController@permission_staff_update');
    Route::post('permission_staff_report', 'admin\User_permissionController@permission_staff_report');


    


    Route::get('assign_cordinator', 'admin\User_permissionController@assign_cordinator');
    Route::get('cordinator_permission', 'admin\User_permissionController@cordinatorpermission');

    Route::post('coordinator_update', 'admin\User_permissionController@coordinator_update');

    Route::post('user_permission/deleteAll', 'admin\User_permissionController@deleteAll');

    /***********************************User permission  End***********************************************/

    /***********************************Assgin supervisor  Start***********************************************/
    Route::resource('assginsupervisor', 'admin\AssginsupervisorController');
    Route::post('assginsupervisor/deleteAll', 'admin\AssginsupervisorController@deleteAll');
    /***********************************Assgin supervisor  End***********************************************/

    /***********************************Warehouse  Start***********************************************/
    Route::resource('warehouse', 'admin\WarehouseController');
    Route::post('warehouse/deleteAll', 'admin\WarehouseController@deleteAll');
    Route::resource('warehouse_rack', 'admin\Warehouse_rackController');
    Route::post('warehouse_rack/deleteAll', 'admin\Warehouse_rackController@deleteAll');
    Route::resource('warehouse_shelf', 'admin\Warehouse_shelfController');
    Route::post('warehouse_shelf/deleteAll', 'admin\Warehouse_shelfController@deleteAll');
    /***********************************Warehouse  Start***********************************************/

    /***********************************Units start****************************************************/
    Route::resource('units', 'admin\UnitsController');
    Route::post('units/deleteAll', 'admin\UnitsController@deleteAll');
    /***********************************Units End****************************************************/

    /***********************************Units start****************************************************/
    Route::resource('marketspace', 'admin\MarketspaceController');
    Route::post('marketspace/deleteAll', 'admin\MarketspaceController@deleteAll');
    /***********************************Units End****************************************************/

    /***********************************Courier  start****************************************************/

    Route::resource('courier', 'admin\CourierController');
    Route::post('courier/deleteAll', 'admin\CourierController@deleteAll');
    /***********************************Courier End****************************************************/

    /***********************************IB start****************************************************/

    Route::get('home_banner', 'admin\HomeBannerController@index')->name('home_banner_index');
    Route::get('home_banner_create', 'admin\HomeBannerController@create')->name('home_banner_create');
    Route::post('home_banner_store', 'admin\HomeBannerController@store')->name('home_banner_store');
    Route::get('home_banner_edit/{id}', 'admin\HomeBannerController@edit')->name('home_banner_edit');
    Route::post('home_banner_update/{id}', 'admin\HomeBannerController@update')->name('home_banner_update');
    Route::post('home_banner_delete', 'admin\HomeBannerController@destroy')->name('home_banner_delete');


    /***********************************IB End****************************************************/



    /***********************************IB start****************************************************/

    Route::get('ib', 'admin\IbController@index')->name('ib-index');
    Route::get('ib-create', 'admin\IbController@create')->name('ib-create');
    Route::post('ib-store', 'admin\IbController@store')->name('ib-store');
    Route::get('ib-destroy/{id}', 'admin\IbController@destroy')->name('ib-destroy');
    Route::delete('ib-destroy/{id}', 'admin\IbController@destroy')->name('ib-destroy');
    Route::get('ib-edit/{id}', 'admin\IbController@edit')->name('ib-edit');
    Route::post('ib-update/{id}', 'admin\IbController@update')->name('ib-update');
    Route::post('ib-import', 'admin\IbController@import')->name('ib-import');

    Route::post('/check_equp_no', 'admin\IbController@check_equp_no')->name('check_equp_no');

    Route::get('ib-import-files', 'admin\IBImportController@import_ib')->name('import_ib');

    Route::post('ib-import-fetch', 'admin\IBImportController@import_file_fetch')->name('import_file_fetch');

    Route::post('ib-import-submit', 'admin\IBImportController@import_ib_submit')->name('import_ib_submit');

    Route::get('import-edit/{id}', 'admin\IBImportController@edit')->name('import-edit');

    Route::post('import-update/{id}', 'admin\IBImportController@import_update')->name('import_update');

    // Route::get('import-destroy/{id}', 'admin\IBImportController@destroy')->name('import-destroy');
  
    Route::delete('import-destroy/{id}', 'admin\IBImportController@destroy')->name('import-destroy');

    /***********************************IB End****************************************************/

    /***********************************Service start****************************************************/

    Route::get('service/{id}', 'admin\ServiceController@index')->name('service-index');
    Route::post('service-destroy', 'admin\ServiceController@destroy')->name('service-destroy');
    Route::get('service-create/{id}', 'admin\ServiceController@create')->name('service-create');
    Route::get('service-detail/{id}', 'admin\ServiceController@serviceDetail')->name('service-detail');
    // Route::get('ib-create', 'admin\IbController@create')->name('ib-create');
    Route::post('service-store', 'admin\ServiceController@store')->name('service-store');
    Route::match(['get', 'post'], 'service-delete/{id}', 'admin\ServiceController@destroy')->name('service-delete');
    Route::get('service-edit/{id}', 'admin\ServiceController@edit')->name('service-edit');
    Route::post('service-update/{id}', 'admin\ServiceController@update')->name('service-update');

    Route::get('pm_approve/{id}', 'admin\ServiceController@pm_approve')->name('pm_approve');
    Route::get('service-task-delete/{id}', 'admin\ServiceController@serviceTaskDelete')->name('service-task-delete');
    Route::post('service-newVisitTask', 'admin\ServiceController@newServiceVistTask')->name('service-newVisitTask');
    Route::post('service-callResponse', 'admin\ServiceController@serviceCallResponse')->name('service-callResponse');

    Route::post('service-RequestPart', 'admin\ServiceController@serviceRequestPart')->name('service-RequestPart');

    Route::post('service-product_order', 'admin\ServiceController@serviceProductOrder')->name('service-product_order');

    Route::post('service-technical_support', 'admin\ServiceController@serviceTechnicalSupport')->name('service-technical_support');

    Route::post('service-feedback', 'admin\ServiceController@serviceFeedback')->name('service-feedback');

    Route::post('service-partDelete', 'admin\ServiceController@servicePartDelete')->name('service-partDelete');

    Route::post('service-response-reply', 'admin\ServiceController@addReplyToTaskComment')->name('service-response-reply');

    Route::post('service-uploadRemark', 'admin\ServiceController@serviceUploadRemark')->name('service-uploadRemark');

    Route::get('service-Approve/{id}', 'admin\ServiceController@serviceApprove')->name('service-Approve');

    /***********************************Service End****************************************************/


    /***********************************Contact Customer start****************************************************/

    Route::post('customer_contact_person', 'admin\ServiceController@customerContactEquipment');
    Route::post('customer_contact_detail', 'admin\ServiceController@customerContactDetail');

    Route::post('equipment_serial', 'admin\ServiceController@equipmentSerial');
    Route::post('equipment_detail', 'admin\ServiceController@equipmentDetail');

    /***********************************Category Product Start***********************************************/

    Route::resource('category_type', 'admin\Category_typeController');
    Route::post('category_type/deleteAll', 'admin\Category_typeController@deleteAll');

    /***********************************Category Product Start***********************************************/
    /*********************************** Product Type Start***********************************************/

    Route::resource('product_type', 'admin\Product_typeController');
    Route::post('product_type/deleteAll', 'admin\Product_typeController@deleteAll');

    /***********************************Product Type Start***********************************************/
    /*********************************** Tools and asset Start***********************************************/
    Route::resource('toolsandasset', 'admin\ToolsandassetController');
    Route::post('toolsandasset/deleteAll', 'admin\ToolsandassetController@deleteAll');
    Route::post('get_service_date', 'admin\ToolsandassetController@get_service_date')->name('get_service_date');
    /*********************************** Tools and asset Start***********************************************/
    Route::resource('inwardandoutward', 'admin\InwardandoutwardController');
    Route::post('inwardandoutward/deleteAll', 'admin\InwardandoutwardController@deleteAll');
    /*********************************** Trainging Start***********************************************/
    Route::resource('training', 'admin\TrainingController');
    Route::post('training/deleteAll', 'admin\TrainingController@deleteAll');
    /*********************************** Trainging Start***********************************************/
    Route::resource('training', 'admin\TrainingController');
    Route::post('training/deleteAll', 'admin\TrainingController@deleteAll');
    /*********************************** Document Start***********************************************/
    Route::resource('document', 'admin\DocumentController');
    Route::get('document/product/list', 'admin\DocumentController@productDocument')->name("document.productDocument");
    Route::post('document/deleteAll', 'admin\DocumentController@deleteAll');

    /*********************************** Documentcategory Start***********************************************/
    Route::resource('document_category', 'admin\Document_categoryController');
    Route::post('document_category/deleteAll', 'admin\Document_categoryController@deleteAll');

    Route::post('add_category_document', 'admin\Document_categoryController@add_category_document')->name('add_category_document');

    Route::resource('training_questions', 'admin\Training_questionsController');
    Route::post('training_questions/deleteAll', 'admin\Training_questionsController@deleteAll');

    Route::resource('training_staff', 'admin\Training_staffController');
    Route::post('training_staff/deleteAll', 'admin\Training_staffController@deleteAll');
    Route::post('view_training_details', 'admin\Training_staffController@view_training_details')->name('view_training_details');
    Route::any('delete_training_staff/{id}', 'admin\Training_staffController@delete_training_staff')->name('delete_training_staff');
    ;
    /***********************************Product Type Start***********************************************/

    /***********************************Modality Product Start****************************************************/
    Route::resource('modality', 'admin\ModalityController');
    Route::post('modality/deleteAll', 'admin\ModalityController@deleteAll');
    /***********************************Modality Product End****************************************************/
    /***********************************Competition Product  Start*************************************************/
    Route::resource('competition_product', 'admin\Competition_productController');
    Route::post('competition_product/deleteAll', 'admin\Competition_productController@deleteAll');
    /***********************************Competition Product  End****************************************************/
    /***********************************Staff Mange Start****************************************************/
    Route::resource('staff', 'admin\StaffController');
    Route::get('staff/sales/list', 'admin\StaffSalesController@index')->name("staff.sales.index");
    Route::get('staff/sales/{id}/show', 'admin\StaffSalesController@show')->name("staff.sales.show");
    Route::delete('staff/sales/{id}/delete', 'admin\StaffSalesController@destroy')->name("staff.sales.delete");
    Route::post('staff/sales/{id}/approve', 'admin\StaffSalesController@approve')->name("staff.sales.approve");
    Route::post('staff/{id}/reset-password', 'admin\StaffController@password_reset')->name("staff.reset-password");
    Route::post('staff/deleteAll', 'admin\StaffController@deleteAll');
    Route::post('staff/remove_staff_detail', 'admin\StaffController@remove_staff_details');
    Route::get('changestatus', 'admin\StaffController@changestatus');

    Route::get('opportunity/won/approve', 'admin\OpportunityWonApprovalController@index')->name("opportunity.won.approve");
    Route::get('opportunity/{oppertunity}/approve/edit', 'admin\OpportunityWonApprovalController@approveStatusEdit')->name("oppertunity.approve.edit");
    Route::post('opportunity/{oppertunity}/approve/status', 'admin\OpportunityWonApprovalController@approveStatus')->name("oppertunity.approve.status");
    Route::get('opportunity/{oppertunity}/approve/history', 'admin\OpportunityWonApprovalController@showstatus')->name("oppertunity.approve.history");
    Route::post('opportunity/approve/attachement', 'admin\OpportunityWonApprovalController@addOpurtunityStatusAttaches')->name("oppertunity.approve.attachement");


    Route::get('staff/target/list', 'admin\StaffTargetController@index')->name("staff.target.index");
    Route::get('staff/target/commission/list', 'admin\StaffTargetController@targetCommission')->name("staff.target.commission.index");
    Route::get('staff/target/commission/completestatus', 'admin\StaffTargetController@completestatus')->name("staff.target.commission.completestatus");
    Route::post('staff/{id}/target/commission/add', 'admin\StaffTargetController@addTargetCommission')->name("staff.target.commission.add");
    Route::post('staff/target/commission/attaches/add', 'admin\StaffTargetController@addOpurtunityAttaches')->name("staff.target.commission.attacheadd");
    Route::get('staff/{staff}/target/commission/{id}/view', 'admin\StaffTargetController@getOpurtunityCommission')->name("staff.target.commission.view");
    Route::get('staff/target/commission/{id}/attaches', 'admin\StaffTargetController@getOpurtunityAttaches')->name("staff.target.commission.attaches");
    Route::get('staff/{staff}/target/commission/{id}/payview', 'admin\StaffTargetController@getOpurtunityCommissionApproved')->name("staff.target.commission.payview");
    Route::post('staff/{id}/target/commission/approve', 'admin\StaffTargetController@approveTargetCommission')->name("staff.target.commission.approve");
    Route::get('staff/target/staff', 'admin\StaffTargetController@staffdata')->name("staff.target.data");
    Route::post('staff/target/staff/update', 'admin\StaffTargetController@updatePeriod')->name("staff.target.updatePeriod");
    Route::post('staff/target/month/staff/add', 'admin\StaffTargetController@addmonthTarget')->name("staff.target.addmonth");
    Route::post('staff/target/commission/staff/add', 'admin\StaffTargetController@addmonthTargetCommission')->name("staff.target.addmonthcommission");
    Route::post('staff/target/paid/staff/add', 'admin\StaffTargetController@addmonthPaidTarget')->name("staff.target.addmonthpaid");
    Route::post('staff/target/brand/staff/remove', 'admin\StaffTargetController@removeBrand')->name("staff.target.removeBrand");
    Route::post('staff/target/brand/staff/remove-modality', 'admin\StaffTargetController@removeBrandModality')->name("staff.target.removeBrandModality");
    Route::get('staff/target/paid/staff/list', 'admin\StaffTargetController@staffmonthpaidlist')->name("staff.target.getmonthpaid");
    Route::get('staff/target/commission/staff/list', 'admin\StaffTargetController@staffmonthcommissionlist')->name("staff.target.getmonthcommission");
    Route::post('staff/{staff}/target/{oppertunity}/commission/change/status', 'admin\StaffTargetController@changeTargetCommissionStatus')->name("staff.target.commission.status");

    Route::get('staff/target/create', 'admin\StaffTargetController@create')->name("staff.target.create");
    Route::post('staff/target/store', 'admin\StaffTargetController@store')->name("staff.target.store");
    Route::get('staff/target/{id}/edit', 'admin\StaffTargetController@edit')->name("staff.target.edit");
    Route::get('staff/target/{id}/show', 'admin\StaffTargetController@show')->name("staff.target.show");
    Route::delete('staff/target/{id}/delete', 'admin\StaffTargetController@destroy')->name("staff.target.delete");
    /***********************************Staff Mange  End****************************************************/

    /************************************ Dealers Manage Start *********************************************/
    Route::post('dealer/{dealer}/verify', 'admin\DealerController@verify')->name("dealer.verify");
    Route::put('dealer/{dealer}/upload', 'admin\DealerController@upload')->name('dealer.upload');
    Route::resource('dealer', 'admin\DealerController');
    /************************************ Dealers Manage Start *********************************************/
    /***********************************Customer Mange ****************************************************/
    Route::resource('customer', 'admin\UserController');
    Route::get('customer/state/district', 'admin\UserController@getDistrict')->name("customer.district");
    Route::get('customer/state/district/taluk', 'admin\UserController@getTaluk')->name("customer.taluk");
    Route::post('customer/deleteAll', 'admin\UserController@deleteAll');
    Route::get('exportproductcustomer', 'admin\UserController@exportproductcustomer')->name('exportproductcustomer');
    Route::post('importproductcustomer', 'admin\UserController@importproductcustomer')->name('importproductcustomer');
    Route::get('importExportViewCustomer', 'admin\UserController@importExportViewCustomer')->name('importExportViewCustomer');
    /***********************************Customer Mange End ****************************************************/

    /***********************************Admin Option Start ****************************************************/

    Route::resource('inoutactivitycategory', 'admin\InOutActivityCategoryController');
    Route::post('inoutactivitycategory/deleteAll', 'admin\InOutActivityCategoryController@deleteAll');
    Route::resource('company', 'admin\CompanyController');
    Route::post('company/deleteAll', 'admin\CompanyController@deleteAll');
    Route::resource('hosdesignation', 'admin\HosdesignationController');
    Route::post('hosdesignation/deleteAll', 'admin\HosdesignationController@deleteAll');
    Route::resource('designation', 'admin\DesignationController');
    Route::resource('staff_category', 'admin\StaffCategoryController');
    Route::post('staff_category/deleteAll', 'admin\StaffCategoryController@deleteAll');

    Route::post('designation/deleteAll', 'admin\DesignationController@deleteAll');
    Route::resource('hosdeparment', 'admin\HosdeparmentController');
    Route::post('hosdeparment/deleteAll', 'admin\HosdeparmentController@deleteAll');
    Route::resource('customercategory', 'admin\CustomercategoryController');
    Route::post('customercategory/deleteAll', 'admin\CustomercategoryController@deleteAll');
    Route::resource('state', 'admin\StateController');
    Route::post('state/deleteAll', 'admin\StateController@deleteAll');
    Route::resource('district', 'admin\DistrictController');
    Route::post('district/deleteAll', 'admin\DistrictController@deleteAll');
    Route::resource('taluk', 'admin\TalukController');
    Route::post('taluk/deleteAll', 'admin\TalukController@deleteAll');
    Route::resource('relatedto_category', 'admin\Relatedto_categoryController');
    Route::post('relatedto_category/deleteAll', 'admin\Relatedto_categoryController@deleteAll');
    Route::resource('relatedto_subcategory', 'admin\Relatedto_subcategoryController');
    Route::post('relatedto_subcategory/deleteAll', 'admin\Relatedto_subcategoryController@deleteAll');
    Route::resource('checklist', 'admin\ChecklistController');
    Route::post('checklist/deleteAll', 'admin\ChecklistController@deleteAll');
    Route::get('contractOwner', 'admin\ContractController@contractOwnerShow')->name('contractowner.index');
    Route::get('contractOwner/create', 'admin\ContractController@contractOwnerCreate')->name('contractowner.create');
    Route::post('contractOwner/store', 'admin\ContractController@contractOwnerStore')->name('contractowner.store');
    Route::get('contractOwner/edit/{id}', 'admin\ContractController@contractOwnerEdit')->name('contractowner.edit');
    Route::post('contractOwner/update/{id}', 'admin\ContractController@contractOwnerUpdate')->name('contractowner.update');
    Route::delete('contractOwner/destroy/{id}', 'admin\ContractController@contractOwnerDestroy')->name('contractowner.destroy');
    Route::post('contractOwner/deleteAll', 'admin\ContractController@contractOwnerDeleteAll');
    Route::get('assetDepartment', 'admin\AssetController@assetDepartmentShow')->name('assetdepartment.index');
    Route::get('assetDepartment/create', 'admin\AssetController@assetDepartmentCreate')->name('assetdepartment.create');
    Route::post('assetDepartment/store', 'admin\AssetController@assetDepartmentStore')->name('assetdepartment.store');
    Route::get('assetDepartment/edit/{id}', 'admin\AssetController@assetDepartmentEdit')->name('assetdepartment.edit');
    Route::post('assetDepartment/update/{id}', 'admin\AssetController@assetDepartmentUpdate')->name('assetdepartment.update');
    Route::delete('assetDepartment/destroy/{id}', 'admin\AssetController@assetDepartmentDestroy')->name('assetdepartment.destroy');
    Route::post('assetDepartment/deleteAll', 'admin\AssetController@assetDepartmentDeleteAll');

    /***********************************Admin Option End ****************************************************/

    Route::resource('industries', 'admin\IndustriesController');
    Route::post('industries/deleteAll', 'admin\IndustriesController@deleteAll');
    Route::resource('downloads_category', 'admin\DownloadsCategoryController');
    Route::post('downloads_category/deleteAll', 'admin\DownloadsCategoryController@deleteAll');
    Route::resource('feedbacks', 'admin\FeedbacksController');
    Route::post('feedbacks/deleteAll', 'admin\FeedbacksController@deleteAll');

    Route::resource('salesoption', 'admin\Order_sales_optionController');
    Route::post('salesoption/deleteAll', 'admin\Order_sales_optionController@deleteAll');

    Route::resource('service_task', 'admin\Service_taskController');
    Route::get('asset', 'admin\AssetController@index')->name('asset');
    Route::get('createasset', 'admin\AssetController@show')->name('asset.show');
    Route::post('asset/show', 'admin\AssetController@store')->name('asset.store');
    Route::get('asset/edit/{id}', 'admin\AssetController@edit')->name('asset.edit');
    Route::post('asset/update/{id}', 'admin\AssetController@update')->name('asset.update');
    Route::post('asset/save', 'admin\AssetController@save')->name('asset.save');
    Route::delete('asset/destroy/{id}', 'admin\AssetController@destroy');
    Route::post('asset/deleteAll', 'admin\AssetController@deleteAll');
    Route::get('import-asset', 'admin\AssetController@importAsset')->name('import-asset');
    Route::post('importasset', 'admin\AssetController@importAssetFile')->name('importasset');

    Route::post('customer-equpment-details', 'admin\ContractController@getequpmentdetails')->name('customer-equpment-details');
    Route::post('customer-equpment', 'admin\ContractController@getequpment')->name('customer-equpment');
    Route::post('contract-customer', 'admin\ContractController@getcustomer')->name('contract-customer');
    Route::post('/get-opportunities-by-user', 'admin\ContractController@getOpportunitiesByUser')->name('getOpportunitiesByUser');
    Route::post('/get-products-by-opportunity', 'admin\ContractController@getProductsByOpportunity')->name('getProductsByOpportunity');

    Route::get('pm_order/{id}', 'admin\PmOrderController@pm_order')->name('pm_order');

    Route::post('update_payment', 'admin\PmOrdersalesController@update_payment')->name('update_payment');

    Route::post('update_comment', 'admin\PmOrdersalesController@update_comment')->name('update_comment');

    Route::post('pm_order_submit', 'admin\PmOrderController@pm_order_submit')->name('pm_order_submit');

    Route::get('pm_order', 'admin\PmOrderController@index')->name('pm_order.index');

    Route::get('sales', 'admin\PmOrdersalesController@index')->name('sales.index');

    Route::post('verify_sales', 'admin\PmOrdersalesController@verify_sales')->name('verify_sales');


    Route::get('pm_create/{id}', 'admin\PmCreateController@pm_create')->name('pm_create');

    Route::get('pm_servide_edit/{id}', 'admin\PmCreateController@pm_servide_edit')->name('pm_servide_edit');

    Route::post('contract_destroy', 'admin\PmCreateController@contract_destroy')->name('contract_destroy');

    Route::get('oppertunity_contactsales/{id}', 'admin\ContractSalesController@oppertunity_contactsales')->name('oppertunity_contactsales');

    Route::get('view_oppertunity_contact/{id}', 'admin\ContractController@view_oppertunity_contact')->name('view_oppertunity_contact');

    Route::get('view_sales_contract/{id}', 'admin\ContractSalesController@view_sales_contract')->name('view_sales_contract');

    Route::post('create_bill', 'admin\ContractSalesController@create_bill')->name('create_bill');
    
    Route::post('fetch_optional_products', 'admin\ContractSalesController@fetch_optional_products')->name('fetch_optional_products');

    Route::post('oppertunity_storesales', 'admin\ContractSalesController@oppertunity_storesales')->name('oppertunity_storesales');


    Route::get('contract', 'admin\ContractController@index')->name('contract');
    Route::get('createcontract', 'admin\ContractController@show')->name('contract.create');
    Route::post('createcontract', 'admin\ContractController@store')->name('contract.store');
    Route::post('oppertunity_store', 'admin\ContractController@oppertunity_store')->name('oppertunity_store');
    Route::get('contract/edit/{id}', 'admin\ContractController@edit')->name('contract.edit');
    Route::get('oppertunity_contact/{id}', 'admin\ContractController@oppertunity_contact')->name('oppertunity_contact');
    Route::post('contract/update/{id}', 'admin\ContractController@update')->name('contract.update');
    Route::delete('contract/destroy/{id}', 'admin\ContractController@destroy');
    Route::post('contract/deleteAll', 'admin\ContractController@deleteAll');


    Route::resource('service_responce', 'admin\Service_responceController');
    Route::post('service_responce/deleteAll', 'admin\Service_responceController@deleteAll');
    Route::resource('service_part', 'admin\Service_partController');
    Route::post('service_part/deleteAll', 'admin\Service_partController@deleteAll');
    Route::resource('service_visit', 'admin\Service_visitController');
    Route::post('service_visit/deleteAll', 'admin\Service_visitController@deleteAll');

    /***********************************Quote  Start****************************************************/

    Route::resource('quote', 'admin\QuoteController');
    Route::post('quote/deleteAll', 'admin\QuoteController@deleteAll');
    Route::get('quotepdf/{id}', 'admin\QuoteController@quotepdf')->name('quotepdf');
    Route::get('sendquote/{id}', 'admin\QuoteController@sendquote')->name('sendquote');
    Route::resource('create_quote', 'admin\QuoteCreateController');

    /***********************************Quote  End****************************************************/

    /***********************************Product Category  Start*************************************/
    Route::resource('category', 'admin\CategoryController');
    Route::post('category/deleteAll', 'admin\CategoryController@deleteAll');
    Route::get('category/{category}/product-type/{productType}/remove-type', 'admin\CategoryController@removeType')->name("category.removeType");
    Route::post('category/{category}/product-type/add', 'admin\CategoryController@addType')->name("category.addType");
    /***********************************Product Category  End***************************************/

    /***********************************Product SubCategory  Start*************************************/
    Route::resource('subcategory', 'admin\SubcategoryController');
    Route::post('subcategory/deleteAll', 'admin\SubcategoryController@deleteAll');
    /***********************************Product SubCategory  End**************************************/
    /***********************************Brand  Start****************************************************/
    Route::resource('brand', 'admin\BrandController');
    Route::post('brand/deleteAll', 'admin\BrandController@deleteAll');
    /***********************************Brand  Start****************************************************/

    /***********************************Testimonial  Start****************************************************/
    Route::resource('testimonial', 'admin\TestimonialController');
    Route::post('testimonial/deleteAll', 'admin\TestimonialController@deleteAll');
    /***********************************Testimonial  End****************************************************/

    /***********************************Product  Start****************************************************/
    Route::post('products/{product}/verify', 'admin\ProductController@verify')->name('products.verify');
    Route::post('products/{product}/unverify', 'admin\ProductController@unverify')->name('products.unverify');
    Route::resource('products', 'admin\ProductController');

    
    Route::post('modality_change', 'admin\ProductController@modality_change')->name('modality_change');

    Route::post('products/deleteAll', 'admin\ProductController@deleteAll');
    Route::get('exportproduct', 'admin\ProductController@exportproduct')->name('exportproduct');
    Route::post('importproduct', 'admin\ProductController@importproduct')->name('importproduct');
    Route::post('exportproductpdf', 'admin\ProductController@exportproductpdf')->name('exportproductpdf');
    Route::get('importExportView', 'admin\ProductController@importExportView')->name('importExportView');
    /***********************************Product  End****************************************************/
    /***********************************Transation  Start****************************************************/
    Route::resource('transation', 'admin\TransationController');
    Route::post('transation/deleteAll', 'admin\TransationController@deleteAll');
    Route::post('save_shipping_address_user', 'admin\TransationController@save_shipping_address_user')->name('save_shipping_address_user');
    Route::post('select_shipping_address_user', 'admin\TransationController@select_shipping_address_user')->name('select_shipping_address_user');
    Route::post('approval_transation', 'admin\TransationController@approval_transation');
    Route::post('delete_product_transation', 'admin\TransationController@delete_product_transation');
    Route::post('save_transation_insentive', 'admin\TransationController@save_transation_insentive');
    Route::post('approval_transation_mspowner', 'admin\TransationController@approval_transation_mspowner');
    Route::post('update_qty_transation', 'admin\TransationController@update_qty_transation');
    Route::post('change_transation_type_oppurtunity', 'admin\TransationController@change_transation_type_oppurtunity');
    Route::post('save_config_transation', 'admin\TransationController@save_config_transation');
    Route::post('save_po_transation', 'admin\TransationController@save_po_transation');
    Route::post('save_certifi_transation', 'admin\TransationController@save_certifi_transation');
    Route::post('save_payment_transation', 'admin\TransationController@save_payment_transation');
    Route::post('save_delivery_transation', 'admin\TransationController@save_delivery_transation');
    Route::post('save_other_transation', 'admin\TransationController@save_other_transation');
    Route::post('view_transation_all_product', 'admin\TransationController@view_transation_all_product');
    Route::post('get_sort_product_transaction', 'admin\TransationController@get_sort_product_transaction');
    Route::post('get_value_prev_transaction', 'admin\TransationController@get_value_prev_transaction');
    Route::post('approval_transaction_staff', 'admin\TransationController@approval_transaction_staff');
    Route::get('dispatch_verify_view/{id}', 'admin\TransationController@dispatch_verify_view')->name('dispatch_verify_view');
    Route::get('sales_order', 'admin\TransationController@sales_order')->name('sales_order');
    Route::get('transation_details/{id}', 'admin\TransationController@transation_details')->name("transation_details");
    /***********************************Transation End****************************************************/
    /***********************************invoice_complete_flow  Start****************************************************/
    Route::resource('invoice_complete_flow', 'admin\Invoice_complete_flowController');
    Route::post('invoice_complete_flow/deleteAll', 'admin\Invoice_complete_flowController@deleteAll');
    /***********************************invoice_complete_flow  end****************************************************/

    /***********************************Credit Start****************************************************/
    Route::resource('credit', 'admin\CreditController');
    Route::post('credit/deleteAll', 'admin\CreditController@deleteAll');
    Route::post('get_customer_invoice', 'admin\CreditController@get_customer_invoice')->name('get_customer_invoice');
    Route::post('get_invoice_product', 'admin\CreditController@get_invoice_product')->name('get_invoice_product');
    Route::post('get_invoice_product_qty_check', 'admin\CreditController@get_invoice_product_qty_check')->name('get_invoice_product_qty_check');
    Route::get('preview_credit/{id}', 'admin\CreditController@preview_credit');
    /***********************************Credit Start****************************************************/


    /***********************************Invoice  Start****************************************************/
    Route::resource('invoice', 'admin\InvoiceController');
    Route::post('invoice/deleteAll', 'admin\InvoiceController@deleteAll');
    Route::post('change_purchase_in_out', 'admin\InvoiceController@change_purchase_in_out');
    Route::post('get_inout_details_search', 'admin\InvoiceController@get_inout_details_search');
    Route::post('get_sales_outout_details_search', 'admin\InvoiceController@get_sales_outout_details_search');
    Route::post('get_transaction_details_for_invoice', 'admin\InvoiceController@get_transaction_details_for_invoice');
    Route::post('get_dispatch_details_for_invoice', 'admin\InvoiceController@get_dispatch_details_for_invoice');

    Route::post('get_transaction_product_qty_check_invoice', 'admin\InvoiceController@get_transaction_product_qty_check_invoice');
    Route::get('preview_invoice/{id}', 'admin\InvoiceController@preview_invoice');
    Route::post('check_invoice_id_exit', 'admin\InvoiceController@check_invoice_id_exit');
    /***********************************Invoice  End****************************************************/

    /***********************************Vendor Start****************************************************/
    Route::resource('vendor', 'admin\VendorController');
    Route::post('vendor/deleteAll', 'admin\VendorController@deleteAll');
    Route::post('vendor_address_remove', 'admin\VendorController@vendor_address_remove');

    /***********************************Vendor End****************************************************/
    /***********************************Purchase Start****************************************************/
    Route::resource('purchase', 'admin\PurchaseController');
    Route::post('purchase/deleteAll', 'admin\PurchaseController@deleteAll');
    Route::get('importstock', 'admin\PurchaseController@importstock')->name('importstock');
    Route::post('importproductstock', 'admin\PurchaseController@importproductstock')->name('importproductstock');
    Route::post('select_address_vendor', 'admin\PurchaseController@select_address_vendor')->name('select_address_vendor');
    Route::post('delete_purchase_product', 'admin\PurchaseController@delete_purchase_product')->name('delete_purchase_product');
    Route::post('get_vendor_use_product_list', 'admin\PurchaseController@get_vendor_use_product_list')->name('get_vendor_use_product_list');


    /***********************************Purchase End****************************************************/

    /***********************************Stock Register start****************************************************/
    Route::resource('stock_register', 'admin\Stock_registerController');
    Route::post('stock_register/deleteAll', 'admin\Stock_registerController@deleteAll');
    Route::post('check_stock_product', 'admin\Stock_registerController@check_stock_product');
    Route::post('change_units_product', 'admin\Stock_registerController@change_units_product');
    Route::post('change_product_stock', 'admin\Stock_registerController@change_product_stock');
    Route::post('view_product_inventorydetails', 'admin\Stock_registerController@view_product_inventorydetails');
    Route::post('get_shelf_use_warehouse', 'admin\Stock_registerController@get_shelf_use_warehouse');
    Route::post('get_rack_use_shelf', 'admin\Stock_registerController@get_rack_use_shelf');
    Route::get('product_stock_details/{id}', 'admin\Stock_registerController@product_stock_details')->name('product_stock_details');
    /***********************************Stock Register End****************************************************/



    /***********************************Inout Register start****************************************************/
    Route::resource('inoutregister', 'admin\InoutregisterController');
    Route::post('inoutregister/deleteAll', 'admin\InoutregisterController@deleteAll');
    Route::post('change_transaction', 'admin\InoutregisterController@change_transaction');
    Route::post('change_purchase', 'admin\InoutregisterController@change_purchase');
    Route::post('get_vendor_using_product_list', 'admin\InoutregisterController@get_vendor_using_product_list');

    /***********************************Inout Register End****************************************************/

    /***********************************Goodsrecive Start****************************************************/
    Route::resource('transaction_manage_staff', 'admin\Transaction_manage_staffController');
    Route::post('transaction_manage_staff/deleteAll', 'admin\Transaction_manage_staffController@deleteAll');
    /***********************************Goodsrecive End****************************************************/

    /***********************************Goodsrecive Start****************************************************/
    Route::resource('goodsrecivenote', 'admin\GoodsrecivenoteController');
    Route::post('goodsrecivenote/deleteAll', 'admin\GoodsrecivenoteController@deleteAll');
    /***********************************Goodsrecive End****************************************************/
    /***********************************MSP Start****************************************************/
    Route::resource('msp', 'admin\MspController');
    Route::post('msp/deleteAll', 'admin\MspController@deleteAll');
    /***********************************MSP End****************************************************/
    /***********************************Task Start****************************************************/
    Route::resource('task', 'admin\TaskController');
    Route::post('task/deleteAll', 'admin\TaskController@deleteAll');
    Route::post('view_travel_all_details', 'admin\TaskController@view_travel_all_details')->name('view_travel_all_details');
    Route::get('Staffstatus', 'admin\TaskController@Staffstatus')->name('Staffstatus');
    Route::post('service_task/deleteAll', 'admin\Service_taskController@deleteAll');
    Route::get('AllTaskservice', 'admin\Service_taskController@AllTaskservice')->name('AllTaskservice');
    Route::get('inprogressTask', 'admin\TaskController@inprogressTask')->name('inprogressTask');
    Route::get('completeTask', 'admin\TaskController@completeTask')->name('completeTask');
    Route::get('pendingTask', 'admin\TaskController@pendingTask')->name('pendingTask');
    Route::get('approvedTask', 'admin\TaskController@approvedTask')->name('approvedTask');
    Route::get('dailyclosing', 'admin\TaskController@dailyclosing')->name('dailyclosing');
    Route::get('dailyclosingstaff', 'admin\TaskController@dailyclosingstaff')->name('dailyclosingstaff');
    Route::get('workupdate', 'admin\TaskController@workupdate')->name('workupdate');
    Route::get('stafffollwup', 'admin\TaskController@stafffollwup')->name('stafffollwup');
    Route::get('AllTask', 'admin\TaskController@AllTask')->name('AllTask');
    Route::post('AllTask', 'admin\TaskController@AllTask')->name('AllTask');
    Route::get('infinityTask', 'admin\TaskController@infinityTask')->name('infinityTask');
    Route::get('verifyTask', 'admin\TaskController@verifyTask')->name('verifyTask');
    Route::get('cron', 'admin\TaskController@cron')->name('cron');
    Route::get('cron_customdays', 'admin\TaskController@cron')->name('cron_customdays');
    Route::get('update_dailytask', 'admin\TaskController@update_dailytask')->name('update_dailytask');
    Route::get('importExportTask', 'admin\TaskController@importExportTask')->name('importExportTask');
    Route::post('importtask', 'admin\TaskController@importtask')->name('importtask');
    Route::get('exporttask', 'admin\TaskController@exporttask')->name('exporttask');
    Route::post('update_expence_status_staff', 'admin\TaskController@update_expence_status_staff');
    Route::post('save_travel_edit_details', 'admin\TaskController@save_travel_edit_details');
    Route::post('delete_admin_generate_leave', 'admin\TaskController@delete_admin_generate_leave');

    Route::get('report', 'admin\TaskController@report')->name('report');
    Route::any('reportdetails/{id}', 'admin\TaskController@reportdetails')->name('reportdetails');
    Route::any('reportdetailsopper/{id}', 'admin\TaskController@reportdetailsopper')->name('reportdetailsopper');
    Route::any('reportdetailsinout/{id}', 'admin\TaskController@reportdetailsinout')->name('reportdetailsinout');
    Route::post('car_approval', 'admin\TaskController@carApproval')->name('car_approval');
    Route::get('edit_car_approval', 'admin\TaskController@editCarApproval')->name('edit_car_approval');
    Route::post('update_car_approval', 'admin\TaskController@updateCarApproval')->name('update_car_approval');
    Route::post('get_hos_for_change_department', 'admin\TaskController@get_hos_for_change_department');
    Route::post('get_customercategory_use_staff_id', 'admin\TaskController@get_customercategory_use_staff_id');
    Route::post('save_expence_edit_details', 'admin\TaskController@save_expence_edit_details')->name('save_expence_edit_details');
    Route::post('get_district_use_hospital', 'admin\TaskController@get_district_use_hospital')->name('get_district_use_hospital');
    Route::post('get_customercategory_use_hospital', 'admin\TaskController@get_customercategory_use_hospital')->name('get_customercategory_use_hospital');
    Route::post('get_dept_change_users', 'admin\TaskController@get_dept_change_users')->name('get_dept_change_users');

    /***********************************Task End****************************************************/


    Route::post('view_task_comment_dailytask', 'admin\AdminajaxController@view_task_comment_dailytask');
    Route::post('create_duplicate_task', 'admin\AdminajaxController@create_duplicate_task');
    Route::get('export', 'admin\MyController@export')->name('export');
    //Route::get('importExportView', 'admin\MyController@importExportView');
    Route::post('import', 'admin\MyController@import')->name('import');



    /* -------Admin Ajax     ---------*/
    Route::get('ajaxGet', 'admin\AdminajaxController@ajaxGet');
    Route::post('ajaxPost', 'admin\AdminajaxController@ajaxPost');
    Route::post('ajaxDataTables', 'admin\AdminajaxController@ajaxDataTables');
    Route::post('ajaxChangeStatus', 'admin\AdminajaxController@ajaxChangeStatus');
    Route::post('ajaxChangeDefaultStatus', 'admin\AdminajaxController@ajaxChangeDefaultStatus');
    Route::post('ajaxDataDetails', 'admin\AdminajaxController@ajaxDataDetails');
    Route::post('updateOrder', 'admin\AdminajaxController@updateOrder');
    Route::post('popup_imageCrop', 'admin\AdminajaxController@popup_imageCrop');
    Route::post('change_repeat_every', 'admin\AdminajaxController@change_repeat_every');
    Route::post('get_client_use_state_district', 'admin\AdminajaxController@get_client_use_state_district');
    Route::post('opp_get_client_use_state_district', 'admin\AdminajaxController@opp_get_client_use_state_district');
    Route::post('get_oppurtunitydetails', 'admin\AdminajaxController@get_oppurtunitydetails');
    Route::post('get_user_contact_list', 'admin\AdminajaxController@get_user_contact_list');
    Route::post('get_brand_using_product_list', 'admin\AdminajaxController@get_brand_using_product_list');
    Route::post('get_contact_details', 'admin\AdminajaxController@get_contact_details');
    Route::post('get_user_all_details', 'admin\AdminajaxController@get_user_all_details');
    Route::post('get_contactperson_all_details', 'admin\AdminajaxController@get_contactperson_all_details');
    Route::post('get_opportunity_all_details', 'admin\AdminajaxController@get_opportunity_all_details');
    Route::post('save_first_responce', 'admin\AdminajaxController@save_first_responce');
    Route::post('edit_first_responce', 'admin\AdminajaxController@edit_first_responce');
    Route::post('save_visit', 'admin\AdminajaxController@save_visit');
    Route::post('edit_visit', 'admin\AdminajaxController@edit_visit');
    Route::post('save_part', 'admin\AdminajaxController@save_part');
    Route::post('edit_part', 'admin\AdminajaxController@edit_part');
    Route::post('change_relative_category', 'admin\AdminajaxController@change_relative_category');
    Route::post('change_assignes', 'admin\AdminajaxController@change_assignes');
    Route::post('add_dailyclosing_comment', 'admin\AdminajaxController@add_dailyclosing_comment');
    Route::post('add_dailyclosing_status', 'admin\AdminajaxController@add_dailyclosing_status');
    Route::post('get_approval_message', 'admin\AdminajaxController@get_approval_message');
    Route::post('viewchecklist_details', 'admin\AdminajaxController@viewchecklist_details');
    Route::post('add_task_replay_comment', 'admin\AdminajaxController@add_task_replay_comment');
    Route::post('ajaxgetAllCategory', 'admin\AdminajaxController@ajaxgetAllCategory');
    Route::post('ajaxCreateCategory', 'admin\AdminajaxController@ajaxCreateCategory');
    Route::post('ajaxEditCategory', 'admin\AdminajaxController@ajaxEditCategory');
    Route::post('productimagegallery', 'admin\AdminajaxController@productimagegallery');
    Route::post('get_productimagegallery', 'admin\AdminajaxController@get_productimagegallery');
    Route::post('delete_productimagegallery', 'admin\AdminajaxController@delete_productimagegallery');
    Route::post('add_contact_person', 'admin\AdminajaxController@add_contact_person');
    Route::post('contactformedit', 'admin\AdminajaxController@contactformedit');
    Route::post('view_task_details', 'admin\AdminajaxController@view_task_details');
    Route::post('view_staff_task', 'admin\AdminajaxController@view_staff_task');
    Route::post('approve_staff', 'admin\AdminajaxController@approve_staff');

    Route::post('add_task_comment', 'admin\AdminajaxController@add_task_comment');
    Route::post('view_task_comment', 'admin\AdminajaxController@view_task_comment');
    Route::post('delete_task_comment', 'admin\AdminajaxController@delete_task_comment');
    Route::post('checkemail_exit_user', 'admin\AdminajaxController@checkemail_exit_user');
    Route::post('emailvalidation', 'admin\AdminajaxController@emailvalidation');
    Route::post('emailvalidationusers', 'admin\AdminajaxController@emailvalidationusers');
    Route::post('remove_shippingaddress', 'admin\AdminajaxController@remove_shippingaddress');
    Route::post('change_assigned_team', 'admin\AdminajaxController@change_assigned_team');
    Route::post('change_related_to', 'admin\AdminajaxController@change_related_to');
    Route::post('change_task_status', 'admin\AdminajaxController@change_task_status');
    Route::post('change_task_priority', 'admin\AdminajaxController@change_task_priority');
    Route::post('change_country', 'admin\AdminajaxController@change_country');
    Route::post('change_state', 'admin\AdminajaxController@change_state');
    Route::post('opp_change_state', 'admin\AdminajaxController@opp_change_state');
    Route::post('change_district', 'admin\AdminajaxController@change_district');

    Route::any('get_product_company', 'staff\AdminajaxController@get_product_company');
    Route::any('get_product_all_details', 'staff\AdminajaxController@get_product_all_details');
    Route::any('get_multiple_product_all_details', 'staff\AdminajaxController@get_multiple_product_all_details');
    Route::any('generate_pdf', 'staff\AdminajaxController@generate_pdf');
    Route::any('delete_product_staff', 'staff\AdminajaxController@delete_product_staff');

    /***********************************Admin Oppertunity Start****************************************************/
    Route::post('view_oppertunity_products', 'admin\OppertunityController@viewOppertunityProductModal')->name('view_oppertunity_products');

    Route::post('edit_oppertunity_product/{id}/{op_id}', 'admin\OppertunityController@update_product');

    Route::post('edit_oppertunity_contract_product/{id}/{op_id}', 'admin\OppertunityController@update_product_contract');

    Route::get('list_oppertunity', 'admin\OppertunityController@index')->name('list_oppertunity');
    Route::get('search_oppertunity', 'admin\OppertunityController@search_oppertunity')->name('search_oppertunity');
    Route::get('create_oppertunity', 'admin\OppertunityController@create')->name('create_oppertunity');
    Route::post('create_oppertunity', 'admin\OppertunityController@insert');
    Route::get('edit_oppertunity/{id}', 'admin\OppertunityController@edit')->name('edit_oppertunity');
    Route::post('edit_oppertunity/{id}', 'admin\OppertunityController@update');
    Route::post('delete_oppertunity', 'admin\OppertunityController@delete')->name('delete_oppertunity');
    ;
    Route::get('view_customer/{id}', 'admin\OppertunityController@view_customer')->name('view_customer');
    Route::get('view_contact/{id}', 'admin\OppertunityController@view_contact')->name('view_contact');
    Route::get('list_oppertunity_products/{id}', 'admin\OppertunityController@list_products')->name('list_oppertunity_products');

    Route::get('preview_products', 'admin\OppertunityController@preview_products')->name('preview_products');

    Route::get('get_quote_product', 'admin\OppertunityController@get_quote_product')->name('get_quote_product');

    Route::delete('delete_opp_product', 'admin\OppertunityController@delete_opp_product')->name('delete_opp_product');
    
    Route::post('update_product_status', 'admin\OppertunityController@update_product_status')->name('update_product_status');

    Route::get('create_oppertunity_product/{id}', 'admin\OppertunityController@add_product');
    Route::post('create_oppertunity_product/{id}', 'admin\OppertunityController@insert_product');
    Route::post('delete_oppertunity_product', 'admin\OppertunityController@delete_product');
    Route::get('edit_oppertunity_product/{id}/{op_id}', 'admin\OppertunityController@edit_product');
    Route::get('edit_oppertunity_product/{id}/{op_id}', 'admin\OppertunityController@edit_product');
    Route::get('delete_oppertunity_eachproduct/{id}/{op_id}', 'admin\OppertunityController@delete_oppertunity_eachproduct');
    Route::post('/oppertunity_product_detail', 'admin\OppertunityController@oppertunity_product_detail')->name('oppertunity_product_detail');
    Route::post('generate_quote', 'admin\OppertunityController@generate_quote');
    Route::get('preview_quote/{id}', 'admin\OppertunityController@quotepdf');
    Route::get('send_quote/{id}', 'admin\OppertunityController@sendquote');
    Route::post('quote_send', 'admin\OppertunityController@quote_send');
    Route::post('send_mail', 'admin\OppertunityController@send_mail');
    Route::get('prospectus/{id}', 'admin\OppertunityController@prospectus')->name('prospectus');
    Route::post('delete_prospectus', 'admin\OppertunityController@delete_prospectus');
    Route::get('update_prospectus/{id}', 'admin\OppertunityController@update_prospectus')->name('update_prospectus');
    Route::post('update_prospectus/{id}', 'admin\OppertunityController@store_prospectus');
    Route::post('chatterdetail', 'admin\OppertunityController@chatterdetail');
    Route::post('chattersave', 'admin\OppertunityController@chattersave');
    Route::post('productdetail', 'admin\OppertunityController@productdetail');
    Route::get('loadproductnames/{id}', 'admin\OppertunityController@loadproductnames')->name('loadproductnames');
    Route::get('oppertunities_destroy/{id}', 'admin\OppertunityController@delete')->name('oppertunities_destroy');
    Route::get('approve_quote', 'admin\OppertunityController@approve_quote')->name('approve_quote');
    ;
    Route::get('oppertunity_report', 'admin\OppertunityController@oppertunity_report')->name('oppertunity_report');
    Route::post('oppertunity_contract_list_product', 'admin\OppertunityController@oppertunityContractListProduct')->name('oppertunity_contract_list_product');
    Route::post('oppertunity_contract_product', 'admin\OppertunityController@oppertunityContractProduct')->name('oppertunity_contract_product');
    Route::post('oppertunity_contract_product_store', 'admin\OppertunityController@oppertunityContractProductStore')->name('oppertunity_contract_product_store');
    Route::post('approve_quote_history', 'admin\OppertunityController@approve_quote_history');
    Route::post('delete_quote_history', 'admin\OppertunityController@delete_quote_history');
    Route::post('save_quote_terms', 'admin\OppertunityController@save_quote_terms');
    Route::post('oppertunity/{id}/quote/won', 'admin\OppertunityController@quotewonupdate')->name('quote.won');

    Route::post('oppertunity/data/clone', 'admin\OppertunityController@oppertunityClone')->name('oppertunity.clone');
    Route::post('oppertunity/{id}/close/won', 'admin\OppertunityController@wonCloseOppertunity')->name('oppertunity.closeWon');
    Route::post('oppertunity/{id}/close/cancell', 'admin\OppertunityController@cancellCloseOppertunity')->name('oppertunity.closeCancell');
    Route::post('oppertunity/{id}/close/lost', 'admin\OppertunityController@lossCloseOppertunity')->name('oppertunity.closeLost');




    /***********************************Admin Oppertunity End****************************************************/

    /***********************************Admin Lead Options Start****************************************************/
    Route::get('lead_option', 'admin\LeadoptionController@index')->name('lead_option');
    Route::get('loadcontacts/{id}', 'admin\LeadoptionController@loadcontacts')->name('loadcontacts');
    Route::get('create_lead_option', 'admin\LeadoptionController@create')->name('create_lead_option');
    Route::post('create_lead_option', 'admin\LeadoptionController@insert');
    Route::get('edit_lead_option/{id}', 'admin\LeadoptionController@edit')->name('edit_lead_option');
    Route::post('edit_lead_option/{id}', 'admin\LeadoptionController@update');
    Route::get('cancel_lead_option/{id}', 'admin\LeadoptionController@cancel')->name('cancel_lead_option');
    Route::post('delete_lead_option', 'admin\LeadoptionController@delete');
    Route::get('convert_opportunity/{id}', 'admin\LeadoptionController@convert');
    Route::get('my_lead_option', 'admin\LeadoptionController@my_lead')->name('my_lead_option');
    /***********************************Admin Lead Options End****************************************************/

    /***********************************Admin Order Start****************************************************/
    Route::get('list_order', 'admin\OrderController@index')->name('list_order');
    Route::get('create_order', 'admin\OrderController@create')->name('create_order');
    Route::post('create_order', 'admin\OrderController@insert');
    Route::post('delete_order', 'admin\OrderController@delete_order');
    Route::post('orderdetail', 'admin\OrderController@orderdetail');
    Route::get('edit_order/{id}', 'admin\OrderController@edit')->name('create_order');
    Route::post('update_order', 'admin\OrderController@update');
    Route::post('delete_order_product', 'admin\OrderController@delete_order_product');
    Route::post('delete_order_document', 'admin\OrderController@delete_order_document');
    Route::post('save_order_gov_sale', 'admin\OrderController@save_order_gov_sale');
    Route::post('save_order_export_sale', 'admin\OrderController@save_order_export_sale');
    Route::post('save_order_purchase_sale', 'admin\OrderController@save_order_purchase_sale');
    Route::post('save_order_registered_sale', 'admin\OrderController@save_order_registered_sale');
    Route::post('save_order_unregistered_sale', 'admin\OrderController@save_order_unregistered_sale');
    Route::post('save_order_tender_sale', 'admin\OrderController@save_order_tender_sale');
    Route::get('add_order_product/{id}', 'admin\OrderController@add_order_product')->name('add_order_product');
    Route::post('add_order_product/{id}', 'admin\OrderController@insert_order_product');
    /***********************************Admin Order  End****************************************************/
    Route::get('product-show-in-page', 'admin\MspController@product_show_status')->name('product_show_status');

    /*********************************** Staff Task Control by Admin ****************************************/
    Route::resource("task-comment-manage", "admin\TaskCommentManageController");
  }
);
Route::prefix('admin')->name('admin.')->group(
  function () {
    Route::post('searchproducts', 'admin\AdminajaxController@searchproducts');
    Route::post('addquote', 'admin\AdminajaxController@addquote');
    Route::post('cartaddquote', 'admin\AdminajaxController@cart_request');
    Route::post('addcart', 'admin\AdminajaxController@addcart');
    Route::post('searchbrand_category', 'admin\AdminajaxController@searchbrand_category');
    Route::post('searchcattype', 'admin\AdminajaxController@searchcattype');
    Route::post('searchsubcat', 'admin\AdminajaxController@searchsubcat');
    Route::post('searchproduct_type', 'admin\AdminajaxController@searchproduct_type');
    Route::post('check_session', 'admin\AdminajaxController@check_session')->name('check_session');
    Route::post('change_brand_for_product', 'admin\AdminajaxController@change_brand_for_product')->name('change_brand_for_product');
    Route::post('change_msp_product', 'admin\AdminajaxController@change_msp_product')->name('change_msp_product');
    Route::post('show_prv_msp', 'admin\AdminajaxController@show_prv_msp')->name('show_prv_msp');
    Route::post('save_mspusing_ajax', 'admin\AdminajaxController@save_mspusing_ajax')->name('save_mspusing_ajax');

    Route::post('change_particular_product_details', 'admin\AdminajaxController@change_particular_product_details')->name('change_particular_product_details');
    Route::post('change_brand_for_msplisting', 'admin\AdminajaxController@change_brand_for_msplisting')->name('change_brand_for_msplisting');
    Route::post('change_product_for_msplisting', 'admin\AdminajaxController@change_product_for_msplisting')->name('change_product_for_msplisting');
    Route::post('get_last_product_price_msp', 'admin\AdminajaxController@get_last_product_price_msp')->name('get_last_product_price_msp');
    Route::post('sort_brand_use_category_type', 'admin\AdminajaxController@sort_brand_use_category_type')->name('sort_brand_use_category_type');
    Route::post('sort_brand_use_modality', 'admin\AdminajaxController@sort_brand_use_modality')->name('sort_brand_use_modality');
    Route::post('sort_brand_categorytypeuse_carearea', 'admin\AdminajaxController@sort_brand_categorytypeuse_carearea')->name('sort_brand_categorytypeuse_carearea');
    Route::post('get_multiple_product_all_details_transation', 'admin\AdminajaxController@get_multiple_product_all_details_transation')->name('get_multiple_product_all_details_transation');
    Route::post('get_opportunity_all_details_transation', 'admin\AdminajaxController@get_opportunity_all_details_transation')->name('get_opportunity_all_details_transation');
  }
);
/***********************************User Side  Start****************************************************/
Route::get('/search', 'SearchController@index')->name('search.index');
Route::post('/search', 'SearchController@search')->name('search');
Route::get('/industries', 'IndustriesController@index');
Route::get('/quote', 'Auth\QuoteController@quote')->name('quote');
Route::get('/mycart', 'Auth\QuoteController@mycart')->name('mycart');
Route::get('/removecart/{id}', 'Auth\QuoteController@removecart');
Route::get('quotepdf/{id}', 'Auth\QuoteController@quotepdf')->name('quotepdf');

/**************************************User Side Route End*************************************************/
//Route::get('404', function () {
//  return view('404');
//});

Route::get('{any}', 'PageController@index');

