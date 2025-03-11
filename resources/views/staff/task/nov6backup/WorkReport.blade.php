@extends('staff/layouts.app')
@section('title', 'Work Report')
@section('content')
<style>
.open_gif {
    background: rgba(255,255,255,0.7);
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    display: inline-block;
    z-index: 9999;
    text-align: center;
}
.open_gif img {
    transform: translateY(-50%);
    top: 50%;
    left: 0;
    right: 0;
    margin: 0 auto;
    display: inline-block;
    position: absolute;
}
</style>
<?php
date_default_timezone_set("Asia/Calcutta"); 
?>
<section class="content-header">
      <h1>
      Work Report <span id="work_date_dis"></span>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Work Report </li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
                <div class="row">
        <div class="col-lg-12 margin-tb">
        </div>
    </div>
    <div class="open_gif"><img src="{{ asset('images/wait.gif') }}"></div>
            @if (session('success'))
                <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">
              <form enctype="multipart/form-data" name="dataForm" id="dataForm" method="post" action="{{ url('/staff/task/deleteAll') }}" />
              @csrf
<input type="hidden" name="search_date" id="search_date">
<input type="hidden" name="travel_type_hidden" id="travel_type_hidden">
<input type="hidden" name="staff_id" id="staff_id" value="<?php echo session('STAFF_ID');?>">
<input type="hidden" name="travel_start_read_hidden" id="travel_start_read_hidden">
<input type="hidden" name="task_id_hidden" id="task_id_hidden">
<input type="hidden" name="time_dis_id" id="time_dis_id">
<input type="hidden" name="work_approval_travel" id="work_approval_travel">
              <div class="datesection">
              <?php $months = array(
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July ',
    'August',
    'September',
    'October',
    'November',
    'December',
);
$staff_id = session('STAFF_ID');
?>
              <div class="form-group col-md-6">
              <select disabled="true" class="form-control"  name="leave_month" id="leave_month" onchange="change_leave_month(this.value)">
              <?php 
                echo '<option value="">Select Month</option>';
              for($i =0; $i < count($months); $i++){
                $sel = ($months[$i] == date("F")) ? 'selected': '';
                echo '<option value="'.$months[$i].'" '.$sel.'>'.$months[$i].'</option>';
              }
              ?>
              </select>
              <div class="viewcounts">
              <?php
     $taskcheck_fullday = DB::select("SELECT * FROM work_report_for_leave where staff_id='".$staff_id."'   AND  MONTH(start_date) = MONTH(CURRENT_DATE()) AND type_leave='Request Leave' AND attendance='Full Day'");
     $taskcheck_halfday = DB::select("SELECT * FROM work_report_for_leave where staff_id='".$staff_id."'   AND  MONTH(start_date) = MONTH(CURRENT_DATE()) AND type_leave='Request Leave' AND attendance='Half Day'");
     $attendence = DB::select("SELECT * FROM work_report_for_leave where staff_id='".$staff_id."'   AND  MONTH(start_date) = MONTH(CURRENT_DATE()) AND (type_leave='Request Leave Office Staff' or type_leave='Request Leave Field Staff') ");
     $half_day=count($taskcheck_halfday)/2;
   $leave=count($taskcheck_fullday)+$half_day;
    $notupdated=date("d");
    $noupdated_task=$notupdated-count($attendence);
              ?>
              <span> Work (<?php echo count($attendence);?>) </span>
              <span>Leave (<?php echo $leave;?>)</span>
              <span>Not Updated (<?php echo $noupdated_task-$leave;?>)</span>
               </div>
              </div>
              <br>
              <div class="form-group col-md-12 month_dispaly">
                <?php
               $date = date('Y-m-d', strtotime("11 days")); //today date
               $weekOfdays = array();
               $cur_date=date("Y-m-d");
               for($i =1; $i <= 41; $i++){
                 if($i==1)
                 {
                   ?>
                   <div class="panel panel-default col-sm-2" onclick="change_date('<?php echo $date;?>','0')">
  <div class="panel-body"><?php   echo $date;?></div>
</div>
                   <?php
                 }
                 $date = date('Y-m-d', strtotime('-1 day', strtotime($date)));
                 if (strtotime($cur_date) >= strtotime($date)) {
                  $a=1;
                 }
                 else{
                  $a=0;
                 }
                 if (strtotime($cur_date) == strtotime($date)) {
                  ?>
 <div class="panel panel-default col-sm-2 work-green"  onclick="change_date('<?php echo  $date;?>','<?php echo $a;?>')">
  <div class="panel-body"><?php   echo $date;?></div>
</div>
                  <?php
                 }
                 else{
                  $status_date='';
                  $status_date_half='';
                   if($a>0)
                   {
               //    $taskcheck = DB::select("SELECT * FROM dailyclosing_expence as day_exp INNER JOIN task_comment as task_comment ON day_exp.travel_task_id=task_comment.task_id where day_exp.staff_id='".$staff_id."'  AND day_exp.start_date='".$date."'  AND day_exp.travel_task_id>0");
               $taskcheck =DB::select('CALL dailyclosing_exit_check("'.$staff_id.'","'.$date.'")');
               $taskcheck_work_report =DB::select('SELECT id from work_report_for_office where staff_id="'.$staff_id.'" AND start_date="'.$date.'" order by id desc');
               $task_pending_travel =DB::select('CALL daily_expence_staff_status_pending("'.$staff_id.'","'.$date.'")');
               $task_pending_office =DB::select('CALL work_office_status_pending("'.$staff_id.'","'.$date.'")');
               if(count($taskcheck)==0 && count($taskcheck_work_report)==0)
               {
                 $noadded_task='1';
               }
               else{
                $noadded_task='0';
               }
               $leave_added = DB::select('SELECT id,attendance from work_report_for_leave where staff_id="'.$staff_id.'" AND start_date="'.$date.'"  AND type_leave="Request Leave" ');
               $attendence_added = DB::select('SELECT id from work_report_for_leave where staff_id="'.$staff_id.'" AND start_date="'.$date.'" AND (type_leave="Request Leave Office Staff" OR type_leave="Request Leave Field Staff")  ');
               if(count($leave_added)>0)
               {
                if($leave_added[0]->attendance=="Half Day")
                {
                  $status_date .="Leave. ";
                  if($noadded_task==1)
                  {
                  $status_date .="Task Not Added";
                  }
                  else{
                    if(count($task_pending_travel)>0 || count($task_pending_office)>0)
                    {
                      if(count($attendence_added)>0 ){
                        $status_date .="Attendance  Added";
                      }
                      else{
                        $status_date .="Approval Pending";
                      }
                     
                    }
                    else if(count($attendence_added)==0 )
                    {
                      $status_date .="Attendance Not Added";
                    }
                    else{
                      $status_date .="Attendance  Added";
                    }
                  }
                }
                else{
                  $status_date="Leave";
                }
               }
               else{
                  if($noadded_task==1)
                  {
                  $status_date="Task Not Added";
                  }
                  else{
                    if(count($task_pending_travel)>0 || count($task_pending_office)>0)
                    {
                     
                      if(count($attendence_added)>0 ){
                        $status_date .="Attendance  Added";
                      }
                      else{
                        $status_date .="Approval Pending";
                      }

                    }
                    else if(count($attendence_added)==0 )
                    {
                      $status_date="Attendance Not Added";
                    }
                    else{
                      $status_date="Attendance  Added";
                    }
                  }
               }
                   if(count($taskcheck)==0)
                   {
                     if(count($taskcheck_work_report)==0)
                     {
                      $work_status='work-yellow';
                     }
                      if(count($attendence_added)>0){
                        $work_status='work-grey';
                     }
                   }
                   else{
                   // $pending_task = DB::select("SELECT * FROM dailyclosing_expence as day_exp INNER JOIN task_comment as task_comment ON day_exp.travel_task_id=task_comment.task_id where day_exp.staff_id='".$staff_id."' AND task_comment.status='R' AND day_exp.start_date='".$date."'  AND day_exp.travel_task_id>0");
                   $pending_task = DB::select('CALL dailyclosing_reject_check("'.$staff_id.'","'.$date.'")');
                    if(count($pending_task)>0)
                    {
                      $work_status='work-red';
                    }
                    else{
                     //$pending_task_rej = DB::select("SELECT * FROM dailyclosing_expence as day_exp INNER JOIN task_comment as task_comment ON day_exp.travel_task_id=task_comment.task_id where day_exp.staff_id='".$staff_id."' AND task_comment.status='N' AND day_exp.start_date='".$date."' AND day_exp.travel_task_id>0");
                     $pending_task_rej = DB::select('CALL dailyclosing_pending_check("'.$staff_id.'","'.$date.'")');
                      if(count($pending_task_rej)>0)
                      {
                        $work_status='work-yellow';
                      }
                      else{
                        $work_status='work-grey';
                      }
                    }
                   }
                   }
                   else{
                    $work_status='';
                   }
                   ?>
                    <div class="panel panel-default col-sm-2 <?php echo $work_status;?>"  onclick="change_date('<?php echo  $date;?>','<?php echo $a;?>')">
  <div class="panel-body"><?php   echo $date;  if(date("D",strtotime($date))=="Sun"){ echo " (Sunday)";} echo '<br>'.$status_date;?></div>
</div>
                   <?php
                 }
                 ?>
                 <?php
               }
                ?>
                <input type="hidden" name="work_date" id="work_date">
                <input type="hidden" name="cur_year" id="cur_year" value="<?php  echo date("Y");?>">
                </div>
              </div>
              <div class="option_section" style="display:none;">
              <div class="form-group col-md-11">
              <select class="form-control"  name="leave_option" id="leave_option" onchange="change_leave_options(this.value)">
               <option value="">Select Options</option>
               <option value="Request For Leave">Request For Leave</option>
               <option value="Work Update Office">Work Update Office</option>
               <option value="Work Update Field Staff">Work Update Field Staff</option>
               </select>
               <div class="load_leave_option" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
               </div>
              </div>
               <div class="work_officesection" style="display:none;">
               <div class="display_office_staff_all"></div>
                   <div class="form-group col-md-12">
                   <div class="form-check col-md-12 start_workbtn_office">
                  <button type="button" class="btn btn-primary"  onclick="start_work_display_office()">Start Work</button>
                   </div>
                   <div class="form-group col-md-3 start_work_office" style="display:none;">
                  <label  for="meter_start">Time </label>
                      <input class="form-check-input"  readonly type="text" name="office_start_time" id="office_start_time" value="<?php echo date("H:i")?>" placeholder="Meter Reading">
                      <span class="error_message" id="office_start_time_message" style="display: none">Field is required</span>
                    </div>
                    <div class="box-footer col-md-3 start_work_office" style="display:none;">
                      <button type="button" class="btn btn-primary"  onclick="submit_office_start_work()">Submit</button>
                      <div class="load_office_start_work" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                    <div class="display_start_time_ofice"></div>
                    </div>
                     <div class=" col-md-12 show_add_task_sec" style="display:none;"> 
                     <div class="form-group col-sm-2 officestaff_tasksec">
                    <label  for="meter_start">Task </label>
                    <select class="form-control"  name="office_task_id[]" id="office_task_id" multiple="multiple">
                  <!-- <option value="">Select Task</option> -->
                  @if(count($alltask)>0)
                    @foreach($alltask as $values)
                    <option value="{{$values->id}}" attr-name="{{$values->name}}">{{$values->name}} ({{$values->start_date}})</option>
                    @endforeach
                    @endif
                  </select>
                  <span class="error_message" id="office_task_id_message" style="display: none">Field is required</span>
                  </div>
                    <div class="box-footer col-md-3 officestaff_tasksec">
                      <button type="button" class="btn btn-primary"  onclick="submit_task_officestaff()">Submit</button>
                      <div class="load_task_officestaff" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                    <div class="display_taskdetail_ofice"></div>
                    </div>
                     <div class=" col-md-12"> 
                   <div class="form-group col-md-12">
                  <button type="button" class="btn btn-primary end_work_display_office" style="display:none;"  onclick="end_work_display_office()">End Work</button>
                  <button type="button" class="btn btn-primary end_work_display_office" style="display:none;"  onclick="addmore_task_office()">Add More Task</button>
                   </div>
                   <div class="form-group col-md-3 end_work_office" style="display:none;"> 
                  <label  for="meter_start">Time </label>
                      <input class="form-check-input" readonly type="text" name="office_end_time" id="office_end_time" value="<?php echo date("H:i")?>" placeholder="Meter Reading">
                      <span class="error_message" id="office_end_time_message" style="display: none">Field is required</span>
                      <button type="button" class="btn btn-primary"  onclick="submit_office_end_work()">Submit</button>
                      <div class="load_office_end_work" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                    </div>
                 <div class="display_end_time_ofice"></div>
                 <div class="expence_results_office_sec"></div>
                   <div class="box-footer expence_sec_office col-md-12" >
                      <button type="button" class="btn btn-primary"  onclick="add_expence_office_staff(0)">Expense +</button>
                  </div> 
                    <div class="form-check col-sm-2 expence_sec_office_staff0"  style="display: none">
                      <label  for="meter_start">Other Expence </label>
                      <select class="form-control"  name="other_expence_office[]" id="other_expence_office0"  onchange="change_expence_office(0)">
                    <option value="">Select Other Expence</option>
                    <option value="Courier">Courier</option>
                    <option value="Print Out">Print Out</option>
                    <option value="Other">Other (with prior approval)</option>
                    </select>
                    <span class="error_message" id="other_expence_office0_message" style="display: none">Field is required</span>
                    </div>
                    <div class="form-check col-sm-2 child_exp_office0" style="display: none">
                  <label  for="meter_start">Amount </label>
                      <input class="form-check-input" type="number" name="expence_amount_office[]" id="expence_amount_office0" value="" placeholder="Amount">
                      <span class="error_message" id="expence_amount_office0_message" style="display: none">Field is required</span>
                    </div>
                    <div class="form-check col-sm-2 child_exp_office0" style="display: none">
                  <label  for="meter_start">Attach photo </label>
                      <input class="form-check-input"  type="file" name="expence_doc_office[]" id="expence_doc_office0" value="" >
                    </div>
                    <div class="form-check col-sm-4 child_exp_office0" style="display: none">
                  <label  for="meter_start">Description </label>
                      <textarea class="form-check-input"  name="expence_desc_office[]" id="expence_desc_office0"  placeholder="Description"></textarea>
                      <span class="error_message" id="expence_desc_office0_message" style="display: none">Field is required</span>
                    </div>
                  <div class="form-check col-sm-2 child_exp_office0" style="display: none">
                    <label  for="meter_start">Task </label>
                    <select class="form-control"  name="expence_task_id_office[]" id="expence_task_id_office0">
                  <option value="">Select Task</option>
                  @if(count($alltask)>0)
                    @foreach($alltask as $values)
                    <option value="{{$values->id}}" attr-name="{{$values->name}}">{{$values->name}} ({{$values->start_date}})</option>
                    @endforeach
                    @endif
                  </select>
                  <span class="error_message" id="expence_task_id_office0_message" style="display: none">Field is required</span>
                  </div>
                  <div class="box-footer col-md-12 child_exp_office0" style="display: none">
                      <button type="button" class="btn btn-primary submit_expence_office_staff"  onclick="submit_expence_office_staff(0)">Submit</button>
                      <div class="load_expence_office_staff0" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                    <input type="hidden" name="expence_count_office_staff" value="0" id="expence_count_office_staff"> 
                    <div class="more_expence_display_sec_office"></div>
                  <div class="box-footer more_expence_btn_off_staff col-md-12" style="display:none;">
                      <button type="button" class="btn btn-primary"  onclick="add_moreexpence_office_staff(0)">Expense +</button>
                  </div> 
 <div class="form-group col-md-12 ">
                 <div class="box-footer  col-md-12 office_staff_attendence_btn" >
                      <button type="button" class="btn btn-primary"  onclick="add_leave_office_staff()">Attendance +</button>
                  </div> 
                    <div class="form-check col-sm-2 levedrop_secoffice_staff" style="display:none;">
                      <label  for="meter_start">Attendance </label>
                      <select class="form-control"  name="staff_leave_office_staff" id="staff_leave_office_staff">
                    <option value="">Select Attendance</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Full Day">Full Day</option>
                    </select>
                    <span class="error_message" id="staff_leave_office_staff_message" style="display: none">Field is required</span>
                    </div>
                    <div class="box-footer col-md-12 levedrop_secoffice_staff" style="display:none;">
                      <button type="button" class="btn btn-primary"  onclick="submit_leaveoffice_staff()">Submit</button>
                    </div>
                    <div class="leave_data_officestaff"></div>
                    <!-- *********************************************************** -->
                    @if(count($verify_task)>0)
<div class="verifyhead">
                <h3>Verify Task</h3></div>
                <table id="cmsTable" class="table table-bordered table-striped data- cmsTabletravel">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Task</th>
                  <th>Start Date</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                       @foreach ($verify_task as $values)
                    <tr  id="tr_{{$values->task_id}}" data-id="{{$values->task_id}}" data-from ="task">
                        <td data-th="No.">
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Task"> <a class="popup" data-id="{{$values->task_id}}">{{$values->name}}</a> </td>
                        <td data-th="Start Date">{{$values->start_date}} </td>
                      </tr>
                       <?php $i++ ?>
                     @endforeach
                            </tbody>
                        </table>
@endif
                        <br>
                <div class="col-md-12">
                <h3>Pending Task</h3>
                <div class="pull-right option_section"  style="display:none;">
                <a class="btn btn-sm btn-success" href="{{ route('staff.task.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Task</a>
                </div>
                </div>
                     <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Task</th>
                  <th>Company</th>
                  <th>Client</th>
                  <th>Assignees</th>
                  <th>Start Date</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                       @foreach ($failed_arr as $values)
                      <?php
                       $product = App\Task::find($values);
                      ?>
                    <tr  id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="task">
                        <td data-th="No.">
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Task"><?php echo $product->name ?></td>
                        <td data-th="Company"><?php 
                        if($product->company_id>0){
                        $company = App\Company::find($product->company_id);
                            echo $company->name;}
                            ?></td>
                        <td data-th="Client"><a target="_blank" href="{{ route('staff.customer.show',$product->user_id) }}"><?php 
                        if($product->user_id>0){
                        $client = App\User::find($product->user_id);
                            echo $client->business_name;}
                            ?></a></td>
                              <td data-th="Assignees"><?php
                            $staff_all=explode(',',$product->assigns);
                          foreach($staff_all as $val_staff)
                            {
                              if($val_staff>0){
                              $staff = App\Staff::find($val_staff);
                            echo $staff->name.'<br> ';
                          }
                            }
                          ?>
                        </td>
                        <td data-th="Start Date"><?php echo $product->start_date ?></td>
                      </tr>
                       <?php $i++ ?>
                     @endforeach
                            </tbody>
                        </table>
                    <!-- *********************************************************** -->
                  </div>
                    </div>
                 <div class="leave_section" style="display:none;">
                  <div class="form-check col-sm-2 levedrop_sec_office">
                      <label  for="meter_start">Leave </label>
                      <select class="form-control"  name="staff_leave_office" id="staff_leave_office">
                    <option value="">Select Leave</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Full Day">Full Day</option>
                    </select>
                    <span class="error_message" id="staff_leave_office_message" style="display: none">Field is required</span>
                    </div>
                    <div class="form-check col-sm-2 levedrop_sec_office" >
                  <label  for="meter_start">Reason For Leave </label>
                      <textarea class="form-check-input"  name="reson_leave" id="reson_leave" value="" placeholder="Reason For Leave"></textarea>
                      <span class="error_message" id="reson_leave_message" style="display: none">Field is required</span>
                    </div>
                    <div class="box-footer col-md-12 leave_section levedrop_sec_office"  style="display:none;">
                      <button type="button" class="btn btn-primary"  onclick="submit_leave_office()">Submit</button>
                      <div class="load_leave_office" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                <div class="leave_data_office"></div>
                <div class="dis_ajax_req_leave"></div>
                  </div>
                 <div class="work_fildsection" style="display:none;">
                  <div class="form-group col-md-12">
                  <div class="display_add_data"></div> 
                  <div class="box-footer">
                      <button type="button" class="btn btn-primary travel_first"  onclick="travel_first()">Travel +</button>
                  </div>
                  <div class="travel_section" style="display:none;">
                  <div class="travel_sec0">
                    <label class="travtype_sec">Start Travel</label>
                     <div class="form-group col-md-12">
                     <div class="form-check col-sm-2 travtype_sec">
                    <label  for="meter_start">Travel Type </label>
                    <select class="form-control"  name="travel_type[]" id="travel_type0" onchange="change_travel(this.value,0)">
                  <option value="">Select Travel Type</option>
                  <option value="Bike">Bike</option>
                  <option value="Car">Car (with prior approval)</option>
                  <option value="Train">Train</option>
                  <option value="Bus">Bus</option>
                  <option value="Auto">Auto</option>
                  </select>
                  </div>
                  <div class="form-check col-sm-2 carbike_sec0" style="display:none" >
                  <label  for="meter_start">Meter Reading </label>
                      <input class="form-check-input"  type="number" name="meter_start[]" id="meter_start0" value="" placeholder="Meter Reading">
                      <span class="error_message" id="meter_start0_message" style="display: none">Field is required</span>
                    </div>
                    <div class="form-check col-sm-2 bike_train_sec0" style="display:none">
                  <label  for="meter_start">Time </label>
                      <input class="form-check-input" readonly type="text" name="start_time[]" id="start_time0" value="<?php echo date("H:i")?>" placeholder="Meter Reading">
                    </div>
                    <div class="form-check col-sm-2 bike_train_sec0" style="display:none">
                  <label  for="meter_start">Attach photo </label>
                      <input class="form-check-input"  type="file" name="fair_doc[]" id="fair_doc0" value="" >
                    </div>
                    <div class="form-check col-sm-2 bike_train_sec0" style="display:none" onchange="change_hospital(0)">
                    <label  for="meter_start">Hospital </label>
                    <select class="form-control user_id"  name="user_id[]" id="user_id0">
                    <option value="">Select Hospital</option>
                    @if(count($user)>0)
                    @foreach($user as $values)
                    <option value="{{$values->id}}" >{{$values->business_name}} </option>
                    @endforeach
                    @endif
                    <option value="Other" >Other </option>
                    </select>
                  <span class="error_message" id="user_id0_message" style="display: none">Field is required</span>
                  </div>
                    <div class="form-check col-sm-2 bike_train_sec0" style="display:none">
                    <label  for="meter_start">Task </label>
                    <select class="form-control task_sec"  name="travel_task_id[]" multiple="multiple"  id="travel_task_id0" onchange="change_task(this.value)">
                    <option value="">Select Task</option>
                    </select>
                  <span class="error_message" id="travel_task_id0_message" style="display: none">Field is required</span>
                  </div>
                  </div>
                    <div class="box-footer col-md-12 travtype_sec add_start_travel">
                      <button type="button" class="btn btn-primary"  onclick="add_start_travel(0)">Submit</button>
                      <div class="load_travel0" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                 <div class="travel_end_sec0" style="display:none;">
                 <div class="form-check col-md-12">
                 <button type="button" class="btn btn-primary"  onclick="viewednd_travel(0)">End Travel</button>
                 <button type="button" class="btn btn-primary addmore_field"   onclick="addmore_task_field(0)">Add More Task</button>
                 </div>
                 <div class="addmore_task0" style="display:none;">
                 <div class="form-check col-sm-2 "  onchange="change_hospital_field_staff(0)">
                    <label  for="meter_start">Hospital </label>
                    <select class="form-control user_id"  name="hospital_id[]" id="hospital_id0">
                    <option value="">Select Hospital</option>
                    @if(count($user)>0)
                    @foreach($user as $values)
                    <option value="{{$values->id}}" >{{$values->business_name}} </option>
                    @endforeach
                    @endif
                    <option value="Other" >Other </option>
                    </select>
                  </div>
                     <div class="form-group col-sm-2 ">
                    <label  for="meter_start">Task </label>
                    <select class="form-control task_sec"  name="more_office_task_id[]" id="more_office_task_id0" multiple="multiple">
                  </select>
                  <span class="error_message" id="more_office_task_id0_message" style="display: none">Field is required</span>
                  </div>
                    <div class="box-footer col-md-3 ">
                      <button type="button" class="btn btn-primary"  onclick="save_fieldstaff_moretask(0)">Submit</button>
                      <div class="save_fieldstaff_moretask" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                 </div>
                  <div class="form-check col-sm-2 carbike_sec_end0 tra_end_sec" style="display:none;">
                  <label  for="meter_start">Meter Reading </label>
                      <input class="form-check-input"  type="number" name="meter_end[]" id="meter_end0" value="" placeholder="Meter Reading">
                      <span class="error_message" id="meter_end0_message" style="display: none">Field is required</span>
                      <span class="error_message" id="meter_end_error0_message" style="display: none">End reading must be greater than your start reading.</span>
                    </div>
                    <div class="form-check col-sm-2 tra_end_sec"  style="display:none;">
                  <label  for="meter_start">Time </label>
                      <input class="form-check-input" readonly type="text" readonly name="end_time[]" id="end_time0" value="<?php echo date("H:i")?>" placeholder="Meter Reading">
                    </div>
                    <div class="form-check col-sm-2 other_sec_end0 tra_end_sec"  style="display:none;">
                  <label  for="meter_start">Amount </label>
                      <input class="form-check-input" type="number" name="amount_end[]" id="amount_end0" value="" placeholder="Amount">
                      <span class="error_message" id="amount_end0_message" style="display: none">Field is required</span>
                    </div>
                     <div class="form-check col-sm-2 tra_end_sec"  style="display:none;">
                  <label  for="meter_start">Attach photo </label>
                      <input class="form-check-input"  type="file" name="fair_doc_end[]" id="fair_doc_end0" value="" >
                    </div>
                     <div class="box-footer col-md-12 tra_end_sec"  style="display:none;">
                      <button type="button" class="btn btn-primary"  onclick="add_end_travel(0)">Submit</button>
                      <div class="load_travel_end0" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                 </div>
                 </div>
                 </div>
                <input type="hidden" name="travel_count" value="0" id="travel_count"> 
                <div class="more_travel_display_sec"></div>
                  <div class="box-footer add_more_travel col-md-12" style="display:none">
                      <button type="button" class="btn btn-primary"  onclick="add_more_travel()">Travel +</button>
                  </div> 
<!-- expence section start -->
                  <div class="expence_results"></div>
                    <div class="box-footer expence_btsecfirst col-md-12 add_expence" >
                      <button type="button" class="btn btn-primary"  onclick="add_expence()">Expense +</button>
                  </div> 
                  <div class="first_expence_section" style="display:none;">
                    <div class="form-check col-sm-2 expence_sec_all0">
                      <label  for="meter_start">Other Expence </label>
                      <select class="form-control other_expence"  name="other_expence[]" id="other_expence0" attr-id='0' onchange="change_expence(0)">
                    <option value="">Select Other Expence</option>
                    <option value="Courier">Courier</option>
                    <option value="Print Out">Print Out</option>
                    <option value="Other">Other (with prior approval)</option>
                    </select>
                    <span class="error_message" id="other_expence0_message" style="display: none">Field is required</span>
                    </div>
                    <div class="form-check col-sm-2 other_sec_end0 exp_sec0 expence_sec_all0" style="display: none">
                  <label  for="meter_start">Amount </label>
                      <input class="form-check-input" type="number" name="expence_amount[]" id="expence_amount0" value="" placeholder="Amount">
                      <span class="error_message" id="expence_amount0_message" style="display: none">Field is required</span>
                    </div>
                    <div class="form-check col-sm-2 exp_sec0 expence_sec_all0" style="display: none">
                  <label  for="meter_start">Attach photo </label>
                      <input class="form-check-input"  type="file" name="expence_doc[]" id="expence_doc0" value="" >
                    </div>
                    <div class="form-check col-sm-4 other_sec_end0 exp_sec0 expence_sec_all0" style="display: none">
                  <label  for="meter_start">Description </label>
                      <textarea class="form-check-input"  name="expence_desc[]" id="expence_desc0"  placeholder="Description"></textarea>
                      <span class="error_message" id="expence_desc0_message" style="display: none">Field is required</span>
                    </div>
                  <div class="form-check col-sm-2 exp_sec0 expence_sec_all0" style="display: none">
                    <label  for="meter_start">Task </label>
                    <select class="form-control"  name="expence_task_id[]" id="expence_task_id0">
                  <option value="">Select Task</option>
                  </select>
                  <span class="error_message" id="expence_task_id0_message" style="display: none">Field is required</span>
                  </div>
                  <div class="box-footer col-md-12 exp_sec0 expence_sec_all0" style="display: none">
                      <button type="button" class="btn btn-primary submit_expence"  onclick="submit_expence(0)">Submit</button>
                      <div class="load_expence'+add_count+'" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                    <input type="hidden" name="expence_count" value="0" id="expence_count"> 
                    <div class="more_expence_display_sec"></div>
                  <div class="box-footer more_expence_btn col-md-12" style="display:none;">
                      <button type="button" class="btn btn-primary"  onclick="add_moreexpence(0)">Expense +</button>
                  </div> 
                      </div> 
                  </div>
<!-- expence section end -->
<!-- leave section start -->
               <div class="box-footer addleave_btsecfirst col-md-12" style="display:none;">
                      <button type="button" class="btn btn-primary"  onclick="add_leave()">Attendance +</button>
                  </div> 
                  <div class="first_leave_section " style="display:none;">
                    <div class="form-check col-sm-2 levedrop_sec">
                      <label  for="meter_start">Attendance </label>
                      <select class="form-control"  name="staff_leave" id="staff_leave">
                    <option value="">Select Attendance</option>
                    <option value="Half Day">Half Day</option>
                    <option value="Full Day">Full Day</option>
                    </select>
                    <span class="error_message" id="staff_leave_message" style="display: none">Field is required</span>
                    </div>
                    <div class="box-footer col-md-12 levedrop_sec">
                      <button type="button" class="btn btn-primary"  onclick="submit_leave()">Submit</button>
                      <div class="load_submit_leave" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>
                    <div class="leave_data"></div>
                  </div>
<!-- leave section start -->
@if(count($verify_task)>0)
<div class="verifyhead">
                <h3>Verify Task</h3></div>
                <table id="cmsTable" class="table table-bordered table-striped data- cmsTabletravel">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Task</th>
                  <th>Start Date</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                       @foreach ($verify_task as $values)
                    <tr  id="tr_{{$values->task_id}}" data-id="{{$values->task_id}}" data-from ="task">
                        <td data-th="No.">
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Task"> <a class="popup" data-id="{{$values->task_id}}">{{$values->name}}</a> </td>
                        <td data-th="Start Date">{{$values->start_date}} </td>
                      </tr>
                       <?php $i++ ?>
                     @endforeach
                            </tbody>
                        </table>
@endif
                        <br>
                <div class="col-md-12">
                <h3>Pending Task</h3>
                <div class="pull-right option_section"  style="display:none;">
                <a class="btn btn-sm btn-success" href="{{ route('staff.task.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Task</a>
                </div>
                </div>
                     <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Task</th>
                  <th>Company</th>
                  <th>Client</th>
                  <th>Assignees</th>
                  <th>Start Date</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                       @foreach ($failed_arr as $values)
                      <?php
                       $product = App\Task::find($values);
                      ?>
                    <tr  id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="task">
                        <td data-th="No.">
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Task"><?php echo $product->name ?></td>
                        <td data-th="Company"><?php 
                        if($product->company_id>0){
                        $company = App\Company::find($product->company_id);
                            echo $company->name;}
                            ?></td>
                        <td data-th="Client"><a target="_blank" href="{{ route('staff.customer.show',$product->user_id) }}"><?php 
                        if($product->user_id>0){
                        $client = App\User::find($product->user_id);
                            echo $client->business_name;}
                            ?></a></td>
                              <td data-th="Assignees"><?php
                            $staff_all=explode(',',$product->assigns);
                          foreach($staff_all as $val_staff)
                            {
                              if($val_staff>0){
                              $staff = App\Staff::find($val_staff);
                            echo $staff->name.'<br> ';
                          }
                            }
                          ?>
                        </td>
                        <td data-th="Start Date"><?php echo $product->start_date ?></td>
                      </tr>
                       <?php $i++ ?>
                     @endforeach
                            </tbody>
                        </table>
                 </div>
            </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
<div class="modal fade" id="replay_modal_expence" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Expence</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
         <div class="form-group">
                      <label  for="meter_start">Other Expence </label>
                      <select class="form-control"  name="other_expence_edit" id="other_expence_edit" attr-id='0' onchange="change_expence(0)">
                    <option value="">Select Other Expence</option>
                    <option value="Courier">Courier</option>
                    <option value="Print Out">Print Out</option>
                    <option value="Other">Other (with prior approval)</option>
                    </select>
                    <span class="error_message" id="other_expence_edit_message" style="display: none">Field is required</span>
                    </div>
                    <div class="form-group">
                  <label  for="meter_start">Amount </label>
                      <input class="form-check-input" type="number" name="expence_amount_edit" id="expence_amount_edit" value="" placeholder="Amount">
                      <span class="error_message" id="expence_amount_edit_message" style="display: none">Field is required</span>
                    </div>
                    <div class="form-group" >
                  <label  for="meter_start">Description </label>
                      <textarea class="form-check-input"  name="expence_desc_edit" id="expence_desc_edit"  placeholder="Description"></textarea>
                      <span class="error_message" id="expence_desc_edit_message" style="display: none">Field is required</span>
                    </div>
<input type="hidden" name="expence_id" id="expence_id">
<input type="hidden" name="type_expence" id="type_expence">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="update_edit_expence()">Update Expence</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div id="myModal" class="modal fade inprogress-popup" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title" id="taskname"></h4>
      </div>
      <div class="modal-body">
   <div class="row">
      <div class="col-md-8 task-single-col-left" >
                 <div class="clearfix"></div>
         <h4 class="th font-medium mbot15 pull-left">Description</h4>
                  <div class="clearfix"></div>
         <div class="tc-content"><div id="task_view_description"></div></div>         <div class="clearfix"></div>
         <div class="clearfix"></div>
            <h4 class="mbot20 font-medium">Comments</h4>
      	<div class="row pgrs-outer">
         <div class="col-md-8 tasks-comments inline-block full-width simple-editor">
  <form name="contactformedit" id="contactformedit" method="post" action"" />
            <input type="hidden" name="task_id" id="task_id">       
            <div><label class="form-check-label">Email 
            <input type="checkbox" name="email_status" id="email_status" class="form-check-input" value="Y">    </label> </div>
            <div><label class="form-check-label">Call 
            <input type="checkbox" name="call_status" id="call_status" class="form-check-input">    </label> </div>
            <div><label class="form-check-label">Visit 
            <input type="checkbox" name="visit_status" id="visit_status" class="form-check-input">    </label> </div>
            <div class="addcon_link"> <a id="contact_link" href="" target='_blank'>Add contact</a></div>
            <select name="contact_id" id="contact_id" class="form-control" multiple="multiple">
            <option value="">Select Contact</option>
            </select>
            <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
            <br>
            <textarea name="comment" placeholder="Add Comment" id="task_comment" rows="3" class="form-control ays-ignore"></textarea>
            <span class="error_message" id="task_comment_message" style="display: none">Field is required</span>
            <input type="file" id="image_name" name="image_name" accept=".jpg,.jpeg,.png"/>
            <span class="error_message" id="image_name_message" style="display: none">Field is required</span>
            <button type="button" class="btn btn-info mtop10 pull-left" id="addTaskCommentBtn" autocomplete="off" data-loading-text="Please wait..." >
            Add Comment            </button>
               <div class="load-sec" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                     <div class="clearfix"></div>
            </form>
            <div class="res_ajax containerscroll">
            </div>
      </div>
      </div>
   </div>
	<div class="col-md-4 task-single-col-right">
         <h4 class="task-info-heading">Task Info</h4>
         <div class="clearfix"></div>
         <h5 class="no-mtop task-info-created">
                        <small class="text-dark">Created at <span class="text-dark" id="created_at"></span></small>
                     </h5>
         <hr class="task-info-separator">
                  <div class="task-info task-single-inline-wrap task-info-start-date">
            <h5><i class="fa task-info-icon fa-fw fa-lg fa-calendar-plus-o pull-left fa-margin"></i>
               Start Date:
                              <span id="start_date"></span>
                           </h5>
         </div>
         <div class="task-info task-info-due-date task-single-inline-wrap">
            <h5>
               <i class="fa fa-calendar-check-o task-info-icon fa-fw fa-lg pull-left"></i>
               Due Date:
                            <span  id="due_date"></span>
                           </h5>
         </div>
         <div class="task-info task-info-priority">
            <h5>
               <i class="fa task-info-icon fa-fw fa-lg pull-left fa-bolt"></i>
               Priority:
                              <span class="task-single-menu task-menu-priority">
                  <span id="priority_dis" class="trigger pointer manual-popover text-has-action" style="color:#fc2d42;" data-original-title="" title="">
                                    </span>
               </span>
                           </h5>
         </div>
                  <div class="clearfix"></div>
         <hr class="task-info-separator">
         <div class="clearfix"></div>
         <h4 class="task-info-heading font-normal font-medium-xs"><i class="fa fa-user-o" aria-hidden="true"></i> Assignees</h4>
                  <div class="task_users_wrapper" id="staff_dis">
               <!-- <div class="task-user" data-toggle="tooltip" data-title="Cleo Cremin">
             <img width="50px" height="50px;" src="{{ asset('images/user-placeholder.jpg') }}" class="staff-profile-image-small"></a>  <a href="#" class="remove-task-user text-danger" onclick="remove_assignee(2,1); return false;">
               </div>
               <div class="task-user" data-toggle="tooltip" data-title="Frederik Rohan">
               <img width="50px" height="50px;" src="{{ asset('images/user-placeholder.jpg') }}" class="staff-profile-image-small"></a>  <a href="#" class="remove-task-user text-danger" onclick="remove_assignee(1,1); return false;">
               </div>        -->
                </div> 
         <hr class="task-info-separator">
         <div class="clearfix"></div>
         <h4 class="task-info-heading font-normal font-medium-xs">
            <i class="fa fa-user-o" aria-hidden="true"></i>
            Followers         </h4>
                  <div class="task_users_wrapper">
                <span class="task-user" id="follower_dis" data-toggle="tooltip" data-title="Frederik Rohan">
                <!-- <img width="50px" height="50px;" src="{{ asset('images/user-placeholder.jpg') }}" class="staff-profile-image-small"> -->
                </span> 
                         </div>
      </div>
</div>
  </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="replay_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Reply message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>
          <div class="form-group">
            <label for="message-text" class="col-form-label">Message:</label>
            <textarea class="form-control" id="replay_comment" name="replay_comment"></textarea>
          </div>
           <div class="form-group status-sec" style="display:none;">
              <label >Status</label>
            <div class="radio">
              <label>
                <input type="radio" name="status_replay" id="status_replay1" value="Y" >
                Approved
              </label>
            </div>
            <div class="radio">
              <label>
                <input type="radio" name="status_replay" id="status_replay1" value="R" checked>
                Reject
              </label>
            </div>
          </div>
          <input type="hidden" name="task_comment_id" id="task_comment_id"> 
          <input type="hidden" name="parent_id" id="parent_id"> 
          <input type="hidden" name="staff_id" id="staff_id" value="<?php echo session('STAFF_ID');?>"> 
          <input type="hidden" name="follower" id="follower" value=""> 
          <input type="hidden" name="follower1" id="follower1" value=""> 
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="add_replay_comment()">Update Task</button>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
     jQuery(document).ready(function() {
        var oTable = $('#cmsTable').DataTable();
        var oTable = $('.table').DataTable();
     });
function addmore_task_field(row_no)
{
  $("#more_office_task_id"+row_no).html('');
  $('#more_office_task_id'+row_no).multiselect('rebuild');
  $(".addmore_task"+row_no).show();
  $(".tra_end_sec").hide();
}
function viewednd_travel(row_no)
{
  $(".addmore_task"+row_no).hide();
  $("#time_dis_id").val("#end_time"+row_no);
  clock();
$(".tra_end_sec").show();
$(".add_start_travel").hide();
var travel_type=$("#travel_type_hidden").val();
if(travel_type=="Bike" || travel_type=="Car")
    {
      $(".carbike_sec_end"+row_no).show();
      $(".other_sec_end"+row_no).hide();
    }
    else{
      $(".other_sec_end"+row_no).show();
      $(".carbike_sec_end"+row_no).hide();
    }
} 
function addmore_task_office()
{
  var url = APP_URL+'/staff/filter_office_staff_task';
  var search_date=$("#search_date").val();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      search_date:search_date
    },
    success: function (data)
    {
      var expence = JSON.parse(data);
      if(expence.length>0)
      {
        var html='';
          for (var i = 0; i < expence.length; i++) {
            html +='<option value='+expence[i]["id"]+'>'+expence[i]["name"]+' ('+expence[i]["start_date"]+')</option>';
          }
          $("#office_task_id").html(html);
          $('#office_task_id').multiselect('rebuild');
      }
    }
  });
  $(".officestaff_tasksec").show();
  $(".end_work_display_office").hide();
}
 function submit_task_officestaff(){
   var office_task_id=$("#office_task_id").val();
   var search_date=$("#search_date").val();
   var task_name=$("#office_task_id").find('option:selected').attr("attr-name");
  if(office_task_id!='')
  {
    var url = APP_URL+'/staff/save_office_staff_task';
    $(".load_task_officestaff").show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      search_date:search_date,task_id:office_task_id,task_name:task_name
    },
    success: function (data)
    {$(".load_task_officestaff").hide();
     /* var htmls='';
  htmls +='<table class="table cmsTabletravel"> <tr>'+
                 '<th>Task</th>'+
                 '</tr>  <tbody id="leave_data">';
  htmls +='<tr id="tr_'+office_task_id+'" data-id="'+office_task_id+'" data-from ="task"><th  data-th="Task">'+
  '<a class="popup" data-id="'+office_task_id+'">'+task_name+'</a> </th></tr>';
  htmls +='</tbody></table>';*/
   var url = APP_URL+'/staff/get_office_staff_all_details';
     $(".load_leave_option").show();
     var search_date=$("#search_date").val();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      search_date:search_date
    },
    success: function (data)
    {
      var htmls="";
      var res = data.split("*");
      var levaedata = JSON.parse(res[0]);
      var attend_status=res[1];
      var htmls_data='';
      $(".load_leave_option").hide();
if(levaedata.length>0)
{
  htmls_data +='<table class="table cmsTabletravel"> <tr>'+
  '<th>Start Time</th>'+
  '<th>Task</th>'+
                 '<th>End Time</th>'+
                 '</tr>  <tbody id="leave_data">';
                 var trav_task_ids = [];
                 for (var i = 0; i < levaedata.length; i++) {
                  htmls_data +='<tr data-id="'+levaedata[i]["task_id"]+'"><td data-th="Start Time">'+levaedata[i]["start_time"]+'</td>';
if(levaedata[i]["task_name"]!=null)
{
  if(levaedata[i]["task_child_id"]!=null)
  {
    var child_task_id = levaedata[i]["task_child_id"].split(",");
    var child_task_name = levaedata[i]["task_child_name"].split(",");
    var child_task_date = levaedata[i]["task_child_date"].split(",");
  }
  else{
    var child_task_id = [];
  var child_task_name = [];
  }
  htmls_data +='<td data-th="Task">';
  //htmls_data +='<a class="popup" data-id="'+levaedata[i]["task_id"]+'">'+levaedata[i]["task_name"]+' ('+levaedata[i]["main_task_date"]+')</a>';
  if(jQuery.inArray( levaedata[i]["task_id"], trav_task_ids )=="-1")
                 {
                  htmls_data +='<a class="popup" data-id="'+levaedata[i]["task_id"]+'">'+levaedata[i]["task_name"]+'  ('+levaedata[i]["main_task_date"]+')</a>';
                 }
                 else{
                  htmls_data +=levaedata[i]["task_name"]+'  ('+levaedata[i]["main_task_date"]+')';
                 }
  if(child_task_id.length>0)
  {
  for (var k = 0; k < child_task_id.length; k++) {
    htmls_data +='<br>';
    if(child_task_date[k]!='')
    {
      var child_date='('+child_task_date[k]+')';
    }
    else{
      var child_date='';
    }
  //htmls_data +='<a class="popup" data-id="'+child_task_id[k]+'">'+child_task_name[k]+' '+child_date+'</a>';
  if(jQuery.inArray(child_task_id[k],trav_task_ids)=="-1")
                 {
                  htmls_data +='<a class="popup" data-id="'+child_task_id[k]+'">'+child_task_name[k]+' '+child_date+'</a>'; 
                  trav_task_ids.push(child_task_id[k]);
                 }
                 else{
                  htmls_data +=child_task_name[k]+' '+child_date;
  trav_task_ids.push(child_task_id[k]);             
                 }
    }
  }
  htmls_data +='</td>';
}
else{
  htmls_data +='<td data-th="Task">NA</td>';
}
if(levaedata[i]["end_time"]!=null)
{
  htmls_data +='<td data-th="End Time">'+levaedata[i]["end_time"]+'</td>';
}
else{
  htmls_data +='<td data-th="End Time"></td>';
}
  htmls_data +=' </tr>';
  trav_task_ids.push(levaedata[i]["task_id"]);
                 }
  htmls_data +='</tbody></table>';
}
$(".display_office_staff_all").html(htmls_data);
  $(".display_start_time_ofice").hide();
}
});
  $(".display_taskdetail_ofice").show();
//$(".display_taskdetail_ofice").html(htmls);
    }
  });
  $(".officestaff_tasksec").hide();
$("#office_task_id_message").hide();
$(".end_work_display_office").show();
  }
  else{
    $("#office_task_id_message").show();
  }
 }
 function submit_leaveoffice_staff()
{
  var staff_leave=$("#staff_leave_office_staff").val();
  if(staff_leave!='')
  {
    $("#staff_leave_office_staff_message").hide();
  }
  else{
    $("#staff_leave_office_staff_message").show();
  }
  if(staff_leave!='')
  {
    $(".office_staff_attendence_btn").hide();
  //
  var type_leave="Request Leave Office Staff";
  //
  var search_date=$("#search_date").val();
  var url = APP_URL+'/staff/save_staff_workleave';
  $(".load_submit_leave").show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      search_date:search_date,staff_leave:staff_leave,type_leave:type_leave
    },
    success: function (data)
    {
      $(".load_submit_leave").hide();
  var htmls='';
  htmls +='<table class="table"> <tr>'+
                 '<th>Attendance</th>'+
                 '</tr>  <tbody id="leave_data">';
  htmls +='<tr><th data-th="Attendance">'+staff_leave+'</th></tr>';
  htmls +='</tbody></table>';
  $(".leave_data_officestaff").html(htmls);
    }
  });
  $(".levedrop_secoffice_staff").hide();
  $(".leave_data_officestaff").show();
  }
}
function add_leave_office_staff()
{
$(".levedrop_secoffice_staff").show();
}
  function submit_leave_office()
{
  var staff_leave=$("#staff_leave_office").val();
  var reson_leave=$("#reson_leave").val();
  var search_date=$("#search_date").val();
  if(staff_leave!='')
  {
    $("#staff_leave_office_message").hide();
  }
  else{
    $("#staff_leave_office_message").show();
  }
  if(reson_leave!='')
  {
    $("#reson_leave_message").hide();
  }
  else{
    $("#reson_leave_message").show();
  }
  if(staff_leave!='' && reson_leave!='')
  {
    var type_leave="Request Leave";
  //
  var url = APP_URL+'/staff/save_staff_workleave';
  $(".load_leave_office").show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      search_date:search_date,staff_leave:staff_leave,reson_leave:reson_leave,type_leave:type_leave
    },
    success: function (data)
    {
      $(".load_leave_office").hide();
    }
  });
  var htmls='';
  htmls +='<table class="table"> <tr>'+
                 '<th>Leave</th>'+
                 '<th>Reason For Leave</th>'+
                 '</tr>  <tbody id="leave_data">';
  htmls +='<tr><td data-th="Leave">'+staff_leave+'</td><td data-th="Reason For Leave">'+reson_leave+'</td></tr>';
  htmls +='</tbody></table>';
  $(".levedrop_sec_office").hide();
  $(".leave_data_office").html(htmls);
  $(".leave_data_office").show();
  }
}
 function submit_office_end_work()
{
  var office_end_time=$("#office_end_time").val();
  var search_date=$("#search_date").val();
  if(office_end_time!='')
  {
    $("#office_end_time_message").hide();
     var url = APP_URL+'/staff/save_office_staff_end_time';
     $(".load_office_end_work").show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      search_date:search_date,office_end_time:office_end_time
    },
    success: function (data)
    { $(".load_office_end_work").hide();
      var htmls='';
      var htmls_data='';
      var res = data.split("*");
      var expence = JSON.parse(res[0]);
      var levaedata = JSON.parse(res[0]);
      var attend_status=res[1];
if(levaedata.length>0)
{
  htmls_data +='<table class="table cmsTabletravel"> <tr>'+
  '<th>Start Time</th>'+
  '<th>Task</th>'+
                 '<th>End Time</th>'+
                 '</tr>  <tbody id="leave_data">';
                 var trav_task_ids = [];
                 for (var i = 0; i < levaedata.length; i++) {
                  htmls_data +='<tr data-id="'+levaedata[i]["task_id"]+'"><td data-th="Start Time">'+levaedata[i]["start_time"]+'</td>';
if(levaedata[i]["task_name"]!=null)
{
  if(levaedata[i]["task_child_id"]!=null)
  {
    var child_task_id = levaedata[i]["task_child_id"].split(",");
    var child_task_name = levaedata[i]["task_child_name"].split(",");
    var child_task_date = levaedata[i]["task_child_date"].split(",");
  }
  else{
    var child_task_id = [];
  var child_task_name = [];
  }
  htmls_data +='<td data-th="Task">';
  //htmls_data +='<a class="popup" data-id="'+levaedata[i]["task_id"]+'">'+levaedata[i]["task_name"]+' ('+levaedata[i]["main_task_date"]+')</a>';
  if(jQuery.inArray( levaedata[i]["task_id"], trav_task_ids )=="-1")
                 {
                  htmls_data +='<a class="popup" data-id="'+levaedata[i]["task_id"]+'">'+levaedata[i]["task_name"]+'  ('+levaedata[i]["main_task_date"]+')</a>';
                 }
                 else{
                  htmls_data +=levaedata[i]["task_name"]+'  ('+levaedata[i]["main_task_date"]+')';
                 }
  if(child_task_id.length>0)
  {
  for (var k = 0; k < child_task_id.length; k++) {
    htmls_data +='<br>';
    if(child_task_date[k]!='')
    { 
      var child_date='('+child_task_date[k]+')';
    }
    else{
      var child_date='';
    }
  //htmls_data +='<a class="popup" data-id="'+child_task_id[k]+'">'+child_task_name[k]+' '+child_date+'</a>';
  if(jQuery.inArray( child_task_id[k], trav_task_ids )=="-1")
                 {
                  htmls_data +='<a class="popup" data-id="'+child_task_id[k]+'">'+child_task_name[k]+' '+child_date+'</a>'; 
                  trav_task_ids.push(child_task_id[k]);
                 }
                 else{
                  htmls_data +=child_task_name[k]+' '+child_date;
  trav_task_ids.push(child_task_id[k]);            
                 }
    }
  }
  htmls_data +='</td>';
}
else{
  htmls_data +='<td data-th="Task">NA</td>';
}
if(levaedata[i]["end_time"]!=null)
{
  htmls_data +='<td data-th="End Time">'+levaedata[i]["end_time"]+'</td>';
}
else{
  htmls_data +='<td data-th="End Time"></td>';
}
  htmls_data +=' </tr>';
  trav_task_ids.push(levaedata[i]["task_id"]);
                 }
  htmls_data +='</tbody></table>';
}
  $(".end_work_office").hide();
  $(".display_office_staff_all").html(htmls_data);
  $(".display_start_time_ofice").hide();
  $(".display_taskdetail_ofice").hide();
  //$(".office_staff_attendence_btn").show();
  $(".end_work_display_office").hide();
  $(".start_workbtn_office").show();
    }
  });
  }
  else{
    $("#office_end_time_message").show();
  }
}
function submit_office_start_work()
{
  var search_date=$("#search_date").val();
  var office_start_time=$("#office_start_time").val();
  if(office_start_time!='')
  {
    $(".show_add_task_sec").show();
    $("#office_start_time_message").hide();
  var url = APP_URL+'/staff/save_office_staff_start_time';
  $(".load_office_start_work").show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      search_date:search_date,office_start_time:office_start_time
    },
    success: function (data)
    {
      $("#office_task_id").val('');
      $('#office_task_id').multiselect('rebuild');
      $(".load_office_start_work").hide();
      var htmls='';
  htmls +='<table class="table"> <tr>'+
                 '<th>Start Time</th>'+
                 '</tr>  <tbody id="leave_data">';
  htmls +='<tr><th  data-th="Start Time">'+office_start_time+'</th></tr>';
  htmls +='</tbody></table>';
  $(".start_work_office").hide();
  $(".display_start_time_ofice").html(htmls);
  $(".display_start_time_ofice").show();
  $(".officestaff_tasksec").show();
$(".start_workbtn_office").hide();
    }
  });
  }
  else{
    $("#office_start_time_message").show();
  }
}
function start_work_display_office()
{
  $("#time_dis_id").val("#office_start_time");
  clock();
$(".start_work_office").show();
}
function end_work_display_office()
{
  $("#time_dis_id").val("#office_end_time");
  clock();
$(".end_work_display_office").hide();
$(".end_work_office").show();
}
function add_expence_office_staff(row_no)
{
  $(".expence_sec_office_staff"+row_no).show();
} 
function change_expence_office(row_no)
{
  $(".child_exp_office"+row_no).show();
}
function submit_leave()
{
  var staff_leave=$("#staff_leave").val();
  if(staff_leave!='')
  {
    $(".staff_leave_message").hide();
  }
  else{
    $(".staff_leave_message").show();
  }
  if(staff_leave!='')
  {
    var type_leave="Request Leave Field Staff";
  //
  var search_date=$("#search_date").val();
  var url = APP_URL+'/staff/save_staff_workleave';
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      search_date:search_date,staff_leave:staff_leave,type_leave:type_leave
    },
    success: function (data)
    {
    }
  });
  //
  var htmls='';
  htmls +='<table class="table"> <tr>'+
                 '<th>Attendance</th>'+
                 '</tr>  <tbody id="leave_data">';
  htmls +='<tr><th>'+staff_leave+'</th></tr>';
  htmls +='</tbody></table>';
  $(".levedrop_sec").hide();
  $(".leave_data").html(htmls);
  $(".leave_data").show();
  }
}
function add_leave()
{
 $(".first_leave_section").show();
}
function add_moreexpence(row_no)
{
  var expence_count=$("#expence_count").val();
  var add_count=parseInt(expence_count)+1;
  $("#expence_count").val(add_count);
  var html='';
   html +='<div class="first_expence_section" ><div class="expence_sec_all'+add_count+'">'+
  ' <div class="form-check col-sm-2">'+
  '<label  for="meter_start">Other Expence </label>'+
  '<select class="form-control other_expence"  name="other_expence[]" id="other_expence'+add_count+'" attr-id="'+add_count+'" onchange="change_expence('+add_count+')">'+
  '<option value="">Select Other Expence</option>'+
  '  <option value="Courier">Courier</option>'+
  '   <option value="Print Out">Print Out</option>'+
  '    <option value="Other">Other (with prior approval)</option>'+
  '   </select>'+
  '    <span class="error_message" id="other_expence'+add_count+'_message" style="display: none">Field is required</span>'+
  '    </div>'+
  '  <div class="form-check col-sm-2 other_sec_end'+add_count+' exp_sec'+add_count+'" style="display: none">'+
  '  <label  for="meter_start">Amount </label>'+
  '    <input class="form-check-input" type="number" name="expence_amount[]" id="expence_amount'+add_count+'" value="" placeholder="Amount">'+
  '     <span class="error_message" id="expence_amount'+add_count+'_message" style="display: none">Field is required</span>'+
  '   </div>'+
        '   <div class="form-check col-sm-2 exp_sec'+add_count+'" style="display: none">'+
  '   <label  for="meter_start">Attach photo </label>'+
  '   <input class="form-check-input"  type="file" name="expence_doc[]" id="expence_doc'+add_count+'" value="" >'+
  '  </div>'+  
  '<div class="form-check col-sm-4 other_sec_end0 exp_sec'+add_count+'" style="display: none">'+
   '               <label  for="meter_start">Description </label>'+
    '                  <textarea class="form-check-input"  name="expence_desc[]" id="expence_desc'+add_count+'"  placeholder="Description"></textarea>'+
     '                 <span class="error_message" id="expence_desc'+add_count+'_message" style="display: none">Field is required</span>'+
      '              </div>'+      
                  '<div class="form-check col-sm-2 exp_sec'+add_count+'" style="display: none">'+
                  '<label  for="meter_start">Task </label>'+
                    ' <select class="form-control"  name="expence_task_id[]" id="expence_task_id'+add_count+'">'+
                    '<option value="">Select Task</option>'+
                  ' </select>'+
                  '<span class="error_message" id="expence_task_id'+add_count+'_message" style="display: none">Field is required</span>'+
                  '</div>'+
                  '<div class="box-footer col-md-12 exp_sec'+add_count+'" style="display: none">'+
                  '<button type="button" class="btn btn-primary submit_expence"  onclick="submit_expence('+add_count+')">Submit</button>'+
                  '<div class="load_expence'+add_count+'" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>'+
                    '</div></div>';
                    get_alltask_staff('#expence_task_id'+add_count)
  $(".more_expence_display_sec").html(html);
}
function add_moreexpence_office_staff(row_no)
{
  var expence_count=$("#expence_count").val();
  var add_count=parseInt(expence_count)+1;
  $("#expence_count").val(add_count);
  var html='';
   html +='<div class="first_expence_section_office" >'+
  ' <div class="form-check col-sm-2 expence_sec_office_staff'+add_count+'">'+
  '<label  for="meter_start">Other Expence </label>'+
  '<select class="form-control other_expence"  name="other_expence_office[]" id="other_expence_office'+add_count+'" onchange="change_expence_office('+add_count+')">'+
  '<option value="">Select Other Expence</option>'+
  '  <option value="Courier">Courier</option>'+
  '   <option value="Print Out">Print Out</option>'+
  '    <option value="Other">Other (with prior approval)</option>'+
  '   </select>'+
  '    <span class="error_message" id="other_expence_office'+add_count+'_message" style="display: none">Field is required</span>'+
  '    </div>'+
  '  <div class="form-check col-sm-2 child_exp_office'+add_count+'" style="display: none">'+
  '  <label  for="meter_start">Amount </label>'+
  '    <input class="form-check-input" type="number" name="expence_amount_office[]" id="expence_amount_office'+add_count+'" value="" placeholder="Amount">'+
  '     <span class="error_message" id="expence_amount_office'+add_count+'_message" style="display: none">Field is required</span>'+
  '   </div>'+
        '   <div class="form-check col-sm-2 child_exp_office'+add_count+'" style="display: none">'+
  '   <label  for="meter_start">Attach photo </label>'+
  '   <input class="form-check-input"  type="file" name="expence_doc_office[]" id="expence_doc_office'+add_count+'" value="" >'+
  '  </div>'+  
  '<div class="form-check col-sm-4 other_sec_end0 child_exp_office'+add_count+'" style="display: none">'+
   '               <label  for="meter_start">Description </label>'+
    '                  <textarea class="form-check-input"  name="expence_desc_office[]" id="expence_desc_office'+add_count+'"  placeholder="Description"></textarea>'+
     '                 <span class="error_message" id="expence_desc_office'+add_count+'_message" style="display: none">Field is required</span>'+
      '              </div>'+      
                  '<div class="form-check col-sm-2 child_exp_office'+add_count+'" style="display: none">'+
                  '<label  for="meter_start">Task </label>'+
                    ' <select class="form-control"  name="expence_task_id_office[]" id="expence_task_id_office'+add_count+'">'+
                    '<option value="">Select Task</option>'+
                  ' </select>'+
                  '<span class="error_message" id="expence_task_id_office'+add_count+'_message" style="display: none">Field is required</span>'+
                  '</div>'+
                  '<div class="box-footer col-md-12 child_exp_office'+add_count+'" style="display: none">'+
                  '<button type="button" class="btn btn-primary submit_expence_office_staff"  onclick="submit_expence_office_staff('+add_count+')">Submit</button>'+
                  '<div class="load_expence_office_staff'+add_count+'" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>'+
                    '</div>';
                    get_alltask_staff('#expence_task_id_office'+add_count)
  $(".more_expence_display_sec_office").html(html);
}
function submit_expence_office_staff(row_no)
{
  var other_expence=$("#other_expence_office"+row_no).val();
  var expence_amount=$("#expence_amount_office"+row_no).val();
  var expence_desc=$("#expence_desc_office"+row_no).val();
  var task_id=$("#expence_task_id_office"+row_no).val();
  var task_name=$("#expence_task_id_office"+row_no).find('option:selected').attr("attr-name");
  var search_date=$("#search_date").val();
  if(other_expence!='')
  {
    $("#other_expence_office"+row_no+'_message').hide();
  }
  else{
    $("#other_expence_office"+row_no+'_message').show();
  }
    if(task_id!='')
  {
    $("#other_expence_office"+row_no+'_message').hide();
  }
  else{
    $("#other_expence_office"+row_no+'_message').show();
  }
   if(expence_amount!='')
  {
    $("#expence_amount_office"+row_no+'_message').hide();
  }
  else{
    $("#expence_amount_office"+row_no+'_message').show();
  }
if(other_expence!='' && expence_amount!='' && task_id!='')
{
  $(".expence_sec_office").hide();
  var formData = new FormData();
  var url = APP_URL+'/staff/save_staff_expence_office';
  var image_name = $('#expence_doc_office'+row_no).val();
   if(image_name != '') {    
      var file = document.getElementById('expence_doc_office'+row_no).files[0];
    //var file=$('#fair_doc'+row_no).prop('files')[0];   
      formData.append('expence_doc',file);  
      }
    formData.append('other_expence',other_expence);  
    formData.append('expence_amount',expence_amount);  
    formData.append('expence_desc',expence_desc);  
    formData.append('task_id',task_id);  
    formData.append('task_name',task_name);  
    formData.append('search_date',search_date);  
$(".load_expence_office_staff"+row_no).show();
$(".submit_expence_office_staff").attr("disabled", true);
  $.ajax({
    type: "POST",
      cache: false,
      processData: false,
      contentType: false,
        url: url,
        data:formData,
    success: function (data)
    {
      $(".submit_expence_office_staff").attr("disabled", false);
      $(".load_expence_office_staff"+row_no).hide();
  var htmls='';
 var search_date=$("#search_date").val();
  var url = APP_URL+'/staff/get_travel_expence_staff_office';
  $(".load_expence_office_staff"+row_no).show();
$.ajax({
type: "POST",
cache: false,
url: url,
data:{
sel_date:search_date
},
success: function (data)
{
$(".load_expence_office_staff"+row_no).hide();
var expence = JSON.parse(data);
if(expence.length>0)
{
 var html_expence='';
 html_expence +='<table class="table">';
 html_expence +=' <tr>'+
          '<th>Other Expence</th>'+
          '<th>Amount</th>'+
          '<th>Description</th>'+
          '<th>Task</th>'+
          '<th>Status</th>'+
          '<th>Edit</th>'+
          '<th>Image</th>'+
          '</tr>  <tbody id="expence_data">';
 for (var i = 0; i < expence.length; i++) {
   html_expence +=' <tr>'+
          '<td data-th="Other Expence">'+expence[i]["travel_type"]+'</td>'+
          '<td data-th="Amount">'+expence[i]["travel_start_amount"]+'</td>'+
          '<td data-th="Description">'+expence[i]["expence_desc"]+'</td>'+
          '<td data-th="Task">'+expence[i]["task_name"]+'</td>';
          if(expence[i]["status"]=="Reject")
          {
           html_expence +='<td  data-th="Status" style="color:red">Reject</td>';
          } 
          if(expence[i]["status"]=="Y")
          {
           html_expence +='<td  data-th="Status" style="color:green">Approved</td>';
          } 
          if(expence[i]["status"]=="N")
          {
           html_expence +='<td  data-th="Status" style="color:red">Pending</td>';
          } 
         var type_expence="office";
          if(expence[i]["status"]=="Reject")
          {
           html_expence +='<td  data-th="Edit" style="color:green"><a onclick="edit_expence('+expence[i]["id"]+',\'' + expence[i]["travel_type"] + '\',\'' + expence[i]["travel_start_amount"] + '\',\'' + expence[i]["expence_desc"] + '\',\'' + expence[i]["travel_task_id"] + '\',\'' + office + '\')">Edit</a></td>';
          }
          else{
           html_expence +='<td  data-th="Edit" style="color:green"></td>';
          }
          if(expence[i]["travel_start_image"]!='' && expence[i]["travel_start_image"]!=null)
           {
             var tr_imgs_start="{{asset('public/storage/comment/')}}/"+expence[i]["travel_start_image"];
             html_expence +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
           }
           else{
             html_expence +='<td></td>';
           }
         html_expence += '</tr>';
         if(expence[i]["status"]=="N" || expence[i]["status"]=="Reject")
          {
           error_flag=1;
          }
 }
 html_expence +='</tbody></table>';
}
  $(".expence_results_office_sec").html(html_expence);
  $(".more_expence_btn_off_staff ").show();
$(".add_expence").hide();
$(".expence_sec_office_staff"+row_no).hide();
$(".child_exp_office"+row_no).hide();
}
});
    }
  });
}
}
function submit_expence(row_no)
{
  var other_expence=$("#other_expence"+row_no).val();
  var expence_amount=$("#expence_amount"+row_no).val();
  var expence_desc=$("#expence_desc"+row_no).val();
  var task_id=$("#expence_task_id"+row_no).val();
  var task_name=$("#expence_task_id"+row_no).find('option:selected').attr("attr-name");
  var search_date=$("#search_date").val();
  if(other_expence!='')
  {
    $("#other_expence"+row_no+'_message').hide();
  }
  else{
    $("#other_expence"+row_no+'_message').show();
  }
    if(task_id!='')
  {
    $("#expence_task_id"+row_no+'_message').hide();
  }
  else{
    $("#expence_task_id"+row_no+'_message').show();
  }
   if(expence_amount!='')
  {
    $("#expence_amount"+row_no+'_message').hide();
  }
  else{
    $("#expence_amount"+row_no+'_message').show();
  }
if(other_expence!='' && expence_amount!='' && task_id!='')
{
  var formData = new FormData();
  var url = APP_URL+'/staff/save_staff_expence';
  var image_name = $('#expence_doc'+row_no).val();
   if(image_name != '') {    
      var file = document.getElementById('expence_doc'+row_no).files[0];
    //var file=$('#fair_doc'+row_no).prop('files')[0];   
      formData.append('expence_doc',file);  
      }
    formData.append('other_expence',other_expence);  
    formData.append('expence_amount',expence_amount);  
    formData.append('expence_desc',expence_desc);  
    formData.append('task_id',task_id);  
    formData.append('task_name',task_name);  
    formData.append('search_date',search_date);  
    $(".load_expence"+row_no).show();
    $(".submit_expence").attr("disabled", true);
  $.ajax({
    type: "POST",
      cache: false,
      processData: false,
      contentType: false,
        url: url,
        data:formData,
    success: function (data)
    {
      $(".load_expence"+row_no).hide();
      $(".submit_expence").attr("disabled", false);
  var htmls='';
  var search_date=$("#search_date").val();
   var url = APP_URL+'/staff/get_travel_expence_staff';
   $(".load_expence"+row_no).show();
$.ajax({
type: "POST",
cache: false,
url: url,
data:{
sel_date:search_date
},
success: function (data)
{ $(".load_expence"+row_no).hide();
var res = data.split("*");
var travel = JSON.parse(res[0]);
var expence = JSON.parse(res[1]);
var attend_status=res[2];
if(attend_status==1)
      {
      $(".addleave_btsecfirst").hide();   
      }
      else{
      $(".addleave_btsecfirst").show();   
      }
if(expence.length>0)
{
  var html_expence='';
  html_expence +='<table class="table">';
  html_expence +=' <tr>'+
           '<th>Other Expence</th>'+
           '<th>Amount</th>'+
           '<th>Description</th>'+
           '<th>Task</th>'+
           '<th>Status</th>'+
           '<th>Edit</th>'+
           '<th>Image</th>'+
           '</tr>  <tbody id="expence_data">';
  for (var i = 0; i < expence.length; i++) {
    html_expence +=' <tr>'+
           '<td data-th="Other Expence">'+expence[i]["travel_type"]+'</td>'+
           '<td data-th="Amount">'+expence[i]["travel_start_amount"]+'</td>'+
           '<td data-th="Description">'+expence[i]["expence_desc"]+'</td>'+
           '<td data-th="Task">'+expence[i]["task_name"]+'</td>';
           if(expence[i]["status"]=="Reject")
           {
            html_expence +='<td  data-th="Status" style="color:red">Reject</td>';
           } 
           if(expence[i]["status"]=="Y")
           {
            html_expence +='<td  data-th="Status" style="color:green">Approved</td>';
           } 
           if(expence[i]["status"]=="N")
           {
            html_expence +='<td  data-th="Status" style="color:red">Pending</td>';
           } 
           var type_expence="field";
           if(expence[i]["status"]=="Reject")
           {
            html_expence +='<td  data-th="Edit" style="color:green"><a onclick="edit_expence('+expence[i]["id"]+',\'' + expence[i]["travel_type"] + '\',\'' + expence[i]["travel_start_amount"] + '\',\'' + expence[i]["expence_desc"] + '\',\'' + expence[i]["travel_task_id"] + '\',\'' + type_expence + '\')">Edit</a></td>';
           }
           else{
            html_expence +='<td  data-th="Edit" style="color:green"></td>';
           }
            if(expence[i]["travel_start_image"]!='' && expence[i]["travel_start_image"]!=null)
                  {
                    var tr_imgs_start="{{asset('public/storage/comment/')}}/"+expence[i]["travel_start_image"];
 html_expence +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    html_expence +='<td></td>';
                  }
          html_expence += '</tr>';
          if(expence[i]["status"]=="N" || expence[i]["status"]=="Reject")
           {
            error_flag=1;
           }
  }
  html_expence +='</tbody></table>';
}
   $(".expence_results").html(html_expence);
   $(".more_expence_display_sec").show();
$(".more_expence_btn").show();
$(".add_expence").hide();
$(".expence_sec_all"+row_no).hide();
}
});
    }
  });
}
}
function update_edit_expence()
{
  var expence_id=$("#expence_id").val();
  var other_expence_edit=$("#other_expence_edit").val();
  var expence_amount_edit=$("#expence_amount_edit").val();
  var expence_desc_edit=$("#expence_desc_edit").val();
  var type_expence=$("#type_expence").val();
  //alert(expence_type)
  var error_flag=0;
  var url = APP_URL+'/staff/save_expence_edit_details';
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      expence_id:expence_id,other_expence_edit:other_expence_edit,expence_amount_edit:expence_amount_edit,expence_desc_edit:expence_desc_edit
    },
    success: function (data)
    {
         var search_date=$("#search_date").val();
if(type_expence=="field")
{
/********* */
  var url = APP_URL+'/staff/get_travel_expence_staff';
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:search_date
    },
    success: function (data)
    {
      var res = data.split("*");
      var travel = JSON.parse(res[0]);
      var expence = JSON.parse(res[1]);
      var attend_status=res[2];
      if(attend_status==1)
            {
            $(".addleave_btsecfirst").hide();   
            }
            else{
            $(".addleave_btsecfirst").show();   
            }
      if(expence.length>0)
      {
        var html_expence='';
        html_expence +='<table class="table">';
        html_expence +=' <tr>'+
                 '<th>Other Expence</th>'+
                 '<th>Amount</th>'+
                 '<th>Description</th>'+
                 '<th>Task</th>'+
                 '<th>Status</th>'+
                 '<th>Edit</th>'+
                 '<th>Image</th>'+
                 '</tr>  <tbody id="expence_data">';
        for (var i = 0; i < expence.length; i++) {
          html_expence +=' <tr>'+
                 '<td data-th="Other Expence">'+expence[i]["travel_type"]+'</td>'+
                 '<td data-th="Amount">'+expence[i]["travel_start_amount"]+'</td>'+
                 '<td data-th="Description">'+expence[i]["expence_desc"]+'</td>'+
                 '<td data-th="Task">'+expence[i]["task_name"]+'</td>';
                 if(expence[i]["status"]=="Reject")
                 {
                  html_expence +='<td  data-th="Status" style="color:red">Reject</td>';
                 } 
                 if(expence[i]["status"]=="Y")
                 {
                  html_expence +='<td  data-th="Status" style="color:green">Approved</td>';
                 } 
                 if(expence[i]["status"]=="N")
                 {
                  html_expence +='<td  data-th="Status" style="color:red">Pending</td>';
                 } 
                 var type_expence="field";
                 if(expence[i]["status"]=="Reject")
                 {
                  html_expence +='<td  data-th="Edit" style="color:green"><a onclick="edit_expence('+expence[i]["id"]+',\'' + expence[i]["travel_type"] + '\',\'' + expence[i]["travel_start_amount"] + '\',\'' + expence[i]["expence_desc"] + '\',\'' + expence[i]["travel_task_id"] + '\',\'' + type_expence + '\')">Edit</a></td>';
                 }
                 else{
                  html_expence +='<td  data-th="Edit" style="color:green"></td>';
                 }
                  if(expence[i]["travel_start_image"]!='' && expence[i]["travel_start_image"]!=null)
                  {
                    var tr_imgs_start="{{asset('public/storage/comment/')}}/"+expence[i]["travel_start_image"];
 html_expence +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    html_expence +='<td></td>';
                  }
                html_expence += '</tr>';
               /* if(expence[i]["status"]=="N" || expence[i]["status"]=="Reject")
                 {
                  error_flag=1;
                 }
                */
        }
       /* if(error_flag==1)
        {
          $(".addleave_btsecfirst").hide();
        }
        else{
          $(".addleave_btsecfirst").show();
        }*/
        html_expence +='</tbody></table>';
      }
      $("#replay_modal_expence").modal("hide");
         $(".expence_results").html(html_expence);
    }
  });
/********* */
}
else{
  /********* */
  var url = APP_URL+'/staff/get_travel_expence_staff_office';
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:search_date
    },
    success: function (data)
    {
      var expence = JSON.parse(data);
var flag_office_staff=0;
      if(expence.length>0)
      {
        var html_expence='';
        html_expence +='<table class="table">';
        html_expence +=' <tr>'+
                 '<th>Other Expence</th>'+
                 '<th>Amount</th>'+
                 '<th>Description</th>'+
                 '<th>Task</th>'+
                 '<th>Status</th>'+
                 '<th>Edit</th>'+
                 '<th>Image</th>'+
                 '</tr>  <tbody id="expence_data">';
        for (var i = 0; i < expence.length; i++) {
        /*  if(expence[i]["status"]=="N" || expence[i]["travel_type"]=="Reject"){
            $(".office_staff_attendence_btn").hide();
          }
          else{
            $(".office_staff_attendence_btn").show();
          }*/
          html_expence +=' <tr>'+
                 '<td data-th="Other Expence">'+expence[i]["travel_type"]+'</td>'+
                 '<td data-th="Amount">'+expence[i]["travel_start_amount"]+'</td>'+
                 '<td data-th="Description">'+expence[i]["expence_desc"]+'</td>'+
                 '<td data-th="Task">'+expence[i]["task_name"]+'</td>';
                 if(expence[i]["status"]=="Reject")
                 {
                  html_expence +='<td  data-th="Status" style="color:red">Reject</td>';
                 } 
                 if(expence[i]["status"]=="Y")
                 {
                  html_expence +='<td  data-th="Status" style="color:green">Approved</td>';
                 } 
                 if(expence[i]["status"]=="N")
                 {
                  html_expence +='<td  data-th="Status" style="color:red">Pending</td>';
                 } 
                 var type_expence="office";
                 if(expence[i]["status"]=="Reject")
                 {
                  html_expence +='<td  data-th="Edit" style="color:green"><a onclick="edit_expence('+expence[i]["id"]+',\'' + expence[i]["travel_type"] + '\',\'' + expence[i]["travel_start_amount"] + '\',\'' + expence[i]["expence_desc"] + '\',\'' + expence[i]["travel_task_id"] + '\',\'' + expence[i]["type_expence"] + '\')">Edit</a></td>';
                 }
                 else{
                  html_expence +='<td  data-th="Edit" style="color:green"></td>';
                 }
                  if(expence[i]["travel_start_image"]!='' && expence[i]["travel_start_image"]!=null)
                  {
                    var tr_imgs_start="{{asset('public/storage/comment/')}}/"+expence[i]["travel_start_image"];
 html_expence +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    html_expence +='<td></td>';
                  }
                html_expence += '</tr>';
                if(expence[i]["status"]=="N" || expence[i]["status"]=="Reject")
                 {
                  error_flag=1;
                 }
        }
     /*   if(error_flag==1)
        {
          $(".office_staff_attendence_btn").hide();
        }
        else{
          $(".office_staff_attendence_btn").show();
        }*/
        html_expence +='</tbody></table>';
      }
         $(".expence_results_office_sec").html(html_expence);
        // $(".more_expence_btn_off_staff ").show();
    $(".add_expence").show();
    $(".expence_sec_office_staff"+row_no).hide();
    $(".child_exp_office").hide();
    }
  });
/********* */
}
    }
  });
}
function edit_expence(id,expence_type,amount,desc,task_id,type_expence)
{
  //alert(id)
  $("#replay_modal_expence").modal("show");
  $("#expence_id").val(id);
  $("#other_expence_edit").val(expence_type);
  $("#expence_amount_edit").val(amount);
  $("#expence_desc_edit").val(desc);
  $("#type_expence").val(type_expence);
}
function change_travel(travel_type,row_no)
{
  $("#time_dis_id").val("#start_time"+row_no);
  clock();
  $("#travel_type_hidden").val(travel_type);
  //var row_no=$(this).attr('attr-id');
if(travel_type=="Bike" || travel_type=="Car")
{
  $(".bike_train_sec"+row_no).show();
$(".carbike_sec"+row_no).show();
$(".other_sec"+row_no).hide();
}
else{$(".bike_train_sec"+row_no).show();
  $(".carbike_sec"+row_no).hide();
$(".other_sec"+row_no).show();
}
}
// function change_expence(row_no)
// {
//   $(".exp_sec"+row_no).show();
// }
$(document).on('change', '.other_expence', function() { 
  var row_no=$(this).attr('attr-id');
    $(".exp_sec"+row_no).show();
});
$(document).ready(function() {
 // $('input.other_expence').on("change click", function() {
   /* $(".other_expence").change(function(){
    var row_no=$(this).attr('attr-id');
    $(".exp_sec"+row_no).show();
  });*/
  $('input.travel_type').on("change click", function() {
var travel_type=$(this).val();
var row_no=$(this).attr('attr-id');
if(travel_type=="Bike" || travel_type=="Car")
{
$(".carbike_sec"+row_no).show();
$(".other_sec"+row_no).hide();
}
else{
  $(".carbike_sec"+row_no).hide();
$(".other_sec"+row_no).show();
}
 });
});
function add_expence()
{
  get_alltask_staff('#expence_task_id0')
  $(".first_expence_section").show();
}
function travel_first()
{
  $(".travel_section").show();
  $(".travtype_sec").show();
}
function add_start_travel(row_no)
{
var travel_type=$("#travel_type"+row_no).val();
var meter_start=$("#meter_start"+row_no).val();
var amount=$("#amount_start"+row_no).val();
var start_time=$("#start_time"+row_no).val();
var user_id=$("#user_id"+row_no).val();
//var task_id=$("#task_id_hidden").val();
var task_id=$("#travel_task_id"+row_no).val();
var search_date=$("#search_date").val();
var task_name=$("#travel_task_id"+row_no).find('option:selected').attr("attr-name");
var flag=0;
if(task_id!='')
    {
      $("#travel_task_id"+row_no+'_message').hide();
    }
    else{
      $("#travel_task_id"+row_no+'_message').show();
    }
if(travel_type=="Bike" || travel_type=="Car")
  {
    if(meter_start=="")
    {flag=1;
      $("#meter_start"+row_no+'_message').show();
    }
    else{flag=0;
    $("#travel_start_read_hidden").val(meter_start);
      $("#meter_start"+row_no+'_message').hide();
    }
  }
  else{
    if(amount=="")
    {flag=1;
      $("#amount_start"+row_no+'_message').show();
    }
    else{flag=0;
      $("#amount_start"+row_no+'_message').hide();
    }
  }
  if(flag==0 && task_id!=''){
    var url = APP_URL+'/staff/save_start_travel';
    var formData = new FormData();
    var image_name = $('#fair_doc'+row_no).val();
    if(image_name != '') {    
    //  var file = document.getElementById('fair_doc'+row_no).files[0];
      formData.append('fair_doc', $('#fair_doc'+row_no)[0].files[0]); 
    //var file=$('#fair_doc'+row_no).prop('files')[0];   
     // formData.append('fair_doc',file);  
      }
      if(travel_type=="Bike" || travel_type=="Car")
      {amount=0;
      }
      else{
        meter_start=0;
      }
    formData.append('travel_type',travel_type);  
    formData.append('meter_start',meter_start);  
    formData.append('amount_start',amount);  
    formData.append('task_id',task_id);  
    formData.append('search_date',search_date);  
    formData.append('task_name',task_name);  
    formData.append('start_time',start_time); 
    formData.append('user_id',user_id); 
    formData.append('travel_sec','first'); 
$(".load_travel"+row_no).show();
  $.ajax({
    type: "POST",
      cache: false,
      processData: false,
      contentType: false,
    //  dataType:'JSON',
        url: url,
       // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data:formData,
    success: function (data)
    {
      $(".load_travel"+row_no).hide();
  console.log('count'+row_no);
$(".travel_end_sec"+row_no).show();
$(".other_sec_end"+row_no).hide();
$(".carbike_sec_end"+row_no).hide();
$(".tra_end_sec").hide();
// $("#travel_type_hidden").val(levaedata[0]["travel_type"]);
 // $("#task_id_hidden").val(levaedata[0]["travel_task_id"]);
  $(".travel_section").show();
  $(".travel_end_sec"+row_no).show();
  $(".carbike_sec"+row_no).hide();
  $(".bike_train_sec"+row_no).hide();
  $(".travtype_sec").hide();
  $(".travel_first").hide();
   var change_date=$("#search_date").val();
var url = APP_URL+'/staff/get_travel_expence_staff';
// $(".load_travel_end"+row_no).show();
$.ajax({
type: "POST",
cache: false,
url: url,
data:{
  sel_date:change_date
},
success: function (data)
{$(".load_travel_end"+row_no).hide();
  var res = data.split("*");
  var travel = JSON.parse(res[0]);
  var expence = JSON.parse(res[1]);
  var error_flag=0;
  if(travel.length>0)
  {
    var htmls='';
    htmls +='<table class="table cmsTabletravel" >';
    htmls +=' <tr>'+
             '<th>Travel Type</th>'+
             '<th>Start Reading</th>'+
             '<th>End Reading</th>'+
             '<th>kilometers</th>'+
             '<th>Amount</th>'+
             '<th>Start Time</th>'+
             '<th>End Time</th>'+
             '<th>Task</th>'+
             '<th>Start Image</th>'+
  '<th>End Image</th>'+
             '</tr>  <tbody id="travel_data">';
             var trav_task_ids = [];
             for (var i = 0; i < travel.length; i++) {
              var total_meter=0;
              if(travel[i]["travel_type"]=="Bike")
                {
                  var bike_rate=2.5;
                  var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                  var tot_price=total_meter*2.5;
                }
                if(travel[i]["travel_type"]=="Car")
                {
                  var car_rate=5;
                  var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                  var tot_price=total_meter*5;  
                }
                if(jQuery.inArray( travel[i]["travel_task_id"], trav_task_ids )=="-1")
             {  
              htmls +=' <tr data-id="'+travel[i]["travel_task_id"]+'">';
             }else{
              htmls +=' <tr >';
             }
              htmls +='<td data-th="Travel Type">'+travel[i]["travel_type"]+'</td>';
             if(travel[i]["start_meter_reading"]==null)
             {
              htmls +='<td data-th="Start Reading">NA</td>';
             }
             else{
              htmls +='<td data-th="Start Reading">'+travel[i]["start_meter_reading"]+'</td>';
             }
             if(travel[i]["end_meter_reading"]!=null)
             {
              htmls +='<td data-th="End Reading">'+travel[i]["end_meter_reading"]+'</td>';
             }
             else{
              htmls +='<td data-th="End Reading">NA</td>';
             }
             if(total_meter>0)
             {
              htmls +='<td data-th="kilometers">'+total_meter+'</td>';
             }
             else{
              htmls +='<td data-th="kilometers">NA</td>';
             }
             if(travel[i]["travel_type"]=="Car"  ||  travel[i]["travel_type"]=="Bike")
             {
               if(tot_price>0){
                htmls +='<td data-th="Amount">'+tot_price+'</td>';
               }
               else{
                htmls +='<td data-th="Amount">NA</td>';
               }
             }
             else{
               if(travel[i]["travel_end_amount"]>0){
                htmls +='<td data-th="Amount">'+travel[i]["travel_end_amount"]+'</td>';
               }else{
                htmls +='<td data-th="Amount">NA</td>';
               }
             }
       htmls += '<td data-th="Start Time">'+travel[i]["start_time_travel"]+'</td>';
      if(travel[i]["end_time_travel"]!=null)
      {
        htmls +='<td data-th="End Time">'+travel[i]["end_time_travel"]+'</td>';
      }
      else{
        htmls +='<td data-th="End Time"></td>';
      }
       //console.log(trav_task_ids+'--'+trav_task_ids.indexOf(travel[i]["travel_task_id"]));
  if(travel[i]["travel_task_child_id"]!=null)
  {
    var child_task = travel[i]["travel_task_child_id"].split(",");
    var child_task_name = travel[i]["travel_task_child_name"].split(",");
    //var child_task_date = travel[i]["travel_task_child_date"].split(",");
    if(travel[i]["travel_task_child_date"]!=null)
    {
      var child_task_date = travel[i]["travel_task_child_date"].split(",");
    }
    else{
      var child_task_date = [];
    }
  }
  else{
    var child_task=[];
  }
                  if(jQuery.inArray( travel[i]["travel_task_id"], trav_task_ids )=="-1")
                 {
                  htmls +=  '<td data-th="Task" >';
                  if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447")
                  {
                    htmls +=travel[i]["task_name"];
                  }
                  else{
                    htmls +='<a class="popup" data-id="'+travel[i]["travel_task_id"]+'">'+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')</a>';
                  }
                  if(child_task.length>0)
                  {
                  for (var k = 0; k < child_task.length; k++) {
                    htmls +=  '<br>';
                    if(jQuery.inArray( child_task[k], trav_task_ids )=="-1")
                    {
                      if(child_task_name[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +='<a class="popup" data-id="'+child_task[k]+'">'+child_task_name[k]+' ('+child_task_date[k]+')</a>';
                        }
                      }
                    }
                    else{
                      if(child_task_date[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +=child_task_name[k] +'('+child_task_date[k]+')';
                        }
                      }
                    }
                    trav_task_ids.push(child_task[k]);
                  } 
                 }
                  htmls +='</td>';
                 }
                 else{
                //  htmls +=  '<td data-th="Task" >'+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')</td>';
                   htmls +=  '<td data-th="Task" >';
                  htmls +=''+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')';
                  if(child_task.length>0)
                  {
                  for (var k = 0; k < child_task.length; k++) {
                    htmls +=  '<br>';
                    if(jQuery.inArray( child_task[k], trav_task_ids )=="-1")
                    {
                      if(child_task_name[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +='<a class="popup" data-id="'+child_task[k]+'">'+child_task_name[k]+' ('+child_task_date[k]+')</a>';
                        }
                      }
                    }
                    else{
                      if(child_task_date[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +=child_task_name[k] +'('+child_task_date[k]+')';
                        }
                      }
                    }
                    trav_task_ids.push(child_task[k]);
                  } 
                 }
                  htmls +='</td>';
                  }
       if(travel[i]["travel_start_image"]!='' && travel[i]["travel_start_image"]!=null)
                  {
                    var tr_imgs_start="{{asset('public/storage/comment/')}}/"+travel[i]["travel_start_image"];
 htmls +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    htmls +='<td></td>';
                  }
                  if(travel[i]["travel_end_image"]!='' && travel[i]["travel_end_image"]!=null)
                  {
                    var tr_imgs_end="{{asset('public/storage/comment/')}}/"+travel[i]["travel_end_image"];
 htmls +='<td><a href="'+tr_imgs_end+'" download="'+tr_imgs_end+'"><img src="'+tr_imgs_end+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    htmls +='<td></td>';
                  }
             htmls +='</tr>';
             trav_task_ids.push(travel[i]["travel_task_id"]);
             }
             htmls +='</tbody></table>';
  }
  //$(".travel_sec"+row_no).hide();
             $(".display_add_data").html(htmls);
}
});
    }
  });
  }
}
function add_end_travel(row_no)
{
  var travel_type=$("#travel_type_hidden").val();
  var meter_end=$("#meter_end"+row_no).val();
var amount=$("#amount_end"+row_no).val();
var flag=0;
if(travel_type=="Bike" || travel_type=="Car")
  {
    if(meter_end=="")
    {flag=1;
      $("#meter_end"+row_no+'_message').show();
    }
    else{
    var start_read=$("#travel_start_read_hidden").val();
    if(parseInt(start_read)>=parseInt(meter_end))
    {
      flag=1;
      $("#meter_end_error"+row_no+'_message').show();
      $("#meter_end"+row_no+'_message').hide();
    }
    else{
      flag=0;
      $("#meter_end_error"+row_no+'_message').hide();
      $("#meter_end"+row_no+'_message').hide();
     }
    }
  }
  else{
    if(amount=="")
    {flag=1;
      $("#amount_end"+row_no+'_message').show();
    }
    else{flag=0;
      $("#amount_end"+row_no+'_message').hide();
    }
  }
  if(flag==0){
   var start_time=$("#start_time"+row_no).val();
    var end_time=$("#end_time"+row_no).val();
    var meter_start=$("#meter_start"+row_no).val();
    var meter_end=$("#meter_end"+row_no).val();
    //var amount_start=$("#amount_start"+row_no).val();
    var amount_end=$("#amount_end"+row_no).val();
    var search_date=$("#search_date").val();
    //var task_id=$("#travel_task_id"+row_no).val();
    var task_id=$("#task_id_hidden").val();
    var task_name=$("#travel_task_id"+row_no).find('option:selected').attr("attr-name");
      var url = APP_URL+'/staff/save_start_travel';
    var formData = new FormData();
    var image_name = $('#fair_doc_end'+row_no).val();
    if(image_name != '') { 
      formData.append('fair_doc_end', $('#fair_doc_end'+row_no)[0].files[0]);    
     // var file = document.getElementById('fair_doc_end'+row_no).files[0];
    //var file=$('#fair_doc'+row_no).prop('files')[0];   
     // formData.append('fair_doc_end',file);  
      }
      if(travel_type=="Bike" || travel_type=="Car")
      {amount=0;
      }
      else{
        meter_start=0;
      }
    if(amount_end>0)
    {
      amount_end=amount_end;
    }  
    else{
      amount_end=0;
    }
    formData.append('end_time',end_time);  
    formData.append('travel_sec','second'); 
    formData.append('meter_end',meter_end);  
    formData.append('amount_end',amount_end);  
    formData.append('task_id',task_id);  
    formData.append('search_date',search_date);  
$(".load_travel_end"+row_no).show();
  $.ajax({
    type: "POST",
      cache: false,
      processData: false,
      contentType: false,
        url: url,
        data:formData,
    success: function (data)
    {
      $(".load_travel_end"+row_no).hide();
      $(".travel_first").hide();
    $(".add_more_travel").show();
    var change_date=$("#search_date").val();
    var url = APP_URL+'/staff/get_travel_expence_staff';
    $(".load_travel_end"+row_no).show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:change_date
    },
    success: function (data)
    {
      $(".load_travel_end"+row_no).hide();
      var res = data.split("*");
      var travel = JSON.parse(res[0]);
      var expence = JSON.parse(res[1]);
      var error_flag=0;
      if(travel.length>0)
      {
        var htmls='';
        htmls +='<table class="table cmsTabletravel" >';
        htmls +=' <tr>'+
                 '<th>Travel Type</th>'+
                 '<th>Start Reading</th>'+
                 '<th>End Reading</th>'+
                 '<th>kilometers</th>'+
                 '<th>Amount</th>'+
                 '<th>Start Time</th>'+
                 '<th>End Time</th>'+
                 '<th>Task</th>'+
                 '<th>Start Image</th>'+
  '<th>End Image</th>'+
                 '</tr>  <tbody id="travel_data">';
                 var trav_task_ids = [];
                 for (var i = 0; i < travel.length; i++) {
                  var total_meter=0;
                  if(travel[i]["travel_type"]=="Bike")
                    {
                      var bike_rate=2.5;
                      var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                      var tot_price=total_meter*2.5;
                    }
                    if(travel[i]["travel_type"]=="Car")
                    {
                      var car_rate=5;
                      var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                      var tot_price=total_meter*5;  
                    }
                    if(jQuery.inArray(travel[i]["travel_task_id"], trav_task_ids )=="-1")
                 {  
                  htmls +=' <tr data-id="'+travel[i]["travel_task_id"]+'">';
                 }else{
                  htmls +=' <tr >';
                 }
                  htmls +='<td data-th="Travel Type">'+travel[i]["travel_type"]+'</td>';
                  if(travel[i]["start_meter_reading"]==null)
                 {
                  htmls +='<td data-th="Start Reading">NA</td>';
                 }
                 else{
                  htmls +='<td data-th="Start Reading">'+travel[i]["start_meter_reading"]+'</td>';
                 }
                 if(travel[i]["end_meter_reading"]!=null)
                 {
                  htmls +='<td data-th="End Reading">'+travel[i]["end_meter_reading"]+'</td>';
                 }
                 else{
                  htmls +='<td data-th="End Reading">NA</td>';
                 }
                 if(total_meter>0)
                 {
                  htmls +='<td data-th="kilometers">'+total_meter+'</td>';
                 }
                 else{
                  htmls +='<td data-th="kilometers">NA</td>';
                 }
                 if(travel[i]["travel_type"]=="Car"  ||  travel[i]["travel_type"]=="Bike")
                 {
                   if(tot_price>0){
                    htmls +='<td data-th="Amount">'+tot_price+'</td>';
                   }
                   else{
                    htmls +='<td data-th="Amount">NA</td>';
                   }
                 }
                 else{
                   if(travel[i]["travel_end_amount"]>0){
                    htmls +='<td data-th="Amount">'+travel[i]["travel_end_amount"]+'</td>';
                   }else{
                    htmls +='<td data-th="Amount">NA</td>';
                   }
                 }
           htmls += '<td data-th="Start Time">'+travel[i]["start_time_travel"]+'</td>';
          if(travel[i]["end_time_travel"]!=null)
          {
            htmls +='<td data-th="End Time">'+travel[i]["end_time_travel"]+'</td>';
          }
          else{
            htmls +='<td data-th="End Time"></td>';
          }
  //console.log(trav_task_ids+'--'+trav_task_ids.indexOf(travel[i]["travel_task_id"]));
       //console.log(trav_task_ids+'--'+trav_task_ids.indexOf(travel[i]["travel_task_id"]));
  if(travel[i]["travel_task_child_id"]!=null)
  {
    var child_task = travel[i]["travel_task_child_id"].split(",");
    var child_task_name = travel[i]["travel_task_child_name"].split(",");
   // var child_task_date = travel[i]["travel_task_child_date"].split(",");
    if(travel[i]["travel_task_child_date"]!=null)
    {
      var child_task_date = travel[i]["travel_task_child_date"].split(",");
    }
    else{
      var child_task_date = [];
    }
  }
  else{
    var child_task=[];
  }
                  if(jQuery.inArray( travel[i]["travel_task_id"], trav_task_ids )=="-1")
                 {
                  htmls +=  '<td data-th="Task" >';
                  if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447")
                        {
                          htmls +=travel[i]["task_name"];
                        }
                        else{
                          htmls +='<a class="popup" data-id="'+travel[i]["travel_task_id"]+'">'+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')</a>';
                        }
                  if(child_task.length>0)
                  {
                  for (var k = 0; k < child_task.length; k++) {
                    htmls +=  '<br>';
                    if(jQuery.inArray( child_task[k], trav_task_ids )=="-1")
                    {
                      if(child_task_name[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447")
                        {
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +='<a class="popup" data-id="'+child_task[k]+'">'+child_task_name[k]+' ('+child_task_date[k]+')</a>';
                        }
                      }
                    }
                    else{
                      if(child_task_date[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +=child_task_name[k] +'('+child_task_date[k]+')';
                        }
                      }
                    }
                    trav_task_ids.push(child_task[k]);
                  } 
                 }
                  htmls +='</td>';
                 }
                 else{
                //  htmls +=  '<td data-th="Task" >'+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')</td>';
                   htmls +=  '<td data-th="Task" >';
                  htmls +=''+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')';
                  if(child_task.length>0)
                  {
                  for (var k = 0; k < child_task.length; k++) {
                    htmls +=  '<br>';
                    if(jQuery.inArray( child_task[k], trav_task_ids )=="-1")
                    {
                      if(child_task_name[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +='<a class="popup" data-id="'+child_task[k]+'">'+child_task_name[k]+' ('+child_task_date[k]+')</a>';
                        }
                      }
                    }
                    else{
                      if(child_task_date[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +=child_task_name[k] +'('+child_task_date[k]+')';
                        }
                      }
                    }
                    trav_task_ids.push(child_task[k]);
                  } 
                 }
                  htmls +='</td>';
                  }
                  if(travel[i]["travel_start_image"]!='' && travel[i]["travel_start_image"]!=null)
                  {
                    var tr_imgs_start="{{asset('public/storage/comment/')}}/"+travel[i]["travel_start_image"];
 htmls +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    htmls +='<td></td>';
                  }
                  if(travel[i]["travel_end_image"]!='' && travel[i]["travel_end_image"]!=null)
                  {
                    var tr_imgs_end="{{asset('public/storage/comment/')}}/"+travel[i]["travel_end_image"];
 htmls +='<td><a href="'+tr_imgs_end+'" download="'+tr_imgs_end+'"><img src="'+tr_imgs_end+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    htmls +='<td></td>';
                  }
                 htmls +='</tr>';
               /* if(travel[i]["status"]=="N" || travel[i]["status"]=="Reject")
                 {
                  error_flag=1;
                 }*/
                 trav_task_ids.push(travel[i]["travel_task_id"]);
                 }
                 htmls +='</tbody></table>';
      }
      $(".travel_sec"+row_no).hide();
                 $(".display_add_data").html(htmls);
    }
  });
    }
  });
  }
}
function change_task(task_id)
{
$("#task_id_hidden").val(task_id);
}
function add_more_travel()
{
  $(".add_more_travel").hide();
  var travel_count=$("#travel_count").val();
  var add_count=parseInt(travel_count)+1;
  $("#travel_count").val(add_count);
  var html='';
  html +='<div class="travel_sec'+add_count+'"> <div class="form-check col-sm-12"><label class="travtype_sec">Start Travel</label></div>';
           html +='<div class="form-group col-md-12">'+
           '<div class="form-check col-sm-2 travtype_sec">'+
                   ' <label  for="meter_start">Travel Type </label>'+
                   ' <select class="form-control"  name="travel_type[]" id="travel_type'+add_count+'" onchange="change_travel(this.value,'+add_count+')">'+
                   ' <option value="">Select Travel Type</option>'+
                   ' <option value="Bike">Bike</option>'+
                   ' <option value="Car">Car</option>'+
                   ' <option value="Train">Train</option>'+
                   ' <option value="Bus">Bus</option>'+
                   '<option value="Auto">Auto</option>'+
                   ' </select>'+
                   '</div>'+
          '<div class="form-check col-sm-2 carbike_sec'+add_count+'" style="display:none">'+
          '<label  for="meter_start">Meter Reading </label>'+
          ' <input class="form-check-input"  type="number" name="meter_start[]" id="meter_start'+add_count+'" value="" placeholder="Meter Reading">'+
          ' <span class="error_message" id="meter_start'+add_count+'_message" style="display: none">Field is required</span>'+
          '</div>'+
          ' <div class="form-check col-sm-2 bike_train_sec'+add_count+'" style="display:none">'+
          '<label  for="meter_start">Time </label>'+
          ' <input class="form-check-input" type="text" name="start_time[]" id="start_time'+add_count+'" value="<?php echo date("H:i")?>" placeholder="Meter Reading">'+
          ' </div>'+
          '<div class="form-check col-sm-2 bike_train_sec'+add_count+'" style="display:none">'+
          ' <label  for="meter_start">Attach photo </label>'+
          '<input class="form-check-input"  type="file" name="fair_doc[]" id="fair_doc'+add_count+'" value="" >'+
          ' </div>'+
          '<div class="form-check col-sm-2 bike_train_sec'+add_count+'" style="display:none" onchange="change_hospital('+add_count+')">'+
                   ' <label  for="meter_start">Hospital </label>'+
                   ' <select class="form-control user_id"  name="user_id[]" id="user_id'+add_count+'">'+
                   '<option value="">Select Hospital</option>'+
                   '</select>'+
                   '<span class="error_message" id="user_id'+add_count+'_message" style="display: none">Field is required</span>'+
                   '</div>'+
          ' <div class="form-check col-sm-2 bike_train_sec'+add_count+'" style="display:none">'+
          ' <label  for="meter_start">Task </label>'+
          ' <select class="form-control"  name="travel_task_id[]" multiple="multiple"  id="travel_task_id'+add_count+'" onchange="change_task(this.value)">'+
          ' <option value="">Select Task</option>'+
         ' </select>'+
         '<span class="error_message" id="travel_task_id'+add_count+'_message" style="display: none">Field is required</span>'+
         '</div>'+
         ' </div>'+
           ' <div class="box-footer col-md-12 travtype_sec add_start_travel">'+
           '  <button type="button" class="btn btn-primary"  onclick="add_start_travel('+add_count+')">Submit</button>'+
           '<div class="load_travel'+add_count+'" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>'+
           ' </div>'+
         ' <div class="travel_end_sec'+add_count+'" style="display:none;">'+
         ' <div class="form-check col-md-12">'+
         '  <button type="button" class="btn btn-primary"  onclick="viewednd_travel('+add_count+')">End Travel</button>'+
        ' <button type="button" class="btn btn-primary addmore_field"   onclick="addmore_task_field('+add_count+')">Add More Task</button>'+
         ' </div>'+
        ' <div class="addmore_task'+add_count+'" style="display:none;">'+
        '<div class="form-check col-sm-2 "  onchange="change_hospital_field_staff('+add_count+')">'+
        ' <label  for="meter_start">Hospital </label>'+
        '<select class="form-control user_id"  name="hospital_id[]" id="hospital_id'+add_count+'">'+
        ' </select>'+
       ' </div>'+
           ' <div class="form-group col-sm-2 ">'+
           '<label  for="meter_start">Task </label>'+
           ' <select class="form-control"  name="more_office_task_id[]" id="more_office_task_id'+add_count+'" multiple="multiple">'+
        '</select>'+
        '<span class="error_message" id="more_office_task_id'+add_count+'_message" style="display: none">Field is required</span>'+
        ' </div>'+
          '<div class="box-footer col-md-3 ">'+
          ' <button type="button" class="btn btn-primary"  onclick="save_fieldstaff_moretask('+add_count+')">Submit</button>'+
          ' <div class="save_fieldstaff_moretask" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>'+
          ' </div>'+
       ' </div>'+
          '<div class="form-check col-sm-2 carbike_sec_end'+add_count+'" >'+
          ' <label  for="meter_start">Meter Reading </label>'+
          ' <input class="form-check-input"  type="number" name="meter_end[]" id="meter_end'+add_count+'" value="" placeholder="Meter Reading">'+
          ' <span class="error_message" id="meter_end'+add_count+'_message" style="display: none">Field is required</span>'+
          ' <span class="error_message" id="meter_end_error'+add_count+'_message" style="display: none">End reading must be greater than your start reading.</span>'+
          ' </div>'+
          '<div class="form-check col-sm-2 tra_end_sec">'+
          ' <label  for="meter_start">Attach photo </label>'+
          '  <input class="form-check-input"  type="file" name="fair_doc_end[]" id="fair_doc_end'+add_count+'" value="" >'+
          ' </div>'+
          ' <div class="form-check col-sm-2 other_sec_end'+add_count+'" >'+
          '<label  for="meter_start">Amount </label>'+
          ' <input class="form-check-input" type="text" name="amount_end[]" id="amount_end'+add_count+'" value="" placeholder="Amount">'+
          '  <span class="error_message" id="amount_end'+add_count+'_message" style="display: none">Field is required</span>'+
          '   </div>'+
          ' <div class="form-check col-sm-2 tra_end_sec">'+
          '<label  for="meter_start">Time </label>'+
          '  <input class="form-check-input" type="text" readonly name="end_time[]" id="end_time'+add_count+'" value="<?php echo date("H:i")?>" placeholder="Meter Reading">'+
          ' </div>'+
            '<div class="box-footer col-md-12 tra_end_sec">'+
            '  <button type="button" class="btn btn-primary"  onclick="add_end_travel('+add_count+')">Submit</button>'+
            '<div class="load_travel_end'+add_count+'" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>'+
           ' </div>'+
         ' </div>'+
         ' </div>'+
         '</div>';
         get_alltask_staff('#travel_task_id'+add_count);
         get_alltask_hospital_staff('#user_id'+add_count);
         get_alltask_hospital_staff('#hospital_id'+add_count);
  $(".more_travel_display_sec").append(html);
}
function change_leave_month(months)
{
  var url = APP_URL+'/staff/get_current_monthdates';
  var cur_year=$("#cur_year").val();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      cur_year:cur_year,months:months
    },
    success: function (data)
    {
      var proObj = JSON.parse(data);
      var htmls='';
      for (var i = 0; i < proObj.length; i++) {
         htmls +=' <div class="panel panel-default col-sm-2" onclick="change_date('+proObj[i]+')">'+
  '<div class="panel-body">'+proObj[i]+'</div>'+
'</div>';
      }
      $(".month_dispaly").html(htmls);
    }
  });
}
function add_task()
{
  var url = APP_URL+'/staff/AllTask';
  window.open(url);
}
function change_date(change_date,$a)
{
var staff_id=$("#staff_id").val();
var dateAr = change_date.split('-');
var newDate = dateAr[1] + '/' + dateAr[2] + '/' + dateAr[0];
//console.log(newDate+'--'+change_date)
$("#work_date_dis").html(newDate);
  $("#search_date").val(change_date);
  $("#work_date").val(change_date);
  $(".datesection").hide();
  $(".option_section").show();
  if($a==1)
  {
    var leave_data=' <option value="">Select Options</option>'+
              '<option value="Request For Leave">Request For Leave</option>'+
               '<option value="Work Update Office">Work Update Office</option>'+
               '<option value="Work Update Field Staff">Work Update Field Staff</option>';
  }
  else{
    var leave_data=' <option value="">Select Options</option>'+
              '<option value="Request For Leave">Request For Leave</option>';
  }
$("#leave_option").html(leave_data);
  var url = APP_URL+'/staff/get_travel_expence_staff';
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:change_date
    },
    success: function (data)
    {
      var res = data.split("*");
      var travel = JSON.parse(res[0]);
      var expence = JSON.parse(res[1]);
      var error_flag=0;
      var attend_status=res[2];
      if(attend_status==1)
            {
              if(travel.length==0 && expence.length>0)
                {
                  $(".addleave_btsecfirst").show();   
                }
                else{
                  $(".addleave_btsecfirst").hide();   
                }
            }
            else{
            $(".addleave_btsecfirst").show();   
            }
      if(travel.length>0)
      {
        var htmls='';
        htmls +='<table class="table cmsTabletravel" >';
        htmls +=' <tr>'+
                 '<th>Travel Type</th>'+
                 '<th>Start Reading</th>'+
                 '<th>End Reading</th>'+
                 '<th>kilometers</th>'+
                 '<th>Amount</th>'+
                 '<th>Start Time</th>'+
                 '<th>End Time</th>'+
                 '<th>Task</th>'+
                 '<th>Start Image</th>'+
                 '<th>End Image</th>'+
                 '</tr>  <tbody id="travel_data">';
                 var trav_task_ids = [];
                 for (var i = 0; i < travel.length; i++) {
                  var total_meter=0;
                  if(travel[i]["travel_type"]=="Bike")
                    {
                      var bike_rate=2.5;
                      var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                      var tot_price=total_meter*2.5;
                    }
                    if(travel[i]["travel_type"]=="Car")
                    {
                      var car_rate=5;
                      var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                      var tot_price=total_meter*5;  
                    }
                    if(jQuery.inArray( travel[i]["travel_task_id"], trav_task_ids )=="-1")
                 {  
                  htmls +=' <tr data-id="'+travel[i]["travel_task_id"]+'">';
                 }else{
                  htmls +=' <tr >';
                 }
                  htmls +='<td data-th="Travel Type">'+travel[i]["travel_type"]+'</td>';
                  if(travel[i]["start_meter_reading"]==null)
                 {
                  htmls +='<td data-th="Start Reading">NA</td>';
                 }
                 else{
                  htmls +='<td data-th="Start Reading">'+travel[i]["start_meter_reading"]+'</td>';
                 }
                 if(travel[i]["end_meter_reading"]!=null)
                 {
                  htmls +='<td data-th="End Reading">'+travel[i]["end_meter_reading"]+'</td>';
                 }
                 else{
                  htmls +='<td data-th="End Reading">NA</td>';
                 }
                 if(total_meter>0)
                 {
                  htmls +='<td data-th="kilometers">'+total_meter+'</td>';
                 }
                 else{
                  htmls +='<td data-th="kilometers">NA</td>';
                 }
                 if(travel[i]["travel_type"]=="Car"  ||  travel[i]["travel_type"]=="Bike")
                 {
                   if(tot_price>0){
                    htmls +='<td data-th="Amount">'+tot_price+'</td>';
                   }
                   else{
                    htmls +='<td data-th="Amount">NA</td>';
                   }
                 }
                 else{
                   if(travel[i]["travel_end_amount"]>0){
                    htmls +='<td data-th="Amount">'+travel[i]["travel_end_amount"]+'</td>';
                   }else{
                    htmls +='<td data-th="Amount">NA</td>';
                   }
                 }
           htmls += '<td data-th="Start Time">'+travel[i]["start_time_travel"]+'</td>';
          if(travel[i]["end_time_travel"]!=null)
          {
            htmls +='<td data-th="End Time">'+travel[i]["end_time_travel"]+'</td>';
          }
          else{
            htmls +='<td data-th="End Time"></td>';
          }
  //console.log(trav_task_ids+'--'+trav_task_ids.indexOf(travel[i]["travel_task_id"]));
  var child_task=[];
  if(travel[i]["travel_task_child_id"]!=null)
  {
    var child_task = travel[i]["travel_task_child_id"].split(",");
    var child_task_name = travel[i]["travel_task_child_name"].split(",");
    if(travel[i]["travel_task_child_date"]!=null)
    {
      var child_task_date = travel[i]["travel_task_child_date"].split(",");
    }
    else{
      var child_task_date = [];
    }
  }
                 if(jQuery.inArray( travel[i]["travel_task_id"], trav_task_ids )=="-1")
                 {
                  htmls +=  '<td data-th="Task" >';
                  if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                    htmls +=travel[i]["task_name"];
                  }
                  else{
                    htmls +='<a class="popup" data-id="'+travel[i]["travel_task_id"]+'">'+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')</a>';
                  }
                  if(child_task.length>0)
                  {
                  for (var k = 0; k < child_task.length; k++) {
                    htmls +=  '<br>';
                    if(jQuery.inArray( child_task[k], trav_task_ids )=="-1")
                    {
                      if(child_task_name[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +='<a class="popup" data-id="'+child_task[k]+'">'+child_task_name[k]+' ('+child_task_date[k]+')</a>';
                        }
                      }
                    }
                    else{
                      if(child_task_date[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +=child_task_name[k] +'('+child_task_date[k]+')';
                        }
                      }
                    }
                    trav_task_ids.push(child_task[k]);
                  } 
                 }
                  htmls +='</td>';
                 }
                 else{
                //  htmls +=  '<td data-th="Task" >'+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')</td>';
                   htmls +=  '<td data-th="Task" >';
                  htmls +=''+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')';
                  if(child_task.length>0)
                  {
                  for (var k = 0; k < child_task.length; k++) {
                    htmls +=  '<br>';
                    if(jQuery.inArray( child_task[k], trav_task_ids )=="-1")
                    {
                      if(child_task_name[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +='<a class="popup" data-id="'+child_task[k]+'">'+child_task_name[k]+' ('+child_task_date[k]+')</a>';
                        }
                      }
                    }
                    else{
                      if(child_task_date[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +=child_task_name[k] +'('+child_task_date[k]+')';
                        }
                      }
                    }
                    trav_task_ids.push(child_task[k]);
                  } 
                 }
                  htmls +='</td>';
                  }
                  if(travel[i]["travel_start_image"]!='' && travel[i]["travel_start_image"]!=null)
                  {
                    var tr_imgs_start="{{asset('public/storage/comment/')}}/"+travel[i]["travel_start_image"];
 htmls +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    htmls +='<td></td>';
                  }
                  if(travel[i]["travel_end_image"]!='' && travel[i]["travel_end_image"]!=null)
                  {
                    var tr_imgs_end="{{asset('public/storage/comment/')}}/"+travel[i]["travel_end_image"];
 htmls +='<td><a href="'+tr_imgs_end+'" download="'+tr_imgs_end+'"><img src="'+tr_imgs_end+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    htmls +='<td></td>';
                  }
               // console.log(trav_task_ids+'--'+travel[i]["travel_task_id"]+'---'+trav_task_ids.indexOf(travel[i]["travel_task_id"]))
                 htmls +='</tr>';
               /* if(travel[i]["status"]=="N" || travel[i]["status"]=="Reject")
                 {
                  error_flag=1;
                 }*/
                 trav_task_ids.push(travel[i]["travel_task_id"]);
                 }
                 htmls +='</tbody></table>';
      }
      //work_approval_travel
      $(".display_add_data").html(htmls);
      if(expence.length>0)
      {
        var html_expence='';
        html_expence +='<table class="table">';
        html_expence +=' <tr>'+
                 '<th>Other Expence</th>'+
                 '<th>Amount</th>'+
                 '<th>Description</th>'+
                 '<th>Task</th>'+
                 '<th>Status</th>'+
                 '<th>Edit</th>'+
                 '<th>Image</th>'+
                 '</tr>  <tbody id="expence_data">';
        for (var i = 0; i < expence.length; i++) {
          html_expence +=' <tr>'+
                 '<td data-th="Other Expence">'+expence[i]["travel_type"]+'</td>'+
                 '<td data-th="Amount">'+expence[i]["travel_start_amount"]+'</td>'+
                 '<td data-th="Description">'+expence[i]["expence_desc"]+'</td>'+
                 '<td data-th="Task">'+expence[i]["task_name"]+'</td>';
                 if(expence[i]["status"]=="Reject")
                 {
                  html_expence +='<td  data-th="Status" style="color:red">Reject</td>';
                 } 
                 if(expence[i]["status"]=="Y")
                 {
                  html_expence +='<td  data-th="Status" style="color:green">Approved</td>';
                 } 
                 if(expence[i]["status"]=="N")
                 {
                  html_expence +='<td  data-th="Status" style="color:red">Pending</td>';
                 } 
                 var type_expence="field";
                 if(expence[i]["status"]=="Reject")
                 {
                  if(expence[i]["comment_admin"]!='')
                   {
                    var comment_admin=expence[i]["comment_admin"];
                   }
                  html_expence +='<td  data-th="Edit" style="color:green"><a onclick="edit_expence('+expence[i]["id"]+',\'' + expence[i]["travel_type"] + '\',\'' + expence[i]["travel_start_amount"] + '\',\'' + expence[i]["expence_desc"] + '\',\'' + expence[i]["travel_task_id"] + '\',\'' + type_expence + '\')">Edit</a></td>';
                 }
                 else{
                  if(expence[i]["comment_admin"]!='')
                   {
                    var comment_admin=expence[i]["comment_admin"];
                   }
                  html_expence +='<td  data-th="Edit" style="color:green"></td>';
                 }
                  if(expence[i]["travel_start_image"]!='' && expence[i]["travel_start_image"]!=null)
                  {
                    var tr_imgs_start="{{asset('public/storage/comment/')}}/"+expence[i]["travel_start_image"];
                    html_expence +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    html_expence +='<td></td>';
                  }
                html_expence += '</tr>';
               /* if(expence[i]["status"]=="N" || expence[i]["status"]=="Reject")
                 {
                  error_flag=1;
                 }*/
        }
        html_expence +='</tbody></table>';
        if(comment_admin!='' && comment_admin!=undefined)
        {
          html_expence +='<h3>Admin Comment</h3><br>'+comment_admin;
        }
      }
     /* if(error_flag==1)
      {
        $(".addleave_btsecfirst").hide();
      }
      else{
        if(expence.length>0 || travel.length>0)
        {
        $(".addleave_btsecfirst").show();
        }
      }*/
      $(".expence_results").html(html_expence);
    },
    error: function (data) {
location.reload();
}
  });
  var url = APP_URL+'/staff/check_travel_from_status';
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:change_date
    },
    success: function (data)
    {
      var levaedata = JSON.parse(data);
      var htmls='';
      if(levaedata.length>0)
      {
      $("#travel_type_hidden").val(levaedata[0]["travel_type"]);
      $("#travel_start_read_hidden").val(levaedata[0]["start_meter_reading"]);
      $("#task_id_hidden").val(levaedata[0]["travel_task_id"]);
      $(".travel_section").show();
      $(".travel_end_sec0").show();
      $(".travtype_sec").hide();
      $(".travel_first").hide();
      }
      else{
        $(".travel_first").show();
      }
    }
  });
/*
  var url = APP_URL+'/staff/get_date_sort_task';
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:change_date
    },
    success: function (data)
    {
      var proObj = JSON.parse(data);
      var htmls='';
      htmls +='<option value="">Select Task</option>';      
      for (var i = 0; i < proObj.length; i++) {
        htmls +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';       
      }
      $("#task_id0").html(htmls);
      $("#office_task_id").html(htmls);
    }
  });*/
}
function get_alltask_staff(ids)
{
  var url = APP_URL+'/staff/get_date_sort_task';
var change_date='';
$.ajax({
  type: "POST",
  cache: false,
  url: url,
  data:{
    sel_date:change_date
  },
  success: function (data)
  {
    var proObj = JSON.parse(data);
    var htmls='';
    htmls +='<option value="">Select Task</option>';      
    for (var i = 0; i < proObj.length; i++) {
      htmls +='<option value="'+proObj[i]["id"]+'" attr-name="'+proObj[i]["name"]+'">'+proObj[i]["name"]+' ('+proObj[i]["start_date"]+')</option>';       
    }
    $(ids).html(htmls);
  }
});
}
function get_alltask_hospital_staff(ids)
{
  var url = APP_URL+'/staff/get_all_hospital_list';
var change_date='';
$.ajax({
  type: "POST",
  cache: false,
  url: url,
  data:{
    sel_date:change_date
  },
  success: function (data)
  {
    var proObj = JSON.parse(data);
    var htmls='';
    htmls +='<option value="">Select Hospital</option>';      
    for (var i = 0; i < proObj.length; i++) {
      htmls +='<option value="'+proObj[i]["id"]+'" >'+proObj[i]["business_name"]+' </option>';       
    }
    htmls +='<option value="Other" >Other </option>';    
    $(ids).html(htmls);
    $('.user_id').select2();
  }
});
}
jQuery( document ).ready(function() {
  $("#leave_option").on('change', function() {
    var change_leave=$(this).val();
    var staff_id=$("#staff_id").val();
    if(staff_id>0)
    {
      console.log('staff');
    }
    else{
      location.reload();
    }
if(change_leave=="Work Update Office")
  { 
    $(".work_fildsection").hide();
    var search_date=$("#search_date").val();
      var office_expence=0;
  var htmls='';
 var search_date=$("#search_date").val();
  var url = APP_URL+'/staff/get_travel_expence_staff_office';
  $(".load_leave_option").show();
$.ajax({
type: "POST",
cache: false,
url: url,
data:{
sel_date:search_date
},
success: function (data)
{
$(".load_leave_option").hide();
var expence = JSON.parse(data);
var flag_office_staff=0;
if(expence.length>0)
{office_expence=1;
 var html_expence='';
 html_expence +='<table class="table">';
 html_expence +=' <tr>'+
          '<th>Other Expence</th>'+
          '<th>Amount</th>'+
          '<th>Description</th>'+
          '<th>Task</th>'+
          '<th>Status</th>'+
          '<th>Edit</th>'+
          '<th>Image</th>'+
          '</tr>  <tbody id="expence_data">';
 for (var i = 0; i < expence.length; i++) {
 /*  if(expence[i]["status"]=="N" || expence[i]["travel_type"]=="Reject"){
     $(".office_staff_attendence_btn").hide();
   }
   else{
     $(".office_staff_attendence_btn").show();
   }*/
   html_expence +=' <tr>'+
          '<td data-th="Other Expence">'+expence[i]["travel_type"]+'</td>'+
          '<td data-th="Amount">'+expence[i]["travel_start_amount"]+'</td>'+
          '<td data-th="Description">'+expence[i]["expence_desc"]+'</td>'+
          '<td data-th="Task">'+expence[i]["task_name"]+'</td>';
          if(expence[i]["status"]=="Reject")
          {
           html_expence +='<td  data-th="Status" style="color:red">Reject</td>';
          } 
          if(expence[i]["status"]=="Y")
          {
           html_expence +='<td  data-th="Status" style="color:green">Approved</td>';
          } 
          if(expence[i]["status"]=="N")
          {
           html_expence +='<td  data-th="Status" style="color:red">Pending</td>';
          } 
          var type_expence="office";
          if(expence[i]["status"]=="Reject")
          {
            if(expence[i]["comment_admin"]!='')
            {
             var comment_admin=expence[i]["comment_admin"];
            }
           html_expence +='<td  data-th="Edit" style="color:green"><a onclick="edit_expence('+expence[i]["id"]+',\'' + expence[i]["travel_type"] + '\',\'' + expence[i]["travel_start_amount"] + '\',\'' + expence[i]["expence_desc"] + '\',\'' + expence[i]["travel_task_id"] + '\',\'' + expence[i]["type_expence"] + '\')">Edit</a></td>';
          }
          else{
           if(expence[i]["comment_admin"]!='')
            {
             var comment_admin=expence[i]["comment_admin"];
            }
           html_expence +='<td  data-th="Edit" style="color:green"></td>';
          }
           if(expence[i]["travel_start_image"]!='' && expence[i]["travel_start_image"]!=null)
                  {
                    var tr_imgs_start="{{asset('public/storage/comment/')}}/"+expence[i]["travel_start_image"];
 html_expence +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    html_expence +='<td></td>';
                  }
         html_expence += '</tr>';
         if(expence[i]["status"]=="N" || expence[i]["status"]=="Reject")
          {
           error_flag=1;
          }
 }
 html_expence +='</tbody></table>';
 if(comment_admin!='' && comment_admin!=undefined)
 {
   html_expence +='<h3>Admin Comment</h3><br>'+comment_admin;
 }
}
  $(".expence_results_office_sec").html(html_expence);
 // $(".more_expence_btn_off_staff ").show();
$(".add_expence").show();
$(".expence_sec_office_staff"+row_no).hide();
$(".child_exp_office").hide();
},
 error: function (data) {
location.reload();
}
});
     var url = APP_URL+'/staff/get_office_staff_all_details';
     $(".load_leave_option").show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      search_date:search_date
    },
    success: function (data)
    {
      var htmls="";
      var res = data.split("*");
      var levaedata = JSON.parse(res[0]);
      var attend_status=res[1];
      var htmls_data='';
      $(".load_leave_option").hide();
if(levaedata.length>0)
{
  htmls_data +='<table class="table cmsTabletravel"> <tr>'+
  '<th>Start Time</th>'+
  '<th>Task</th>'+
                 '<th>End Time</th>'+
                 '</tr>  <tbody id="leave_data">';
                 var trav_task_ids = [];
                 for (var i = 0; i < levaedata.length; i++) {
                  htmls_data +='<tr data-id="'+levaedata[i]["task_id"]+'"><td data-th="Start Time">'+levaedata[i]["start_time"]+' </td>';
if(levaedata[i]["task_name"]!=null)
{
  if(levaedata[i]["task_child_id"]!=null)
  {
    var child_task_id = levaedata[i]["task_child_id"].split(",");
    var child_task_name = levaedata[i]["task_child_name"].split(",");
    var child_task_date = levaedata[i]["task_child_date"].split(",");
  }
  else{
    var child_task_id = [];
  var child_task_name = [];
  }
  htmls_data +='<td data-th="Task">';
  console.log(trav_task_ids+'--'+levaedata[i]["task_id"])
  if(jQuery.inArray(levaedata[i]["task_id"],trav_task_ids)=="-1")
                 {
                  htmls_data +='<a class="popup" data-id="'+levaedata[i]["task_id"]+'">'+levaedata[i]["task_name"]+'  ('+levaedata[i]["main_task_date"]+')</a>';
                 }
                 else{
                  htmls_data +=levaedata[i]["task_name"]+'  ('+levaedata[i]["main_task_date"]+')';
                 }
  if(child_task_id.length>0)
  {
  for (var k = 0; k < child_task_id.length; k++) {
    htmls_data +='<br>';
    if(child_task_date[k]!='')
    {
      var child_date='('+child_task_date[k]+')';
    }
    else{
      var child_date='';
    }
//alert(child_date)
if(jQuery.inArray(child_task_id[k],trav_task_ids)=="-1")
                 {
                  htmls_data +='<a class="popup" data-id="'+child_task_id[k]+'">'+child_task_name[k]+' '+child_date+'</a>';   
                  trav_task_ids.push(child_task_id[k]);
                }
                 else{
                  htmls_data +=child_task_name[k]+' '+child_date;
                  trav_task_ids.push(child_task_id[k]);
                 }
    }
  }
  htmls_data +='</td>';
}
else{
  htmls_data +='<td data-th="Task">NA</td>';
}
if(levaedata[i]["end_time"]!=null)
{
  htmls_data +='<td data-th="End Time">'+levaedata[i]["end_time"]+'</td>';
}
else{
  htmls_data +='<td data-th="End Time"></td>';
}
  htmls_data +=' </tr>';
  trav_task_ids.push(levaedata[i]["task_id"]);
                 }
  htmls_data +='</tbody></table>';
}
$(".display_office_staff_all").html(htmls_data);
      if(levaedata.length>0)
      {
        for (var i = 0; i < levaedata.length; i++) {
        if(levaedata[i]["start_time"]!=null)
        {
          htmls +='<table class="table"> <tr>'+
                 '<th>Start Time</th>'+
                 '</tr>  <tbody id="leave_data">';
  htmls +='<tr><th  data-th="Start Time">'+levaedata[i]["start_time"]+'</th></tr>';
  htmls +='</tbody></table>';
  $(".work_officesection").show();
  //$(".display_start_time_ofice").html(htmls);
          $(".start_workbtn_office").hide();
          $(".start_work_office").hide();
          $(".leave_section").hide();
          $(".office_staff_attendence_btn").hide();
        }
        var htmls="";
         if(levaedata[i]["task_id"]!=null)
        {
          htmls +='<table class="table"> <tr>'+
                 '<th>Task</th>'+
                 '</tr>  <tbody id="leave_data">';
  htmls +='<tr><th  data-th="Task">'+levaedata[i]["task_name"]+'</th></tr>';
  htmls +='</tbody></table>';
  $(".work_officesection").show();
  //$(".display_taskdetail_ofice").html(htmls);
  $(".officestaff_tasksec").hide();
$("#office_task_id_message").hide();
          $(".start_workbtn_office").hide();
          $(".start_work_office").hide();
          $(".leave_section").hide();
          $(".office_staff_attendence_btn").hide();
        }
        else{
          $(".officestaff_tasksec").show();
          $(".show_add_task_sec").show();
          $(".end_work_display_office").hide();
        }
        var htmls="";
         if(levaedata[i]["end_time"]!=null)
        {
          htmls +='<table class="table"> <tr>'+
                 '<th>End Time</th>'+
                 '</tr>  <tbody id="leave_data">';
  htmls +='<tr><th  data-th="End Time">'+levaedata[i]["end_time"]+'</th></tr>';
  htmls +='</tbody></table>';
  $(".work_officesection").show();
  $(".end_work_office").hide();
  //$(".display_end_time_ofice").html(htmls);
  $(".officestaff_tasksec").hide();
$("#office_task_id_message").hide();
          $(".start_workbtn_office").hide();
          $(".start_work_office").hide();
          $(".leave_section").hide();
          $(".office_staff_attendence_btn").hide();
          $(".show_add_task_sec").show();
        }
        else{
          if(levaedata[i]["task_id"]!=null)
            {
              $(".show_add_task_sec").show();
              $(".end_work_display_office").show();
            }
        }
//$(".end_work_display_office").show();
if(levaedata[i]["start_time"]!=null && levaedata[i]["end_time"]!=null && levaedata[i]["task_id"]!=null)
        {
          $(".start_workbtn_office").show();
        }
}
      }
      else{
        $(".work_officesection").show();
    $(".work_fildsection").hide();
    $(".leave_section").hide();
     $(".office_staff_attendence_btn").hide();   
      }
if(attend_status==1)
{
  if(office_expence>0 && levaedata.length==0)
  {
    $(".office_staff_attendence_btn").show();  
  }
  else{
    $(".office_staff_attendence_btn").hide();  
  }
}
else{
  $(".office_staff_attendence_btn").show();   
}
    }
  });
 var url = APP_URL+'/staff/get_request_leave';
     var type_leave='Request Leave Office Staff';
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:search_date,type_leave:type_leave
    },
    success: function (data)
    {
      var res = data.split("*");
      var levaedata = JSON.parse(res[0]);
      var htmls='';
      if(res[1]>0)
      {
        $(".office_staff_attendence_btn").hide();
        $(".addleave_btsecfirst").hide();
      }
      if(levaedata.length>0)
      {
        $(".office_staff_attendence_btn").hide();
      htmls +='<table class="table"> <tr>'+
                    '<th>Attendance</th>'+
                    '</tr>  <tbody id="leave_data">';
                    for (var i = 0; i < levaedata.length; i++) {
      htmls +='<tr><td data-th="Attendance">'+levaedata[i]["attendance"]+'</td></tr>';
                    }
      htmls +='</tbody></table>';
  $(".leave_data_officestaff").html(htmls);
      }
    }
  });
  }
  else if(change_leave=="Work Update Field Staff"){
    $(".work_fildsection").show();
    $(".work_officesection").hide();
    $(".leave_section").hide();
    var change_date=$("#search_date").val();
    var type_leave='Request Leave Field Staff';
     var url = APP_URL+'/staff/get_request_leave';
     $(".load_leave_option").show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:change_date,type_leave:type_leave
    },
    success: function (data)
    {
      $(".load_leave_option").hide();
      var res = data.split("*");
      var levaedata = JSON.parse(res[0]);
      var htmls='';
      console.log('---'+res[1])
      if(res[1]>0)
      {
        $(".office_staff_attendence_btn").hide();
        $(".addleave_btsecfirst").hide();
      }
      if(levaedata.length>0)
      {
      htmls +='<table class="table"> <tr>'+
                    '<th>Attendance</th>'+
                    '</tr>  <tbody id="leave_data">';
                    for (var i = 0; i < levaedata.length; i++) {
      htmls +='<tr><td data-th="Attendance">'+levaedata[i]["attendance"]+'</td></tr>';
                    }
      htmls +='</tbody></table>';
        $(".levedrop_sec").hide();
  $(".leave_data").html(htmls);
  $(".leave_data").show();
  $(".first_leave_section").show();
  $(".addleave_btsecfirst").hide();
      }
    },
    error: function (data) {
location.reload();
}
  });
  }
  else{
    $(".work_fildsection").hide();
    $(".work_officesection").hide();
    $(".leave_section").show();
  var change_date=$("#search_date").val();
  var type_leave='Request Leave';
     var url = APP_URL+'/staff/get_request_leave';
     $(".load_leave_option").show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:change_date,type_leave:type_leave
    },
    success: function (data)
    {
      $(".load_leave_option").hide();
      var res = data.split("*");
      var levaedata = JSON.parse(res[0]);
      var htmls='';
      if(res[1]>0)
      {
        $(".office_staff_attendence_btn").hide();
        $(".addleave_btsecfirst").hide();
      }
      if(levaedata.length>0)
      {
      htmls +='<table class="table"> <tr>'+
                    '<th>Leave</th>'+
                    '<th>Reason For Leave</th>'+
                    '</tr>  <tbody id="leave_data">';
                    for (var i = 0; i < levaedata.length; i++) {
      htmls +='<tr><td data-th="Leave">'+levaedata[i]["attendance"]+'</td><td data-th="Reason For Leave">'+levaedata[i]["reason_leave"]+'</td></tr>';
                    }
      htmls +='</tbody></table>';
       $(".levedrop_sec_office").hide();
      $(".dis_ajax_req_leave").html(htmls);
      }
    },
    error: function (data) {
location.reload();
}
  });
  }
  });
});
function change_leave_options(change_leave)
{
}
function change_hospital_field_staff(row_no)
{
  var user_id=$("#hospital_id"+row_no).val();
  var url = APP_URL+'/staff/get_hospital_sortbytravel_task';
  var search_date=$("#search_date").val();
  if(user_id>0)
  {
$.ajax({
  type: "POST",
  cache: false,
  url: url,
  data:{
    user_id:user_id,search_date:search_date
  },
  success: function (data)
  {
    var proObj = JSON.parse(data);
    var htmls='';
  //  htmls +='<option value="">Select Task</option>';      
    for (var i = 0; i < proObj.length; i++) {
      htmls +='<option value="'+proObj[i]["id"]+'" attr-name="'+proObj[i]["name"]+'">'+proObj[i]["name"]+' ('+proObj[i]["start_date"]+')</option>';       
    }
    $("#more_office_task_id"+row_no).html('');
    $("#more_office_task_id"+row_no).html(htmls);
    $('#more_office_task_id'+row_no).multiselect('rebuild');
    $('#more_office_task_id'+row_no).multiselect({
      enableFiltering: true,
    });
  }
});
}
else{var htmls='';
  htmls +='<option value="3446" attr-name="Going To Office">Going To Office</option>';       
    htmls +='<option value="3447" attr-name="Going To Home">Going To Home</option>';       
    $("#more_office_task_id"+row_no).html('');
    $("#more_office_task_id"+row_no).html(htmls);
    $('#more_office_task_id'+row_no).multiselect('rebuild');
}
}
function save_fieldstaff_moretask(row_no)
{
  var search_date=$("#search_date").val();
  var more_office_task_id=$("#more_office_task_id"+row_no).val();
  var url = APP_URL+'/staff/save_field_staff_moretask';
  $.ajax({
  type: "POST",
  cache: false,
  url: url,
  data:{
    search_date:search_date,task_id:more_office_task_id
  },
  success: function (data)
  {
$(".addmore_task"+row_no).hide();
 var change_date=$("#search_date").val();
    var url = APP_URL+'/staff/get_travel_expence_staff';
    $(".load_travel_end"+row_no).show();
  $.ajax({
    type: "POST",
    cache: false,
    url: url,
    data:{
      sel_date:change_date
    },
    success: function (data)
    {
      $(".load_travel_end"+row_no).hide();
      var res = data.split("*");
      var travel = JSON.parse(res[0]);
      var expence = JSON.parse(res[1]);
      var error_flag=0;
      if(travel.length>0)
      {
        var htmls='';
        htmls +='<table class="table cmsTabletravel" >';
        htmls +=' <tr>'+
                 '<th>Travel Type</th>'+
                 '<th>Start Reading</th>'+
                 '<th>End Reading</th>'+
                 '<th>kilometers</th>'+
                 '<th>Amount</th>'+
                 '<th>Start Time</th>'+
                 '<th>End Time</th>'+
                 '<th>Task</th>'+
                 '<th>Start Image</th>'+
                  '<th>End Image</th>'+
                 '</tr>  <tbody id="travel_data">';
                 var trav_task_ids = [];
                 for (var i = 0; i < travel.length; i++) {
                  var total_meter=0;
                  if(travel[i]["travel_type"]=="Bike")
                    {
                      var bike_rate=2.5;
                      var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                      var tot_price=total_meter*2.5;
                    }
                    if(travel[i]["travel_type"]=="Car")
                    {
                      var car_rate=5;
                      var total_meter=parseInt(travel[i]["end_meter_reading"])-parseInt(travel[i]["start_meter_reading"]);
                      var tot_price=total_meter*5;  
                    }
                    if(jQuery.inArray(travel[i]["travel_task_id"], trav_task_ids )=="-1")
                 {  
                  htmls +=' <tr data-id="'+travel[i]["travel_task_id"]+'">';
                 }else{
                  htmls +=' <tr >';
                 }
                  htmls +='<td data-th="Travel Type">'+travel[i]["travel_type"]+'</td>';
                  if( travel[i]["start_meter_reading"]==null)
                 {
                  htmls +='<td data-th="Start Reading">NA</td>';
                 }
                 else{
                  htmls +='<td data-th="Start Reading">'+travel[i]["start_meter_reading"]+'</td>';
                 }
                 if(travel[i]["end_meter_reading"]!=null)
                 {
                  htmls +='<td data-th="End Reading">'+travel[i]["end_meter_reading"]+'</td>';
                 }
                 else{
                  htmls +='<td data-th="End Reading">NA</td>';
                 }
                 if(total_meter>0)
                 {
                  htmls +='<td data-th="kilometers">'+total_meter+'</td>';
                 }
                 else{
                  htmls +='<td data-th="kilometers">NA</td>';
                 }
                 if(travel[i]["travel_type"]=="Car"  ||  travel[i]["travel_type"]=="Bike")
                 {
                   if(tot_price>0){
                    htmls +='<td data-th="Amount">'+tot_price+'</td>';
                   }
                   else{
                    htmls +='<td data-th="Amount">NA</td>';
                   }
                 }
                 else{
                   if(travel[i]["travel_end_amount"]>0){
                    htmls +='<td data-th="Amount">'+travel[i]["travel_end_amount"]+'</td>';
                   }else{
                    htmls +='<td data-th="Amount">NA</td>';
                   }
                 }
           htmls += '<td data-th="Start Time">'+travel[i]["start_time_travel"]+'</td>';
          if(travel[i]["end_time_travel"]!=null)
          {
            htmls +='<td data-th="End Time">'+travel[i]["end_time_travel"]+'</td>';
          }
          else{
            htmls +='<td data-th="End Time"></td>';
          }
  //console.log(trav_task_ids+'--'+trav_task_ids.indexOf(travel[i]["travel_task_id"]));
       //console.log(trav_task_ids+'--'+trav_task_ids.indexOf(travel[i]["travel_task_id"]));
  if(travel[i]["travel_task_child_id"]!=null)
  {
    var child_task = travel[i]["travel_task_child_id"].split(",");
    var child_task_name = travel[i]["travel_task_child_name"].split(",");
    if(travel[i]["travel_task_child_date"]!=null)
    {
      var child_task_date = travel[i]["travel_task_child_date"].split(",");
    }
    else{
      var child_task_date = [];
    }
  }
  else{
    var child_task=[];
  }
                  if(jQuery.inArray( travel[i]["travel_task_id"], trav_task_ids )=="-1")
                 {
                  htmls +=  '<td data-th="Task" >';
                  if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                    htmls +=travel[i]["task_name"];
                  }
                  else{
                    htmls +='<a class="popup" data-id="'+travel[i]["travel_task_id"]+'">'+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')</a>';
                  }
                  if(child_task.length>0)
                  {
                  for (var k = 0; k < child_task.length; k++) {
                    htmls +=  '<br>';
                    if(jQuery.inArray( child_task[k], trav_task_ids )=="-1")
                    {
                      if(child_task_name[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +='<a class="popup" data-id="'+child_task[k]+'">'+child_task_name[k]+' ('+child_task_date[k]+')</a>';
                        }
                      }
                    }
                    else{
                      if(child_task_date[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +=child_task_name[k] +'('+child_task_date[k]+')';
                        }
                      }
                    }
                    trav_task_ids.push(child_task[k]);
                  } 
                 }
                  htmls +='</td>';
                 }
                 else{
                //  htmls +=  '<td data-th="Task" >'+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')</td>';
                   htmls +=  '<td data-th="Task" >';
                  htmls +=''+travel[i]["task_name"]+' ('+travel[i]["task_date"]+')';
                  if(child_task.length>0)
                  {
                  for (var k = 0; k < child_task.length; k++) {
                    htmls +=  '<br>';
                    if(jQuery.inArray( child_task[k], trav_task_ids )=="-1")
                    {
                      if(child_task_name[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }else{
                          htmls +='<a class="popup" data-id="'+child_task[k]+'">'+child_task_name[k]+' ('+child_task_date[k]+')</a>';
                        }
                      }
                    }
                    else{
                      if(child_task_date[k]!='')
                      {
                        if(travel[i]["travel_task_id"]=="3446" || travel[i]["travel_task_id"]=="3447"){
                          htmls +=child_task_name[k];
                        }
                        else{
                          htmls +=child_task_name[k] +'('+child_task_date[k]+')';
                        }
                      }
                    }
                    trav_task_ids.push(child_task[k]);
                  } 
                 }
                  htmls +='</td>';
                  }
                if(travel[i]["travel_start_image"]!='' && travel[i]["travel_start_image"]!=null)
                  {
                    var tr_imgs_start="{{asset('public/storage/comment/')}}/"+travel[i]["travel_start_image"];
 htmls +='<td><a href="'+tr_imgs_start+'" download="'+tr_imgs_start+'"><img src="'+tr_imgs_start+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    htmls +='<td></td>';
                  }
                  if(travel[i]["travel_end_image"]!='' && travel[i]["travel_end_image"]!=null)
                  {
                    var tr_imgs_end="{{asset('public/storage/comment/')}}/"+travel[i]["travel_end_image"];
 htmls +='<td><a href="'+tr_imgs_end+'" download="'+tr_imgs_end+'"><img src="'+tr_imgs_end+'" width="50px" height="50px;"/>Download</a></td>';
                  }
                  else{
                    htmls +='<td></td>';
                  }
                 htmls +='</tr>';
               /* if(travel[i]["status"]=="N" || travel[i]["status"]=="Reject")
                 {
                  error_flag=1;
                 }*/
                 trav_task_ids.push(travel[i]["travel_task_id"]);
                 }
                 htmls +='</tbody></table>';
      }
     // $(".travel_sec"+row_no).hide();
                 $(".display_add_data").html(htmls);
    }
  });
  }
  });
}
function change_hospital(row_no)
{
  var user_id=$("#user_id"+row_no).val();
  var url = APP_URL+'/staff/get_hospital_sort_task';
  if(user_id>0)
  {  $("#travel_task_id"+row_no).html('');
$.ajax({
  type: "POST",
  cache: false,
  url: url,
  data:{
    user_id:user_id
  },
  success: function (data)
  {
    var proObj = JSON.parse(data);
    var htmls='';
  //  htmls +='<option value="">Select Task</option>';      
    for (var i = 0; i < proObj.length; i++) {
      htmls +='<option value="'+proObj[i]["id"]+'" attr-name="'+proObj[i]["name"]+'">'+proObj[i]["name"]+' ('+proObj[i]["start_date"]+')</option>';       
    }
    $("#travel_task_id"+row_no).html(htmls);
   // $('#travel_task_id'+row_no).multiselect();
    $('#travel_task_id'+row_no).multiselect('rebuild');
  }
});
}
else{var htmls='';
  htmls +='<option value="3446" attr-name="Going To Office">Going To Office</option>';       
    htmls +='<option value="3447" attr-name="Going To Home">Going To Home</option>';    
    $("#travel_task_id"+row_no).html('');
    $("#travel_task_id"+row_no).html(htmls);
   // $('#travel_task_id'+row_no).multiselect();   
    $('#travel_task_id'+row_no).multiselect('rebuild');
}
}
jQuery(document).ready(function() {
  if ($(window).width() <= 1024) {
  }
  else{
    $(".sidebar-toggle").trigger("click");
  }
        jQuery("#addTaskCommentBtn").click(function() {
         var comment=$("#task_comment").val();
         var contact_id=$("#contact_id").val();
        if($('input[name="email_status"]').is(':checked'))
             {
               var email_status="Y";
             }
             else{
               var email_status="N";
             }
             if($('input[name="call_status"]').is(':checked'))
             {
               var call_status="Y";
             }
             else{
               var call_status="N";
             }
             if($('input[name="visit_status"]').is(':checked'))
             {
               var visit_status="Y";
             }
             else{
               var visit_status="N";
             }
           if(comment!='' && contact_id!='' )
           {
             $(".load-sec").show();
             $("#task_comment_message").hide();
             var task_id=$("#task_id").val();
             var url = APP_URL+'/staff/add_task_comment';
             var formData = new FormData();
                var image_name = $('#image_name').val();
               if(image_name != '') {    
           //    formData.append('image_name',$("#image_name")[0].files[0]);
               var file = document.getElementById('image_name').files[0];
               formData.append('image_name',file);  
               }
              // alert(image_name);
             formData.append('task_id',task_id);  
             formData.append('comment',comment);  
              formData.append('contact_id',contact_id);  
               formData.append('email_status',email_status);  
               formData.append('call_status',call_status);  
               formData.append('visit_status',visit_status);  
           //    console.log(formData)
             $.ajax({
               type: "POST",
           cache: false,
           processData: false,
           contentType: false,
             url: url,
             data:formData,
               success: function (data)
               {  
                 $("#task_comment").val('');  
                 //alert(data);
                 viewall_comments()
               }
             });
           }
           else{ 
             if(comment==""){
               $("#task_comment_message").show();
              }
              if(contact_id==""){
               $("#contact_id_message").show();
              }
           }
         });
 //$(document).on('click',".cmsTabletravel tr",function(){
        }); 
          //jQuery(".popup").click(function() {
            $(document).on('click',".popup",function(){
          var id=$(this).attr("data-id");
         // alert(id)
          $("#task_id").val(id);
          viewall_comments()
          var url = APP_URL+'/staff/view_task_details';
          $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              id:id
            },
            success: function (data)
            {    
              //alert(data);
               var res = data.split("*");
              var proObj = JSON.parse(res[0]);
              var contact_list = JSON.parse(res[1]);
              var travel = JSON.parse(res[2]);
              var expence = JSON.parse(res[3]);
              var day_expence = JSON.parse(res[4]);
              htmls=' ';
              var j=1;
                  $("#taskname").html(proObj[0]);
                  if(proObj[1]!=null){
                      $("#task_view_description").html(proObj[1]);
                    }
                    else{
                      $("#task_view_description").html("No description added");
                    }
                  $("#created_at").html(proObj[2]);
                  $("#start_date").html(proObj[3]);
                  $("#due_date").html(proObj[4]);
                  $("#priority_dis").html(proObj[5]);
                  $("#staff_dis").html(proObj[6]);
                  $("#follower_dis").html(proObj[7]);
                  if(proObj[8]>0)
                    {
                      $("#contact_link"). attr("href", APP_URL+'/staff/customer/'+proObj[8]);
                    }
                    else{
                      $("#contact_link"). attr("href", APP_URL+'/staff/customer/');
                    }
                     var contact_option='';
                    contact_option +='<option value="">Select Contact</option>';
                    for (var j = 0; j < contact_list.length; j++) {
                       contact_option +='<option value="'+contact_list[j]["id"]+'">'+contact_list[j]["name"] +'</option>';
                    }
                 $("#contact_id").html(contact_option);
                 $('#contact_id').multiselect('rebuild');
              $("#myModal").modal("show");
            }
          });
         }); 
 function viewall_comments(){
    var task_id=$("#task_id").val();
    var staff_id=$("#staff_id").val();
    var url = APP_URL+'/staff/view_task_comment';
    $.ajax({
                type: "POST",
                cache: false,
                url: url,
                data:{
                  task_id:task_id
                },
                success: function (data)
                {    
                  var res = data.split("*");
                  var proObj = JSON.parse(res[0]);
                  var proObj_replay = JSON.parse(res[1]);
                  var proObj_task = JSON.parse(res[2]);
                  var follower_id=proObj_task[0]["followers"];
                  htmls=' ';
                  var j=1;
                  var rplays_but = [];
                    for (var i = 0; i < proObj.length; i++) {
                  var imgs="{{asset('public/storage/comment/')}}/"+proObj[i]["image_name"];
                     htmls +='<div class="panel panel-default" id="row'+i+'">'+
                        '<div class="panel-body">';
                         if(proObj[i]["contact_name"]!=null)
                        {
                          htmls +='<p>Contact Person: '+proObj[i]["contact_name"]+'</p>';
                        }
                        htmls += '<p>'+proObj[i]["comment"]+'</p>'+
                        '<p>'+proObj[i]["created_at"]+'</p>';
                        if(proObj[i]["email"]=="Y")
                        {
                          htmls +='<p>Email: Yes</p>';
                        }
                        if(proObj[i]["call_status"]=="Y")
                        {
                          htmls +='<p>Call: Yes</p>';
                        }
                        if(proObj[i]["visit"]=="Y")
                        {
                          htmls +='<p>Visit: Yes</p>';
                        }
                      if(proObj[i]["status"]=="Y"){
                        htmls +='<p style="color:green">Approved</p>';
                      }
                      else if(proObj[i]["status"]=="N"){
                        htmls +='<p style="color:red">Pending</p>';
                      }
                      else if(proObj[i]["status"]=="R"){
                        htmls +='<p style="color:red">Reject</p>';
                      }
                        if(proObj[i]["image_name"]!='')
                        {
                          htmls +='<a href="'+imgs+'" download="'+imgs+'"><img src="'+imgs+'" width="50px" height="50px;"/>Download</a><br>';
                        }
                      /*  if(proObj[i]["added_by_id"]=="1" || proObj[i]["added_by_id"]=="2")
                        {
                          htmls +=  '<p>Added By: <b>'+proObj[i]["added_by"]+'</b></p>';
                        }
                        else if(proObj[i]["added_by_id"]==staff_id){
                          htmls +=  '<p>Added By: <b>Staff</b></p>';
                        }
                        else{
                          htmls +=  '<p>Added By: <b>Follower</b></p>';
                        }*/
                        htmls +=  '<p>Added By: <b>'+proObj[i]["added_by"]+'</b></p>';
                       /* htmls += '<a class="btn btn-danger btn-xs" onClick="delete_task_comment('+proObj[i]["id"]+','+i+');" id="btn_deleteAll" >'+
                        '<span class="glyphicon glyphicon-trash"></span></a>';*/
                      //  if(proObj[i]["added_by"]=='admin' && proObj[i]["status"]=="N")
                      if( proObj[i]["status"]=="N" && staff_id!=proObj[i]["added_by_id"])
                        {
                          if(proObj[i]["quick_task_comment"]=="Y")
                          {
                            if(staff_id==55 || staff_id==29)
                            {
                              var followerids = follower_id.toString().replace(/\,/g, '*');
                             
                              htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+');"  >'+
                        'Reply</a>';
                            }

                          }
                          else{
                            var followerids = follower_id.toString().replace(/\,/g, '*');
                             
                            htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+');"  >'+
                        'Reply</a>';
                          }
                       
                        }
                        if( proObj[i]["status"]=="R" && staff_id!=proObj[i]["added_by_id"])
                        {
                         

                        if(proObj[i]["quick_task_comment"]=="Y")
                          {
                            if(staff_id==55 || staff_id==29)
                            {
                              var followerids = follower_id.toString().replace(/\,/g, '*');
                              htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+');"  >'+
                        'Reply</a>';
                            }

                          }
                          else{
                            var followerids = follower_id.toString().replace(/\,/g, '*');
                            htmls += '<a class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+i+','+follower_id+');"  >'+
                        'Reply</a>';
                          }

                        }
                      htmls += '</div>';
                      for (var j = 0; j < proObj_replay.length; j++) {
                        if(proObj[i]["id"]==proObj_replay[j]["task_comment_id"])
                        {
                          if(staff_id==proObj_replay[j]["added_by_id"])
                          {var p=1;
                          htmls += '<div class="reply-comment staff" >'+
                           ''+proObj_replay[j]["comment"]+''+
                            '<p>'+proObj_replay[j]["created_at"]+'<br/>';
                          /*  if(proObj_replay[j]["added_by_id"]=="1" || proObj_replay[j]["added_by_id"]=="2")
                            {
                              htmls +='<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            }
                            else if(proObj_replay[j]["added_by_id"]==staff_id){
                          htmls +=  '<p>Added By: <b>Staff</b></p>';
                        }
                        else{
                          htmls +=  '<p>Added By: <b>Follower</b></p>';
                        }*/
                        htmls +='<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            htmls +='</p>'+
                          '</div>';
                          }else{var p=2;
                            htmls += '<div class="reply-comment admin"  >';
                            if(proObj_replay[j]["comment"]!=null)
                        {
                          htmls +=proObj_replay[j]["comment"];
                        }
                        htmls +=  '<p>'+proObj_replay[j]["created_at"]+'<br/>';
                           /* if(proObj_replay[j]["added_by_id"]=="1" || proObj_replay[j]["added_by_id"]=="2")
                            {
                              htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            }
                              else if(proObj_replay[j]["added_by_id"]==staff_id){
                            htmls +=  '<p>Added By: <b>Staff</b></p>';
                          }
                          else{
                            htmls +=  '<p>Added By: <b>Follower</b></p>';
                          }*/
                          htmls +=  '<p>Added By: <b>'+proObj_replay[j]["added_by"]+'</b></p>';
                            if(proObj[i]["status"]=="N"  && proObj_replay[j]["replay_status"]=="N" && staff_id!=proObj_replay[j]["added_by_id"]){

 htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                             /* if(proObj[i]["quick_task_comment"]=="Y")
                                {
                                    if(staff_id==55 || staff_id==29)
                                    {
                                      var followerids = follower_id.toString().replace(/\,/g, '*');
                                      htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                                    }

                                }
                                else{
                                  var followerids = follower_id.toString().replace(/\,/g, '*');
                                  htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                                }*/
                             
                            }
                            if(proObj[i]["status"]=="R"  && proObj_replay[j]["replay_status"]=="N" && staff_id!=proObj_replay[j]["added_by_id"]){
                              console.log(111);
                              /* if(proObj[i]["quick_task_comment"]=="Y")
                                {
                                    if(staff_id==55 || staff_id==29)
                                    {
                                      var followerids = follower_id.toString().replace(/\,/g, '*');
                                      htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                                    }

                                }
                                else{
                                  var followerids = follower_id.toString().replace(/\,/g, '*');
                                  htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                                }*/
                                htmls += ' <a id="replay_but_'+j+'" class="btn btn-success btn-xs" onClick="replay_task_comment('+proObj[i]["id"]+','+proObj_replay[j]["id"]+','+follower_id+');"  >Reply</a>';
                             
                            }
                              htmls +=   '</p>'+
                          '</div>';
                          rplays_but.push(j);
                          }
                        }
                      }
                        htmls +='</div>';
                      j++;
                    }
                    $(".res_ajax").html(htmls);
                  
                 /*   for(k=0;k<rplays_but.length;k++)
                    {
                      var rowno=rplays_but[k];
                      $("#replay_but_"+rowno).hide();
                    }*/

                    var lastEl = rplays_but[rplays_but.length-1];
                    console.log('len'+rplays_but.length+'lastEl'+lastEl)
                    // if(p==1){
                    //   $("#replay_but_"+lastEl).show();
                    // }
                   // $("#replay_but_"+lastEl).show();
                    $(".load-sec").hide();
                }
              });
  }
    function replay_task_comment(id,replay_id,follower_id,follower_id_new){
$("#replay_modal").modal("show");
$("#task_comment_id").val(id);
$("#parent_id").val(replay_id);
var staff_id=$("#staff_id").val();


// if ($.isNumeric(follower_id)) {
//   var follower_ids=follower_id.split("*");
// console.log(folowerids.length)
// }
if(follower_id_new>0)
{
  $("#follower").val(follower_id);
  $("#follower1").val(follower_id_new);
  if(staff_id==follower_id || staff_id==follower_id_new){
    $(".status-sec").show();
    }
    else{
      $(".status-sec").hide();
    }

}
else{
  $("#follower").val(follower_id);
  $("#follower1").val(follower_id_new);
    if(staff_id==follower_id){
    $(".status-sec").show();
    }
    else{
      $(".status-sec").hide();
    }
}


}
 function add_replay_comment()
{
  var task_id=$("#task_id").val();
  var task_comment_id=$("#task_comment_id").val();
  var replay_comment=$("#replay_comment").val();
  var follower=$("#follower").val();
  var follower1=$("#follower1").val();
  var staff_id=$("#staff_id").val();
if(follower1>0)
{
  if(follower==staff_id || follower1==staff_id){
    var status = $("input[name='status_replay']:checked").val();
  }else{
    var status = "N";
  }

}
else{

if(follower==staff_id){
    var status = $("input[name='status_replay']:checked").val();
  }else{
    var status = "N";
  }


}
  
  var parent_id=$("#parent_id").val();
  var url = APP_URL+'/staff/add_task_replay_comment';
    $.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        parent_id:parent_id,task_id:task_id,task_comment_id:task_comment_id,replay_comment:replay_comment,status:status
      },
      success: function (data)
      {  
        $("#replay_modal").modal("hide");
        viewall_comments()
      //  window.location.href=APP_URL+'/staff/inprogressTask';
      }
    });
}
function clock()
{
	//var monthArray = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
	var monthArray = new Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
	//var dayArray	= new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
	var dayArray	= new Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
	var thetime=new Date();
	var nhours=thetime.getHours();
	var nmins=thetime.getMinutes();
	var nsecn=thetime.getSeconds();
	var nday=thetime.getDay();
	var nmonth=thetime.getMonth();
	var ntoday=thetime.getDate();
	var nyear=thetime.getYear();
	var AorP=" ";
	if (nhours>=12)
		AorP="P.M";
	else
		AorP="A.M";
	if (nhours>=13)
		nhours-=12;
	if (nhours==0)
	   nhours=12;
	if (nsecn<10)
	 nsecn="0"+nsecn;
	if (nmins<10)
	 nmins="0"+nmins;
	nday	= dayArray[nday];
	nmonth	= monthArray[nmonth];
	if (nyear<=99)
	  nyear= "19"+nyear;
	if ((nyear>99) && (nyear<2000))
	 nyear+=1900;
	//document.getElementById('clock').innerHTML=nhours+":"+nmins+":"+nsecn+" "+AorP+" "+nday+", "+ntoday+"-"+nmonth+"-"+nyear;
  document.getElementById('end_time0').innerHTML= nday + ", " + nmonth + " " + ntoday + ", " + nyear + " " + nhours+":"+nmins+":"+nsecn+" "+AorP;
  var type_field=$("#time_dis_id").val();
	$(type_field).val(nhours+":"+nmins);
	setTimeout('clock()',1000);
}
</script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />
<script>
$(document).ready(function() {
   $(".open_gif").hide();
    $('#contact_id').multiselect({
    });
    $('#office_task_id').multiselect({
      enableFiltering: true,
    });
    $('.task_sec').multiselect({
      enableFiltering: true,
    });
});
</script>
  <script>
    $('.user_id').select2();
    </script>
<style>
.panel
{
  cursor:pointer;
}
</style>
@endsection