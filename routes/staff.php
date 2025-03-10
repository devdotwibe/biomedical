<?php

use Illuminate\Support\Facades\Route;

Route::get('staff/target/list', 'staff\AdminStaffTargetController@index')->name("staff.target.index");
Route::get('staff/target/commission/list', 'staff\AdminStaffTargetController@targetCommission')->name("staff.target.commission.index");
Route::get('staff/target/commission/completestatus', 'staff\AdminStaffTargetController@completestatus')->name("staff.target.commission.completestatus");
Route::post('staff/{id}/target/commission/add', 'staff\AdminStaffTargetController@addTargetCommission')->name("staff.target.commission.add");
Route::post('staff/target/commission/attaches/add', 'staff\AdminStaffTargetController@addOpurtunityAttaches')->name("staff.target.commission.attacheadd");
Route::get('staff/{staff}/target/commission/{id}/view', 'staff\AdminStaffTargetController@getOpurtunityCommission')->name("staff.target.commission.view");
Route::get('staff/target/commission/{id}/attaches', 'staff\AdminStaffTargetController@getOpurtunityAttaches')->name("staff.target.commission.attaches");
Route::get('staff/{staff}/target/commission/{id}/payview', 'staff\AdminStaffTargetController@getOpurtunityCommissionApproved')->name("staff.target.commission.payview");
Route::post('staff/{id}/target/commission/approve', 'staff\AdminStaffTargetController@approveTargetCommission')->name("staff.target.commission.approve");
Route::get('staff/target/staff', 'staff\AdminStaffTargetController@staffdata')->name("staff.target.data");
Route::post('staff/target/staff/update', 'staff\AdminStaffTargetController@updatePeriod')->name("staff.target.updatePeriod");
Route::post('staff/target/month/staff/add', 'staff\AdminStaffTargetController@addmonthTarget')->name("staff.target.addmonth");
Route::post('staff/target/commission/staff/add', 'staff\AdminStaffTargetController@addmonthTargetCommission')->name("staff.target.addmonthcommission");
Route::post('staff/target/paid/staff/add', 'staff\AdminStaffTargetController@addmonthPaidTarget')->name("staff.target.addmonthpaid");
Route::post('staff/target/brand/staff/remove', 'staff\AdminStaffTargetController@removeBrand')->name("staff.target.removeBrand");
Route::get('staff/target/paid/staff/list', 'staff\AdminStaffTargetController@staffmonthpaidlist')->name("staff.target.getmonthpaid");
Route::get('staff/target/commission/staff/list', 'staff\AdminStaffTargetController@staffmonthcommissionlist')->name("staff.target.getmonthcommission");
Route::post('staff/{staff}/target/{oppertunity}/commission/change/status', 'staff\AdminStaffTargetController@changeTargetCommissionStatus')->name("staff.target.commission.status");

Route::get('staff/target/create', 'staff\AdminStaffTargetController@create')->name("staff.target.create");
Route::post('staff/target/store', 'staff\AdminStaffTargetController@store')->name("staff.target.store");
Route::get('staff/target/{id}/edit', 'staff\AdminStaffTargetController@edit')->name("staff.target.edit");
Route::get('staff/target/{id}/show', 'staff\AdminStaffTargetController@show')->name("staff.target.show");
Route::delete('staff/target/{id}/delete', 'staff\AdminStaffTargetController@destroy')->name("staff.target.delete");


Route::get('staff/target/report', 'staff\AdminStaffTargetController@targetReport')->name("staff.target.report");
Route::get('staff/target/customer', 'staff\AdminStaffTargetController@customerlist')->name("staff.target.customer");
Route::get('staff/target/district', 'staff\AdminStaffTargetController@getDistrict')->name("staff.target.district");
Route::get('staff/target/staff/report', 'staff\AdminStaffTargetController@filterreport')->name("staff.target.detailreport");

Route::post('task/hospital/start', 'staff\TaskController@task_hospital_start')->name("task_hospital_start");
Route::post('task/hospital/end', 'staff\TaskController@task_hospital_end')->name("task_hospital_end");
Route::post('update/location/current', 'staff\AdminajaxController@update_current_location')->name("update_current_location");

Route::post('/store-pm-details', 'staff\ServiceController@storePmdetails')->name('store_pm_details');

Route::post('/assign-Pmdetails', 'staff\ServiceController@assignPmdetails')->name('assignPmdetails');

Route::get('/manage/{id}/task-entry-location', 'staff\TaskManageController@manage_task_entry_location')->name('manage-task-entry-location');
Route::post('/manage-task/expence/update-status', 'staff\TaskManageController@work_update_expence_update')->name('manage-task.expence.update');
Route::get('/manage-staff-task-location', 'admin\TaskManageController@staff_task_location')->name('manage-staff-task-location');
Route::post('/manage-task/staff/frees-status', 'staff\TaskManageController@work_update_staff_detail_change')->name('manage-task.staff.freez');
Route::get('/manage-staff-task-time', 'staff\TaskManageController@staff_task_time')->name('manage-staff-task-time');
// Route::get('/work-report', 'staff\WorkReportController@index')->name('work-report.index');

Route::get('/staff-sale/report','staff\StaffReportController@index')->name('staff-report');

Route::get('/staff-sale/staff_equip_sales','staff\StaffReportController@staff_equip_sales')->name('staff_equip_sales');

Route::get('/staff-sale/staff_sales_parts','staff\StaffReportController@staff_sales_parts')->name('staff_sales_parts');

Route::get('/staff-sale/staff_msa_service','staff\StaffReportController@staff_msa_service')->name('staff_msa_service');

Route::get('/staff-sale/oppertunity_accesseries','staff\StaffReportController@oppertunity_accesseries')->name('oppertunity_accesseries');

Route::get('/staff-sale/oppertunity_equipment','staff\StaffReportController@oppertunity_equipment')->name('oppertunity_equipment');

Route::get('/staff-sale/oppertunity_parts','staff\StaffReportController@oppertunity_parts')->name('oppertunity_parts');

Route::get('/staff-sale/oppertunity_msa_staff','staff\StaffReportController@oppertunity_msa_staff')->name('oppertunity_msa_staff');

Route::get('/staff-sale/staff_report_district','staff\StaffReportController@staff_report_district')->name('staff_report_district');

Route::get('/staff-sale/staff_report_category','staff\StaffReportController@staff_report_category')->name('staff_report_category');

Route::get('/staff-sale/staff_report_modality','staff\StaffReportController@staff_report_modality')->name('staff_report_modality');

Route::get('/staff-sale/special_task','staff\StaffReportController@special_task')->name('special_task');

Route::get('/staff-sale/quick_links','staff\StaffReportController@quick_links')->name('quick_links');

Route::get('/staff-sale/staff_corrective','staff\StaffReportController@staff_corrective')->name('staff_corrective');

Route::get('/staff-sale/staff_pm','staff\StaffReportController@staff_pm')->name('staff_pm');

Route::get('/staff-sale/staff_installation','staff\StaffReportController@staff_installation')->name('staff_installation');

Route::get('/staff-sale/staff_expense','staff\StaffReportController@staff_expense')->name('staff_expense');

Route::get('/staff-sale/staff_work_update','staff\StaffReportController@staff_work_update')->name('staff_work_update');


Route::prefix('work')->group(function () {
    Route::get('/work-report/travel/attendance/status', 'staff\WorkReportController@attendance')->name('work-report.attendance');
    Route::get('/work-report/expence/list', 'staff\WorkReportController@expence')->name('work-report.expence');
    Route::get('/work-report', 'staff\WorkReportController@show')->name('work-report.show');
    Route::post('/work-report/travel/{id}/update', 'staff\WorkReportController@update')->name('work-report.update');
    Route::post('/work-report/travel/{id}/store', 'staff\WorkReportController@store')->name('work-report.store');
});