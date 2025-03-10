<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/auth', 'Auth\ApiAuthentication@login');
Route::get('/auth', 'Auth\ApiAuthentication@login');
Route::middleware('auth:staff')->post(
    '/user',
    function (Request $request) {
        return $request->user();
    }
);
Route::middleware('auth:staff')->group(
    function () {
        Route::get('/staff/dashbord/details', 'api\StaffApiController@getStaffDashboedDetails')->name("api.getStaffDashboedDetails");
        

        Route::get('/{name}/latest-version', 'api\VersionController@latestVersion')->name("app.api.latestVersion");
        Route::get('/{name}/update-latest-version', 'api\VersionController@updatelatestVersion')->name("app.api.updatelatestVersion");

        Route::post('/pin', 'Auth\ApiAuthentication@set_pin')->name("api.setpin");
        Route::post('/resetpin', 'Auth\ApiAuthentication@resetpin')->name("api.resetpin");
        Route::get('/company-list', 'api\StaffApiController@getCompaney')->name("api.company");
        Route::get('/client', 'api\StaffApiController@getClient')->name("api.client");
        Route::get('/work-report-client', 'api\StaffApiController@getWorkReportClient')->name("api.getWorkReportClient");
        Route::get('/staff-list', 'api\StaffApiController@getStaff')->name("api.staff");
        Route::get('/target-staff-list', 'api\StaffApiController@getTargetStaff')->name("api.target.staff");
        Route::get('/check-list', 'api\StaffApiController@getCheckList')->name("api.checklist");
        Route::get('/state', 'api\StaffApiController@getState')->name("api.state");
        Route::get('/district', 'api\StaffApiController@getDistrict')->name("api.district");
        Route::get('/related-to-category', 'api\StaffApiController@getRelatedToCategory')->name("api.relatedtocategory");
        Route::get('/related-to-sub-category', 'api\StaffApiController@getRelatedToSubCategory')->name("api.relatedtosubcategory");
        Route::post('/add-task', 'api\StaffApiController@addTask')->name("api.addTask");
        Route::post('/add-task-comment', 'api\StaffApiController@addTaskComment')->name("api.addTaskComment");
        Route::post('/task-comments', 'api\StaffApiController@viewtaskComment')->name("api.viewtaskComment");
        Route::post('/work-report-status', 'api\StaffApiController@workReportStatus')->name("api.workReportStatus");
        Route::post('/save-travel', 'api\StaffApiController@save_travel')->name("api.save_travel");
        Route::post('/travel-by-date', 'api\StaffApiController@getTravelDate')->name("api.getTravelDate");
        Route::get('/{id}/get-travel-by-parent', 'api\StaffApiController@getTravelParent')->name("api.getTravelParent");
        Route::get('/get-travel/{travel}/task/{task}/time', 'api\StaffApiController@getStaffTaskTimeByTravel')->name("api.getStaffTaskTimeByTravel");
        Route::post('/{id}/add-travel-by-parent', 'api\StaffApiController@addChildTravelParent')->name("api.addChildTravelParent");
        Route::post('/{id}/end-travel-by-id', 'api\StaffApiController@endChildTravel')->name("api.endChildTravel");
        Route::post('/staff-travel-list-report', 'api\StaffApiController@staffTravelListReport')->name("api.staffTravelListReport");
        Route::post('/expence-by-date', 'api\StaffApiController@getExpenceDate')->name("api.getExpenceDate");
        Route::post('/task-by-hospital', 'api\StaffApiController@gethospitaltask')->name("api.gethospitaltask");
        Route::get('/verifytasks', 'api\StaffApiController@getVerifyTask')->name("api.getVerifyTask");
        Route::get('/task-contactpm/{id}', 'api\StaffApiController@getContactByTaskpm')->name("api.getContactByTaskpm");
        Route::get('/task-contact/{id}', 'api\StaffApiController@getContactByTask')->name("api.getContactByTask");
        Route::get('/get-contact/{id}', 'api\StaffApiController@getContact')->name("api.getContact");
        Route::get('/get-contactpm/{id}', 'api\StaffApiController@getContactpm')->name("api.getContactpm");

        Route::get('/get-designation', 'api\StaffApiController@getDesignation')->name("api.getDesignation");
        Route::get('/get-department', 'api\StaffApiController@getDepartment')->name("api.getDepartment");
        Route::post('/create-contact', 'api\StaffApiController@cretatecontact')->name("api.cretatecontact");
        Route::get('/{id}/get-task-by-id','api\StaffApiController@gettaskbyid')->name("api.cretatecontact");

        Route::post('/request-leave', "api\StaffApiController@requestStaffWorkleave")->name("api.requestStaffWorkleave");
        Route::get("/leave-by-date", "api\StaffApiController@getLeaveDate")->name('getLeaveDate');
        Route::post('/office-staff-task', "api\StaffApiController@getOfficeStaffTask")->name('api.getOfficeStaffTask');
        Route::get('/staff-work', "api\StaffApiController@getWork")->name("api.getWork");
        Route::post("/start-work", "api\StaffApiController@startWork")->name("api.startWork");
        Route::post("/end-work", "api\StaffApiController@endWork")->name("api.endWork");
        Route::post("/save-expence", "api\StaffApiController@saveTaskExpence")->name("api.saveTaskExpence");
        Route::get("/staff-tasks", "api\StaffApiController@getTaskByStaff")->name("api.getTaskByStaff");
        Route::get("/car-permission", "api\StaffApiController@checkCarPermission")->name("api.checkCarPermission");
        Route::post("/add-task-replay-comment", "api\StaffApiController@addTaskReplayComment")->name("api.addTaskReplayComment");
        Route::get('/attedence-by-date', 'api\StaffApiController@getAttedence')->name("api.getAttedence");
 

        Route::get('/pm-list', 'api\StaffApiController@getPm')->name('api.getPm');

        Route::get('/service-list', 'api\StaffApiController@getService')->name('api.getService');
        Route::get('/equipment-list', 'api\StaffApiController@getEquipment')->name('api.getEquipment');
        Route::get('/service-ref-number', 'api\StaffApiController@getServiceRefNumber')->name('api.getServiceRefNumber');
        Route::get('/service-customer', 'api\StaffApiController@getServiceUser')->name('api.getServiceUser');
        Route::get('/machine-status-list', 'api\StaffApiController@getMechineStatus')->name('api.getMechineStatus');
        Route::post('/save-service', 'api\StaffApiController@saveservice')->name('api.saveservice');
        Route::post('/remove-service/{id}', 'api\StaffApiController@removeservice')->name('api.removeservice');
        Route::get('/service-task-list/{id}', 'api\StaffApiController@serviceTask')->name('api.serviceTask');
        Route::get('/pm-task-list/{id}', 'api\StaffApiController@pmTask')->name('api.pmTask');
        Route::get('/service-responce-list/{id}', 'api\StaffApiController@serviceResponce')->name('api.serviceResponce');
        Route::get('/pm-responce-list/{id}', 'api\StaffApiController@pmResponce')->name('api.pmResponce');

        Route::post('/create-service-task/{id}', 'api\StaffApiController@createServiceTask')->name('api.createServiceTask');
        Route::post('/create-pm-task/{id}', 'api\StaffApiController@createpmTask')->name('api.createpmTask');

        Route::post('/create-service-call-responce/{id}', 'api\StaffApiController@createServiceCallResponse')->name('api.createServiceCallResponse');
        Route::post('/create-pm-call-responce/{id}', 'api\StaffApiController@createPmCallResponse')->name('api.createPmCallResponse');

        Route::post('/create-task-comment-remark', 'api\StaffApiController@serviceUploadRemark')->name('api.serviceUploadRemark');
        Route::post('/create-task-comment-replay', 'api\StaffApiController@serviceReplyToTaskComment')->name('api.serviceReplyToTaskComment');
        Route::post('/remove-task/{id}', 'api\StaffApiController@removetask')->name('api.removetask');

        Route::get('/staff-training-notification', 'api\TrainingApiController@getTrainingNotification')->name('api.getTrainingNotification');
        Route::get('/staff-training-list', 'api\TrainingApiController@getCurrentTraining')->name('api.getCurrentTraining');
        Route::get('/staff-training-log/{id}', 'api\TrainingApiController@getStaffTraining')->name('api.getStaffTraining');
        Route::get('/staff-training-questions/{id}', 'api\TrainingApiController@getStaffTrainingQuestionAnswer')->name("api.getStaffTrainingQuestionAnswer");
        Route::post("/staff-training-submit/{id}", 'api\TrainingApiController@submitAnswer')->name("api.submitAnswer");

        Route::get('/staff-document-list', 'api\TrainingApiController@getStaffDocuments')->name('api.getStaffDocuments');

        Route::get('/in-out-activity-list', 'api\InOutActivityController@activitylist')->name('api.activitylist');
        Route::get('/in-out-activity/{id}', 'api\InOutActivityController@getActivityHistory')->name('api.getActivityHistory');
        Route::get('/in-out-activity-detail/{id}', 'api\InOutActivityController@getActivityDetails')->name('api.getActivityDetails');
        Route::get('/in-out-category', 'api\InOutActivityController@getActivityCategory')->name('api.getActivityCategory');
        Route::post("/create-in-out-activity", 'api\InOutActivityController@createActivity')->name('api.createActivity');
        Route::post("/edit-in-out-activity/{id}", 'api\InOutActivityController@editActivity')->name('api.editActivity');
        Route::post("/verify-in-out-activity/{id}", 'api\InOutActivityController@verifyActivty')->name('api.verifyActivty');
        Route::post("/close-in-out-activity/{id}", 'api\InOutActivityController@closeActivty')->name('api.closeActivty');
        Route::get('/cancel-in-out-activity/{id}', 'api\InOutActivityController@cancelActivity')->name('api.cancelActivity');
        Route::get('/accept-in-out-activity/{id}', 'api\InOutActivityController@acceptActivity')->name('api.acceptActivity');
        Route::get('/clone-in-out-activity/{id}', 'api\InOutActivityController@cloneActivity')->name('api.cloneActivity');

        Route::get("/customer/{limit}/{offset}/list","api\CustomerApiController@geMyCustomers")->name("api.cutomer.list");
        Route::get("/customer/{id}/care-area","api\CustomerApiController@getCustomerCareArea")->name("api.cutomer.care-area");
        Route::get("/customer-{id}-oppertunity/{limit}/{offset}/list","api\CustomerApiController@getCustomerOppertunity")->name("api.cutomer-oppertunity.list");
        Route::get("/oppertunity/{limit}/{offset}/list","api\StaffApiController@getOppertunity")->name("api.oppertunity.list");
        Route::get("/care-area/{id}/product-type","api\CustomerApiController@getCareAreaProductTypes")->name("api.care-area.product-type");
        Route::get("/product-type/{id}/products/{limit}/{offset}/list","api\CustomerApiController@getProductTypeProducts")->name("api.product-type-products");
        Route::post("/customer/{id}/care-area/{care_area}/sale-visit/create","api\CustomerApiController@createSaleVisit")->name("api.cutomer.care-area.sale-visit.create");
        Route::get("/opportunity-code", "api\CustomerApiController@getOppurtunityCode")->name("api.opportunity.code");
        Route::post("/customer/{id}/opportunity/create","api\CustomerApiController@createSaleOpurtunity")->name("api.cutomer.opportunity.create");
        Route::post("/customer/{customer}/opportunity/{id}/edit","api\CustomerApiController@editSaleOpurtunity")->name("api.cutomer.opportunity.edit");
        Route::post("/customer/{id}/care-area/{care_area}/update-status","api\CustomerApiController@addCustomerCareAreaStatus")->name("api.cutomer.care-area.update-status");
        Route::get("/staff-sales/get-customer-visits/{limit}/{offset}/list", "api\CustomerApiController@getCustomerVisits")->name("api-staff-sales-customer-visits");
   
        Route::get('staff/salesacc/target/list',"api\TargetApiController@getStafftargetsacc")->name("api.salesacc-target-list");

        Route::get('staff/saleseqp/target/list',"api\TargetApiController@getStafftargetseqp")->name("api.saleseqp-target-list");


        Route::get('staff/msa/target/list',"api\TargetApiController@getMsatargets")->name("api.msa-target-list");


        Route::get("/customer/{limit}/{offset}/dash","api\TargetApiController@getStaffTargetDash")->name("api.cutomer.dash");
        Route::get("/customer/{limit}/{offset}/list-all","api\CustomerApiController@getAllCustomer")->name("api.cutomer.list.all");


        Route::get('oppertunity/{id}/product/{product}/add-opt-list','api\StaffApiController@oppurtunityaddoptlist')->name('api.oppurtunity.addoptlist');
        Route::post('products/msps','api\StaffApiController@getProductsMSP')->name('api.products.msps');
        Route::get('oppertunity/{id}/product/add-list','api\StaffApiController@oppurtunityaddlist')->name('api.oppurtunity.addlist');
        Route::post('oppertunity/{id}/product/add','api\StaffApiController@oppurtunityAddProducts')->name('api.oppurtunity.add');
        Route::get('oppertunity/{id}/list/product','api\StaffApiController@oppurtunityListProducts')->name('api.oppurtunity.list');
        Route::post('oppertunity/{id}/quote/send','api\StaffApiController@generateQuote')->name('api.oppurtunity.generateQuote');
        Route::get('oppertunity/{id}/list/quote','api\StaffApiController@oppurtunityQuotelist')->name('api.oppurtunity.quote');
        Route::get('quote/{id}/preview/{name}.pdf','api\CustomerApiController@quotepdf')->name('api.oppurtunity.quote.pdf');
        Route::get('quote/{id}/delete','api\StaffApiController@deleteQuote')->name('api.oppurtunity.quote.delete');

        Route::get("/customer-{id}-oppertunity/{limit}/{offset}/list-all","api\CustomerApiController@getCustomerOppertunityAll")->name("api.cutomer-oppertunity.list.all");
        Route::get("/customer-{id}-contact/{limit}/{offset}/list","api\CustomerApiController@getCustomerContact")->name("api.cutomer-contact.list");
        Route::get('/customer-detail/{id}', 'api\CustomerApiController@getCustomerById')->name('api.getCustomerById');
        Route::get("/customer-{id}-ib/{limit}/{offset}/list","api\CustomerApiController@getCustomerIB")->name("api.cutomer-ib.list");

        Route::post('oppertunity/{id}/close/{status}/status','api\StaffApiController@oppurtunityStatusClose')->name('api.oppurtunity.oppurtunityStatusClose');
        Route::get('oppertunity/{id}/product/{product}/remove','api\StaffApiController@oppurtunityremoveProduct')->name('api.oppurtunity.removePrroduct');
        Route::post('oppertunity/{id}/product/{product}/edit','api\StaffApiController@oppurtunityeditProduct')->name('api.oppurtunity.editPrroduct');

        Route::post('task-hospital-start','api\StaffApiController@task_hospital_start')->name('api.task-hospital-start');
        Route::post('task-hospital-end','api\StaffApiController@task_hospital_end')->name('api.task-hospital-end');
        Route::post('update/location/current', 'api\StaffApiController@update_current_location')->name("api.update-current-location");
        
        Route::get("/page/{limit}/{offset}/list-all","api\PageController@getAllPage")->name("api.page.list.all");

        Route::post('customer/{customerId}/equipment-count', 'api\TargetApiController@getCustomerEquipmentCount')->name("getCustomerEquipmentCount");


    }
);
Route::group(
    ['prefix' => 'v1', 'as' => 'v1.'],
    function () {
        Route::post('/products', 'api\AppApiController@getProducts')->name("api.getProducts");
        Route::get('/product-categorys', 'api\AppApiController@getCategory')->name("api.getCategory");
        Route::get('/product-brands', 'api\AppApiController@getBrand')->name("api.getBrand");
    }
);




 