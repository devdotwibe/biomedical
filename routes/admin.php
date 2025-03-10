<?php

use Illuminate\Support\Facades\Route;


Route::get('staff/target/report', 'admin\StaffTargetController@targetReport')->name("staff.target.report");
Route::get('staff/target/customer', 'admin\StaffTargetController@customerlist')->name("staff.target.customer");
Route::get('staff/target/district', 'admin\StaffTargetController@getDistrict')->name("staff.target.district");
Route::get('staff/target/staff/report', 'admin\StaffTargetController@filterreport')->name("staff.target.detailreport");
Route::resource('/manage-task', 'admin\TaskManageController');
Route::get('/manage/{id}/task-entry-location', 'admin\TaskManageController@manage_task_entry_location')->name('manage-task-entry-location');

Route::post('/manage-task/expence/update-status', 'admin\TaskManageController@work_update_expence_update')->name('manage-task.expence.update');
Route::post('/manage-task/staff/frees-status', 'admin\TaskManageController@work_update_staff_detail_change')->name('manage-task.staff.freez');
Route::get('/manage-staff-location', 'admin\TaskManageController@staff_location')->name('manage-staff-location');

Route::resource('/reminder', 'admin\ReminderController');

// Route::post('service-newVisitTask', 'admin\ServiceController@newServiceVistTask')->name('service-newVisitTask');
Route::post('/store-pm-details', 'admin\ServiceController@storePmdetails')->name('store_pm_details');

Route::post('/assign-Pmdetails', 'admin\ServiceController@assignPmdetails')->name('assignPmdetails');

Route::get('/manage-staff-task-location', 'admin\TaskManageController@staff_task_location')->name('manage-staff-task-location');
Route::get('/manage-staff-task-time', 'admin\TaskManageController@staff_task_time')->name('manage-staff-task-time');