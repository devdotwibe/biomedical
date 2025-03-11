@extends('staff/layouts.app')

@section('title', 'Edit Service task')

@section('content')

<section class="content-header">
      <h1>
        Edit 
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.service_task.index')}}">Manage Service task</a></li>
        <li class="active">Edit </li>
      </ol>
    </section>

<section class="content">
      <div class="row">

        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">

            <!-- /.box-header -->
            <!-- form start -->

            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif


            @if(session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session()->get('error_message') }}
                </div>
            @endif

            <p class="error-content alert-danger">
            {{ $errors->first('name') }}
            </p>

            <form role="form" autocomplete="off" name="frm_responce" id="frm_responce" method="post" action="{{ route('staff.service_task.update', $service_task->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
                


                  <div class="box-body">

            <div class="form-group col-md-12 border">
            <!-- <label for="name">Service</label> -->
            <select id="service" name="service" class="form-control" onchange="service_change()">
            <option value="service_task">Service Activities</option>
            <option value="first_res" >First Responce</option>
            <option value="visit" >Visit</option>
            <option value="part" >Part Intent</option>
           
            <option value="quote" >Quote</option>

            </select>
          
            </div>



<!-- Service task start -->
<div class="box-body service_task">
<h3>Service Activities</h3>
<div class="form-group col-md-6">
 <label for="name">Service Reference No*</label>

 <input type="text" id="service_ref_no" disabled="true" name="service_ref_no" value="{{ $service_task->service_ref_no}}" class="form-control" placeholder="Service Reference No">
 <input type="hidden" id="service_ref_no_val"  name="service_ref_no_val" value="{{ $service_task->service_ref_no}}" class="form-control">
 <span class="error_message" id="service_ref_no_message" style="display: none">Field is required</span>
</div> 

 <div class="form-group col-md-6">
 <label for="name">Start Date*</label>
 <input type="text" id="start_date" name="start_date" value="{{ $service_task->start_date}}" class="form-control" placeholder="Start Date">
 <span class="error_message" id="start_date_message" style="display: none">Field is required</span>
</div>
<div class="form-group col-md-6">
 <label for="name">End Date*</label>
 <input type="text" onchange="change_date()" id="end_date" name="end_date" value="{{ $service_task->end_date }}" class="form-control" placeholder="End Date">
 <span class="error_message" id="end_date_message" style="display: none">Field is required</span>
</div>
<div class="form-group col-md-6">
 <label for="name">Schedule Date*</label>
 <input type="text" onchange="change_date()" id="schedule_date" name="schedule_date" value="{{ $service_task->schedule_date }}" class="form-control" placeholder="Schedule Date">
 <span class="error_message" id="schedule_date_message" style="display: none">Field is required</span>
</div>

  

 <div class="form-group col-md-6">
  <label for="name">Service For Other Reference</label>
  <input type="text" id="other_ref" name="other_ref" value="{{ $service_task->other_ref }}" class="form-control" placeholder="Other Reference">
  <span class="error_message" id="other_ref_message" style="display: none">Field is required</span>
</div>

  <div class="form-group col-md-6">
  <label for="name">Service Status </label>
  <select id="service_status" name="service_status" class="form-control" onchange="change_service_status()">
  <option value="">Select Service Status </option>
  <option value="Open" <?php echo ($service_task->service_status == "Open") ? 'selected': ''?>>Open</option>
<option value="Closed" <?php echo ($service_task->service_status == "Closed") ? 'selected': ''?>>Closed</option>
<option value="Visit" <?php echo ($service_task->service_status == "Visit") ? 'selected': ''?>>Visit</option>
  </select>
  <span class="error_message" id="service_status_message" style="display: none">Field is required</span>
</div>

 <div class="form-group col-md-12 next_sch_date" <?php if($service_task->service_status=="Visit" && $service_task->service_status!="") { ?>style="display:block;" <?php }else{ ?> style="display:none;" <?php } ?>>
    <label for="name">Next Schedule Date</label>
    <input type="text" id="next_schedule_date" name="next_schedule_date" value="{{ $service_task->next_schedule_date }}" class="form-control" placeholder="Next Schedule Date">
    <span class="error_message" id="next_schedule_date_message" style="display: none">Field is required</span>
  </div>
  


<div class="form-group col-md-6">
 <label for="name">Job Type*</label>
 <select id="job_type" name="job_type" class="form-control">
 <option value="">Select Job Type</option>
 <option value="CR" <?php echo ($service_task->job_type == "CR") ? 'selected': ''?>>CR</option>
 <option value="PM" <?php echo ($service_task->job_type == "PM") ? 'selected': ''?>>PM</option>
 <option value="Instalation" <?php echo ($service_task->job_type == "Instalation") ? 'selected': ''?>>Instalation</option>
<option value="FMI" <?php echo ($service_task->job_type == "FMI") ? 'selected': ''?>>FMI</option>
<option value="Data Collection" <?php echo ($service_task->job_type == "Data Collection") ? 'selected': ''?>>Data Collection</option>
<option value="Others"<?php echo ($service_task->job_type == "Others") ? 'selected': ''?> >Others</option>
<option value="MSA Followup" <?php echo ($service_task->job_type == "MSA Followup") ? 'selected': ''?> >MSA Followup</option>


 </select>
 <span class="error_message" id="job_type_message" style="display: none">Field is required</span>
</div>

<!-- <div class="form-group">
 <label for="name">Other Reference*</label>
 <textarea id="other_ref" name="other_ref"  class="form-control" placeholder="Other Reference">{{ $service_task->other_ref }}</textarea>
 <span class="error_message" id="other_ref_message" style="display: none">Field is required</span>
</div> -->

<div class="form-group col-md-6">
 <label for="name">State*</label>
 <select id="state_id" name="state_id" class="form-control" onchange="change_state()"> 
 <option value="">Select State</option>
 @foreach($state as $values)
 <?php
  $sel = ($service_task->state_id == $values->id) ? 'selected': '';
 
 echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
 ?>

 @endforeach
 </select>
 <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
</div>

<div class="form-group  col-md-6">
 <label for="name">District*</label>
 <select id="district_id" name="district_id" class="form-control" onchange="change_district()">
 <option value="">Select District</option>
 @foreach($district as $values)
 <?php
 $sel = ($service_task->district_id == $values->id) ? 'selected': '';
 
 echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
 ?>
 <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
 @endforeach
 </select>
 <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
</div>



<div class="form-group col-md-6">
 <label for="name">Customer Name*</label>
 <select id="user_id" name="user_id" class="form-control" onchange="change_user()">
 <option value="">Select Customer Name</option>
 @foreach($user as $values)
 <?php
 $sel = ($service_task->user_id == $values->id) ? 'selected': '';
 
 echo '<option value="'.$values->id.'" '.$sel.'>'.$values->business_name.'</option>';
 ?>
 <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
 @endforeach
 </select>
 <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
</div>

<div class="form-group col-md-12">
 <label for="name">Contact Person* <a  id="contact_link" href="{{route('staff.customer.index')}}/{{$service_task->user_id}}" target='_blank'>Add contact</a></label>
 <select id="contact_id" name="contact_id" class="form-control" onchange="change_contact_person()">
 <option value="">Select Contact Person</option>
 @foreach($contact_person as $values)
 <?php

  $sel = ($service_task->contact_id == $values->id) ? 'selected': '';

 echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
 ?>

 @endforeach
 </select>
 <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
</div>



<div class="form-group col-md-12">
 <label for="name">Product Description*</label>
 <select id="product_id" name="product_id" class="form-control">
 <option value="">Select Product Description</option>
 @foreach($product as $values)
 <?php
   $sel = ($service_task->product_id == $values->id) ? 'selected': '';

 echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
 ?>

 @endforeach
 </select>

 <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
</div>

<div class="form-group col-md-6">
                  <label for="name">Customer Equipment Status</label>
                  <select id="cus_eq_status" name="cus_eq_status" class="form-control" onchange="change_state()"> 
                  <option value="">Select Customer Equipment Status</option>
                <option value="Systen Use Or Not" <?php echo ($service_task->cus_eq_status == "Systen Use Or Not") ? 'selected': ''?> >Systen Use Or Not</option>
                 
                  </select>
                  <span class="error_message" id="cus_eq_status_message" style="display: none">Field is required</span>
                </div>


<div class="form-group col-md-6">
 <label for="name">Serial Number*</label>
 <select id="serial_no" name="serial_no" class="form-control">
 <option value="">Select Serial Number</option>

 </select>
 <span class="error_message" id="serial_no_message" style="display: none">Field is required</span>
</div>

 
<div class="form-group col-md-12">
                  <label for="name">Owner*</label>
                  <select id="staff_id" name="staff_id" class="form-control" onchange="change_state()"> 
                  <option value="">Select Owner</option>
                  @foreach($staff as $values)
                  <?php
   $sel = ($service_task->staff_id == $values->id) ? 'selected': '';

 echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
 ?>
                 
                  @endforeach
                  </select>
                  <span class="error_message" id="staff_id_message" style="display: none">Field is required</span>
                </div>


<div class="form-group col-md-6">
 <label for="name">Contact Number*</label>
 <input type="text" disabled="true" id="contact_no" name="contact_no" value="{{ $service_task->contact_no }}" class="form-control" placeholder="Contact Number">
 <input type="hidden" id="contact_no_val" name="contact_no_val" value="{{ $service_task->contact_no }}">
 <span class="error_message" id="contact_no_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-6">
 <label for="name">Email*</label>
 <input type="text" disabled="true" id="email" name="email" value="{{ $service_task->email }}" class="form-control" placeholder="Email">
 <input type="hidden" id="email_val" name="email_val" value="{{ $service_task->email }}">
 <span class="error_message" id="email_message" style="display: none">Field is required</span>
</div>


  <div class="form-group col-md-12">
  <label for="name">Problem Reported</label>
  <textarea id="problem_reported" name="problem_reported"  class="form-control" placeholder="Problem Reported">{{ $service_task->problem_reported }}</textarea>
  <span class="error_message" id="problem_reported_message" style="display: none">Field is required</span>
</div>

  <div class="form-group col-md-12">
  <label for="name">Problem Description</label>
  <textarea id="prob_desc" name="prob_desc"  class="form-control" placeholder="Problem Description">{{ $service_task->prob_desc}}</textarea>
  <span class="error_message" id="prob_desc_message" style="display: none">Field is required</span>
</div>

  <div class="box-footer">
   <button type="button" class="btn btn-primary"  onclick="validate_from()">Update</button>
   <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.service_task.index')}}'">Cancel</button>
 </div>
          

</div>
</form>
<!-- Service task end -->


<!-- First Responce start -->
<div class="box-body first_responce"  style="display:none;">
<form role="form" autocomplete="off" name="frm_firresponce" id="frm_firresponce" method="post"  enctype="multipart/form-data" >
               @csrf
<h3>First Responce</h3>
<div class="form-group col-md-6">
<label for="name">Date*</label>
<input type="text" id="resp_date" name="resp_date" value="{{ old('resp_date')}}" class="form-control" placeholder="Date">
<span class="error_message" id="resp_date_message" style="display: none">Field is required</span>
</div>



<div class="form-group col-md-6">
<label for="name">Contact Person*<a  id="contact_link_visit" href="{{route('staff.customer.index')}}/{{$service_task->user_id}}" target='_blank'>Add contact</a></label>
<input type="hidden" name="resp_contact_id_val" id="resp_contact_id_val" value="{{$service_task->contact_id}}">
<select id="resp_contact_id" name="resp_contact_id" class="form-control" disabled="true">
<option value="">Select Contact Person</option>
@foreach($contact_person as $values)
<?php

$sel = ($service_task->contact_id == $values->id) ? 'selected': '';

echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
?>

@endforeach
</select>
<span class="error_message" id="resp_contact_id_message" style="display: none">Field is required</span>
</div>

<div class="form-group col-md-6">
<label for="name">Contact Number*</label>
<input type="hidden" name="resp_contact_no_val" id="resp_contact_no_val" value="{{$service_task->contact_no}}">
<input type="hidden" name="resp_service_task_id" id="resp_service_task_id" value="{{$service_task->id}}">
<input type="text" id="resp_contact_no" disabled="true" name="resp_contact_no" value="{{ $service_task->contact_no  }}" class="form-control" placeholder="Contact Number">
<span class="error_message" id="resp_contact_no_message" style="display: none">Field is required</span>
</div>



<div class="form-group col-md-6">
<label for="name">Responce</label>
<input type="text" id="resp_responce" name="resp_responce" value="{{ old('resp_responce')}}" class="form-control" placeholder="Responce">
<span class="error_message" id="resp_responce_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-12">
<label for="name">Status*</label>
<select id="resp_status" name="resp_status" class="form-control">
<option value="">Select Status</option>

<option value="Closed" >Closed</option>
<option value="Not Closed - Warranty part" >Not Closed - Warranty part</option>
<option value="Not Closed - Part Quote Needed" >Not Closed - Part Quote Needed</option>
<option value="Not in warranty or Contact - Service Quote" >Not in warranty or Contact - Service Quote</option>
<option value="Not in warranty or Contact - Free Service" >Not in warranty or Contact - Free Service</option>

</select>
<span class="error_message" id="resp_status_message" style="display: none">Field is required</span>
</div>

<div class="form-group">
<label for="name">Action Plan</label>
<textarea id="resp_action_plan" name="resp_action_plan"  class="form-control" placeholder="Action Plan">{{ old('resp_action_plan')}}</textarea>
<span class="error_message" id="resp_action_plan_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-6">
<label for="name">Schedule Date*</label>
<input type="text" id="resp_schedule_date" name="resp_schedule_date" value="{{ old('resp_schedule_date')}}" class="form-control" placeholder="Schedule Date">
<span class="error_message" id="resp_schedule_date_message" style="display: none">Field is required</span>
</div>

<div class="form-group col-md-6">
<label for="name">Schedule Time*</label>
<input type="text" id="resp_schedule_time" name="resp_schedule_time" value="{{ old('resp_schedule_time')}}" class="form-control" placeholder="Schedule Time">
<span class="error_message" id="resp_schedule_time_message" style="display: none">Field is required</span>
</div>



<div class="box-footer">
<input type="hidden" name="service_responce_id" id="service_responce_id" value="">
  <button type="button" class="btn btn-primary" id="firstres_btn" onclick="add_firstresponce()">Save</button>
    <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.service_task.index')}}'">Cancel</button>
</div>

</form>





</div>
<!-- First Responce end -->

<!-- visit start -->
<div class="box-body visit"  style="display:none;">
<form role="form" autocomplete="off" name="frm_visit" id="frm_visit" method="post"  enctype="multipart/form-data" >
               @csrf
<h3>Visit</h3>


<div class="form-group col-md-6">
 <label for="name">Travel Start Time*</label>
 <input type="text" id="travel_start_time" name="travel_start_time" value="{{ old('travel_start_time')}}" class="form-control" placeholder="Travel Start Time">
 <span class="error_message" id="travel_start_time_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-6">
 <label for="name">Travel End Time</label>
 <input type="text" id="travel_end_time" name="travel_end_time" value="{{ old('travel_end_time')}}" class="form-control" placeholder="Travel End Time">
 <span class="error_message" id="travel_end_time_message" style="display: none">Field is required</span>
</div>



<div class="form-group col-md-6">
 <label for="name">Work start date and time*</label>
 <input type="text" id="work_start" name="work_start" value="{{ old('work_start')}}" class="form-control" placeholder="Work start date and time">
 <span class="error_message" id="work_start_message" style="display: none">Field is required</span>
</div>

<div class="form-group col-md-6">
 <label for="name">Work complete date and time</label>
 <input type="text" id="work_end" name="work_end" value="{{ old('work_end')}}" class="form-control" placeholder="Work complete date and time">
 <span class="error_message" id="work_end_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-6">
 <label for="name">Observed Problem</label>
 <input type="text" id="observed_prob" name="observed_prob" value="{{ old('observed_prob')}}" class="form-control" placeholder="Observed Problem">
 <span class="error_message" id="observed_prob_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-6">
 <label for="name">Action Taken</label>
 <input type="text" id="visit_action_taken" name="visit_action_taken" value="{{ old('visit_action_taken')}}" class="form-control" placeholder="Action Taken">
 <span class="error_message" id="visit_action_taken_message" style="display: none">Field is required</span>
</div>




<div class="form-group col-md-12">
 <label for="name">Tests & Calibration Done and Equipment Status</label>
 <input type="text" id="test_status" name="test_status" value="{{ old('test_status')}}" class="form-control" placeholder="Tests & Calibration Done and Equipment Status">
 <span class="error_message" id="test_status_message" style="display: none">Field is required</span>
</div>



<div class="form-group col-md-6">
 <label for="name">Status*</label>
 <select id="visit_status" name="visit_status" class="form-control">
 <option value="">Select Status</option>

<option value="Closed" >Closed</option>
<option value="Not Closed - Warranty part" >Not Closed - Warranty part</option>
<option value="Not Closed - Part Quote Needed" >Not Closed - Part Quote Needed</option>
<option value="Not in warranty or Contact - Service Quote" >Not in warranty or Contact - Service Quote</option>
<option value="Not in warranty or Contact - Free Service" >Not in warranty or Contact - Free Service</option>

 </select>
 <span class="error_message" id="visit_status_message" style="display: none">Field is required</span>
</div>

<div class="form-group col-md-6">
 <label for="name">Action Plan*</label>
 <textarea id="visit_action_plan" name="visit_action_plan"  class="form-control" placeholder="Action Plan">{{ old('visit_action_plan')}}</textarea>
 <span class="error_message" id="visit_action_plan_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-6">
 <label for="name">Return start travel date and time</label>
 <input type="text" id="return_travel_start_time" name="return_travel_start_time" value="{{ old('return_travel_start_time')}}" class="form-control" placeholder="Return start travel date and time">
 <span class="error_message" id="return_travel_start_time_message" style="display: none">Field is required</span>
</div>

<div class="form-group col-md-6">
 <label for="name">Return end travel date and time</label>
 <input type="text" id="return_travel_end_time" name="return_travel_end_time" value="{{ old('return_travel_end_time')}}" class="form-control" placeholder="Return end travel date and time">
 <span class="error_message" id="work_end_message" style="display: none">Field is required</span>
</div>




<div class="form-group col-md-12">
 <label for="name">Report Upload*</label>
 <input type="file" id="report_upload" name="report_upload" />
 <input type="hidden" id="current_image" name="current_image" value=""/>
 <p class="help-block">(Allowed Image Type: jpg,jpeg,png,pdf )</p>
 <span class="error_message" id="report_upload_message" style="display: none">Field is required</span>
</div>


<div class="box-footer">
<input type="hidden" name="service_visit_id" id="service_visit_id" value="">
<input type="hidden" name="resp_service_task_id" id="resp_service_task_id" value="{{$service_task->id}}">
  <button type="button" class="btn btn-primary" onclick="add_visit()">Save</button>
    <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.service_task.index')}}'">Cancel</button>
</div>

</form>




</div>
<!-- visit end -->

<!-- Part Intent start -->
<div class="box-body intent_start" style="display:none;">
<form role="form" autocomplete="off" name="frm_part" id="frm_part" method="post"  enctype="multipart/form-data" >
               @csrf
<h3>Part Intent</h3>


<div class="form-group col-md-12">
 <label for="name">Part Number* &nbsp;<a   href="{{url('staff/products/create')}}" target='_blank'>Add Product</a></label>
 <select id="part_no" name="part_no" class="form-control">
 <option value="">Select Part Number</option>
 @foreach($product_verify as $values)
 <?php
   $sel = ($service_task->product_id == $values->id) ? 'selected': '';

 echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
 ?>

 @endforeach
 </select>

 <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-12">
<label for="name">Part Description</label>
<textarea id="part_description" name="part_description"  class="form-control" placeholder="Part Description">{{ old('description')}}</textarea>
<span class="error_message" id="part_description_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-12">
<label for="name">Remark*</label>
<textarea id="description" name="description"  class="form-control" placeholder="Remark">{{ old('description')}}</textarea>
<span class="error_message" id="description_message" style="display: none">Field is required</span>
</div>

<div class="form-group col-md-6">
<label for="name">Intened Date </label>
<input type="text" id="intened_date" name="intened_date" value="{{ old('intened_date')}}" class="form-control" placeholder="Intened Date">
<span class="error_message" id="intened_date_message" style="display: none">Field is required</span>
</div>



<div class="form-group col-md-6">
<label for="name">Expected Date of Arrival</label>
<input type="text" id="expect_date" name="expect_date" value="{{ old('expect_date')}}" class="form-control" placeholder="Expected Date of Arrival">
<span class="error_message" id="expect_date_message" style="display: none">Field is required</span>
</div>



<div class="form-group col-md-6">
<label for="name">Reference</label>
<input type="text" id="part_reference" name="part_reference" value="{{ old('part_reference')}}" class="form-control" placeholder="Reference">
<span class="error_message" id="part_reference_message" style="display: none">Field is required</span>
</div>


<div class="form-group col-md-6">
<label for="name">Action Plan</label>
<textarea id="part_action_plan" name="part_action_plan"  class="form-control" placeholder="Action Plan">{{ old('part_action_plan')}}</textarea>
<span class="error_message" id="part_action_plan_message" style="display: none">Field is required</span>
</div>

<div class="box-footer">
<input type="hidden" name="service_part_id" id="service_part_id" value="">
<input type="hidden" name="resp_service_task_id" id="resp_service_task_id" value="{{$service_task->id}}">
  <button type="button" class="btn btn-primary" onclick="add_part()">Add</button>
    <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.service_task.index')}}'">Cancel</button>
</div>

</form>



</div>
<!-- Part Intent end -->


<!-- Quote start -->




<div class="box-body quotesec" style="display:none;">
<form role="form" autocomplete="off" name="frm_quote" id="frm_quote" method="post"  enctype="multipart/form-data" >
               @csrf
               <div class="pull-left">

<a class="btn btn-sm btn-success" href=""> <span class="glyphicon glyphicon-plus"></span>Add Quote</a>

</div> 
<br>
<!-- <h3>Quote</h3> -->

 <table id="quoteTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Product Name</th>
                  <th>Company Name</th>
                  <th>Date</th>
                  <th>Action</th>
               
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($quote as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="subcategory">
                        <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td>
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td><?php echo $product->user_name ?></td>
                         <td><?php echo $product->user_email ?></td>
                      
                         <td><?php echo $product->product_name ?></td>
                        <td>
                           @if(!is_null($product->company_id))
                              {{$product->company->name}}
                            @endif
                         </td>
                        <td>{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td>
                        <td class="alignCenter">
                        <a class="btn btn-sm btn-success" target="_blank" href="{{ route('staff.quotepdf',$product->id) }}"> <span class="glyphicon "></span>Preview</a>
                    
                        </td>
                     
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($user) > 0) { ?>
              <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('user');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a>
              </div>
               <?php } ?>

              </table>



</form>



</div>
<!-- Quote end -->


<div id="responceTable-sec" style="display:none;">
<table id="responceTable" class="table table-bordered table-striped data-" >
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Activty Type</th>
                  <th>Date</th>
                  <th>Time</th>
                  <th>Status</th>
                 <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody id="responce_sec">
                <?php $i=1;?>
                @if(count($service_responce)>0)
                @foreach($service_responce as $values)
                <tr data-from ="service_responce" id="tr_{{$values->id}}" data-id="{{$values->id}}">
                <td>{{$i}}</td>
                <td>First Responce</td>
                  <td>{{$values->schedule_date}}</td>
                  <td>{{$values->schedule_time}}</td>
                  <td>{{$values->status}}</td>
                 <td class="alignCenter"> 
                 <a class="btn btn-primary btn-xs" onclick="edit_first_responce({{$values->id}})"  title="Edit">Edit</span></a>
                 <a class="btn btn-danger btn-xs deleteItem"  href=""  id="deleteItem{{$values->id}}" data-tr="tr_{{$values->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                 </td>
                 </tr>
                 <?php
                 $i++; 
                 ?>
                @endforeach
                @endif
                @if(count($service_visit)>0)
                @foreach($service_visit as $values)
                <tr data-from="service_visit" id="tr_{{$values->id}}" data-id="{{$values->id}}">
                <td>{{$i}}</td>
                <td>Visit</td>
                  <td>{{$values->travel_start_time}}</td>
                  <td>{{$values->travel_end_time}}</td>
                  <td>{{$values->status}}</td>
                  
                 <td class="alignCenter"> 
                 <a class="btn btn-primary btn-xs" onclick="edit_visit({{$values->id}})"  title="Edit">Edit</span></a>
                 <a class="btn btn-danger btn-xs deleteItem"  href=""  id="deleteItem{{$values->id}}" data-tr="tr_{{$values->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                 </td>
                 </tr>
                 <?php
                 $i++; 
                 ?>
                @endforeach
                @endif

                 @if(count($service_part)>0)
                @foreach($service_part as $values)
                <tr data-from="service_part" id="tr_{{$values->id}}" data-id="{{$values->id}}">
                <td>{{$i}}</td>
                  <td>Part Intent</td>
                 
                  <td>{{$values->intened_date}}</td>
                  <td>{{$values->expect_date}}</td>
                  <td></td>
                 <td class="alignCenter"> 
                 <a class="btn btn-primary btn-xs" onclick="edit_part({{$values->id}})"  title="Edit">Edit</span></a>
                 <a class="btn btn-danger btn-xs deleteItem"  href=""  id="deleteItem{{$values->id}}" data-tr="tr_{{$values->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                 </td>
                 </tr>
                 <?php
                 $i++; 
                 ?>
                @endforeach
                @endif

                @if(count($service_responce)==0)
                <td colspan="5">No Result</td>
                @endif
                </tbody>
   </table>
   </div>


</div>
<!-- /.box-body -->

              <!-- /.box-body -->

              
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')
<script type="text/javascript">

 function change_service_status()
    {
      var service_status=$("#service_status").val();
      if(service_status=="Visit")
      {
        $(".next_sch_date").show();
      }
      else{
        $(".next_sch_date").hide();
      }
    }

function deleteItemajax()
{
  var id= $(".deleteItemajax").attr('id');
        var url = $('.deleteItemajax').attr('data-link');
        $('#btnDeleteItem').attr('data-id', id);
        $('#btnDeleteItem').attr('data-href', url);
        $('#modalDelete').modal();        
        return false;
}

function deleteItemajaxvisit()
{
  var id= $(".deleteItemajaxvisit").attr('id');
        var url = $('.deleteItemajaxvisit').attr('data-link');
        $('#btnDeleteItem').attr('data-id', id);
        $('#btnDeleteItem').attr('data-href', url);
        $('#modalDelete').modal();        
        return false;
}

function deleteItemajaxpart()
{
  var id= $(".deleteItemajaxpart").attr('id');
        var url = $('.deleteItemajaxpart').attr('data-link');
        $('#btnDeleteItem').attr('data-id', id);
        $('#btnDeleteItem').attr('data-href', url);
        $('#modalDelete').modal();        
        return false;
}
     jQuery(document).ready(function() {

         


        var oTable = $('#visitTable').DataTable({
        });
        var oTable = $('#responceTable').DataTable({
        });
        var oTable = $('#quoteTable').DataTable({
        });
        
          var oTable = $('#partTable').DataTable({
        });

        });


  function change_contact_person(){
   var contact_id=$("#contact_id").val();
   var url = APP_URL+'/staff/get_contact_details';
    $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:{
             contact_id: contact_id,
           },
           success: function (data)
           {    
             var proObj = JSON.parse(data);
            console.log(proObj);
               $("#email").val(proObj[0]["email"]);
               $("#contact_no").val(proObj[0]["mobile"]);
               
               $("#email_val").val(proObj[0]["email"]);
               $("#contact_no_val").val(proObj[0]["mobile"]);
           }
         });
 
   }
 
 
  function change_state(){
   var state_id=$("#state_id").val();
   var url = APP_URL+'/staff/change_state';
    $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:{
             state_id: state_id,
           },
           success: function (data)
           {    
             var proObj = JSON.parse(data);
             states_val='';
             states_val +='<option value="">Select District</option>';
             for (var i = 0; i < proObj.length; i++) {
              
               states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
            
               }
               $("#district_id").html(states_val);
              
           }
         });
 
   }
 
   function change_service_status()
    {
      var service_status=$("#service_status").val();
      if(service_status=="Visit")
      {
        $(".next_sch_date").show();
      }
      else{
        $(".next_sch_date").hide();
      }
    }

   function change_district(){
   var district_id=$("#district_id").val();
   var state_id=$("#state_id").val();
   var url = APP_URL+'/staff/get_client_use_state_district';
    $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:{
             district_id: district_id,state_id: state_id
           },
           success: function (data)
           {    
             var proObj = JSON.parse(data);
             states_val='';
             states_val +='<option value="">Select Customer</option>';
             for (var i = 0; i < proObj.length; i++) {
              
               states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
            
               }
               $("#user_id").html(states_val);
              
           }
         });
 
   }
 
   
   function change_user(){
   var user_id=$("#user_id").val();
   if(user_id>0)
   {
     $("#contact_link").show();
   }
   else{
     $("#contact_link").hide();
   }
   
   $("#contact_link").attr("href", APP_URL+'/staff/customer/'+user_id);
   var url = APP_URL+'/staff/get_user_contact_list';
    $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:{
             user_id: user_id
           },
           success: function (data)
           {    
             var proObj = JSON.parse(data);
             states_val='';
             states_val +='<option value="">Select Contact Personn</option>';
             for (var i = 0; i < proObj.length; i++) {
              
               states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
            
               }
               $("#contact_id").html(states_val);
              
               
            
           }
         });
 
   }
 
 
 
   
       function validate_from()
       {
         var start_date=$("#start_date").val();
         var end_date=$("#end_date").val();
         var schedule_date=$("#schedule_date").val();
         var job_type=$("#job_type").val();
         var state_id=$("#state_id").val();
         var district_id=$("#district_id").val();
         var user_id=$("#user_id").val();
         var contact_id=$("#contact_id").val();
         var product_id=$("#product_id").val();
         var staff_id=$("#staff_id").val();
         
         if(start_date=="")
         {
           $("#start_date_message").show();
         }
         else{
           $("#start_date_message").hide();
         }
 
         if(end_date=="")
         {
           $("#end_date_message").show();
         }
         else{
           $("#end_date_message").hide();
         }
 
         if(schedule_date=="")
         {
           $("#schedule_date_message").show();
         }
         else{
           $("#schedule_date_message").hide();
         }
 
           if(job_type=="")
         {
           $("#job_type_message").show();
         }
         else{
           $("#job_type_message").hide();
         }
 
         if(state_id=="")
         {
           $("#state_id_message").show();
         }
         else{
           $("#state_id_message").hide();
         }
 
         if(district_id=="")
         {
           $("#district_id_message").show();
         }
         else{
           $("#district_id_message").hide();
         }
 
         if(user_id=="")
         {
           $("#user_id_message").show();
         }
         else{
           $("#user_id_message").hide();
         }
 
         if(product_id=="")
         {
           $("#product_id_message").show();
         }
         else{
           $("#product_id_message").hide();
         }
 
         if(staff_id=="")
         {
           $("#staff_id_message").show();
         }
         else{
           $("#staff_id_message").hide();
         }
 
          if(contact_id=="")
         {
           $("#contact_id_message").show();
         }
         else{
           $("#contact_id_message").hide();
         }
         
 
         
   if(start_date!='' && end_date!='' && schedule_date!='' && job_type!='' && state_id!='' && district_id!='' && user_id!='' && product_id!='' && staff_id!='' && contact_id!='')
   {
     $("#frm_responce").submit();
   }
   
 
 
       }
 function add_firstresponce()
 {
  var resp_date=$("#resp_date").val();

  var resp_status=$("#resp_status").val();
  var resp_schedule_date=$("#resp_schedule_date").val();
  var resp_schedule_time=$("#resp_schedule_time").val();

  var resp_contact_id=$("#resp_contact_id").val();
  var resp_contact_no=$("#resp_contact_no").val();
  var resp_responce=$("#resp_responce").val();
  var resp_action_plan=$("#resp_action_plan").val();
  
  

  if(resp_date=="")
  {
    $("#resp_date_message").show();
  }
  else{
    $("#resp_date_message").hide();
  }


    if(resp_status=="")
  {
    $("#resp_status_message").show();
  }
  else{
    $("#resp_status_message").hide();
  }

    if(resp_schedule_date=="")
  {
    $("#resp_schedule_date_message").show();
  }
  else{
    $("#resp_schedule_date_message").hide();
  }

    if(resp_schedule_time=="")
  {
    $("#resp_schedule_time_message").show();
  }
  else{
    $("#resp_schedule_time_message").hide();
  }
   if(resp_date!=''  && resp_status!='' && resp_schedule_date!='' && resp_schedule_time!='')
   {
    var url = APP_URL+'/staff/save_first_responce';
    
    $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:$("#frm_firresponce").serialize(),
           success: function (data)
           {    
             var res=data.split('*');
             var proObj = JSON.parse(res[0]);
             var proObj_visit = JSON.parse(res[1]);
             var proObj_part = JSON.parse(res[2]);
             states_val='';
            
             var j=1;
             for (var i = 0; i < proObj.length; i++) {
              states_val +='<tr data-from ="service_responce" id="tr_'+proObj[i]["id"]+'" data-id="'+proObj[i]["id"]+'">';
              states_val +='<td >'+j+'</td>';
              states_val +='<td >First Responce</td>';
               states_val +='<td >'+proObj[i]["schedule_date"]+'</td>';
               states_val +='<td >'+proObj[i]["schedule_time"]+'</td>';
               states_val +='<td >'+proObj[i]["status"]+'</td>';
               var serv_id=APP_URL+"/staff/service_responce/"+proObj[i]["id"];
               states_val +='<td > <a class="btn btn-primary btn-xs" onclick="edit_first_responce('+proObj[i]["id"]+')"  title="Edit">Edit</span></a>'+
                 '<a class="btn btn-danger btn-xs  deleteItemajax" onclick="deleteItemajax()"  data-link="'+serv_id+'"  id="deleteItem'+proObj[i]["id"]+'" data-tr="tr_'+proObj[i]["id"]+'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>'+
                 '</td>';

               states_val +='</tr>';
              j++;
               }
              
               for (var i = 0; i < proObj_visit.length; i++) {
              states_val +='<tr data-from ="service_visit" id="tr_'+proObj_visit[i]["id"]+'" data-id="'+proObj_visit[i]["id"]+'">';
              states_val +='<td >'+j+'</td>';
              states_val +='<td >Visit</td>';
               states_val +='<td >'+proObj_visit[i]["travel_start_time"]+'</td>';
               states_val +='<td >'+proObj_visit[i]["travel_end_time"]+'</td>';
               states_val +='<td >'+proObj_visit[i]["status"]+'</td>';
               var serv_id=APP_URL+"/staff/service_visit/"+proObj_visit[i]["id"];
               states_val +='<td > <a class="btn btn-primary btn-xs" onclick="edit_visit('+proObj_visit[i]["id"]+')"  title="Edit">Edit</span></a>'+
                 '<a class="btn btn-danger btn-xs  deleteItemajax" onclick="deleteItemajax()"  data-link="'+serv_id+'"  id="deleteItem'+proObj_visit[i]["id"]+'" data-tr="tr_'+proObj_visit[i]["id"]+'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>'+
                 '</td>';

               states_val +='</tr>';
              j++;
               }

                 for (var i = 0; i < proObj_part.length; i++) {
              states_val +='<tr data-from ="service_part" id="tr_'+proObj_part[i]["id"]+'" data-id="'+proObj_part[i]["id"]+'">';
              states_val +='<td >'+j+'</td>';
              states_val +='<td >Part Intent</td>';
               states_val +='<td >'+proObj_part[i]["intened_date"]+'</td>';
               states_val +='<td >'+proObj_part[i]["expect_date"]+'</td>';
               states_val +='<td ></td>';
               var serv_id=APP_URL+"/staff/service_part/"+proObj_part[i]["id"];
               states_val +='<td > <a class="btn btn-primary btn-xs" onclick="edit_part('+proObj_part[i]["id"]+')"  title="Edit">Edit</span></a>'+
                 '<a class="btn btn-danger btn-xs  deleteItemajax" onclick="deleteItemajax()"  data-link="'+serv_id+'"  id="deleteItem'+proObj_part[i]["id"]+'" data-tr="tr_'+proObj_part[i]["id"]+'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>'+
                 '</td>';

               states_val +='</tr>';
              j++;
               }


               $("#responce_sec").html(states_val);
              
              $("#resp_date").val('');
          
              $("#resp_status").val('');
              $("#resp_schedule_date").val('');
              $("#resp_schedule_time").val('');

              $("#resp_contact_id").val('');
              $("#resp_contact_no").val('');
              $("#resp_responce").val('');
              $("#resp_action_plan").val('');
            
           }
         });
   }
   
 }
 
 function add_visit(id)
 {
  var travel_start_time=$("#travel_start_time").val();
  var travel_end_time=$("#travel_end_time").val();
  var work_start=$("#work_start").val();
  var work_end=$("#work_end").val();
  
  if(travel_start_time=="")
  {
    $("#travel_start_time_message").show();
  }
  else{
    $("#travel_start_time_message").hide();
  }

 

    if(work_start=="")
  {
    $("#work_start_message").show();
  }
  else{
    $("#work_start_message").hide();
  }


if( work_start!='' &&  travel_start_time!='')
  {
    var url = APP_URL+'/staff/save_visit';
    
    $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:$("#frm_visit").serialize(),
           success: function (data)
           {    
             
            var res=data.split('*');
             var proObj = JSON.parse(res[0]);
             var proObj_visit = JSON.parse(res[1]);
             var proObj_part = JSON.parse(res[2]);
             states_val='';
            
             var j=1;
             for (var i = 0; i < proObj.length; i++) {
              states_val +='<tr data-from ="service_responce" id="tr_'+proObj[i]["id"]+'" data-id="'+proObj[i]["id"]+'">';
              states_val +='<td >'+j+'</td>';
              states_val +='<td >First Responce</td>';
               states_val +='<td >'+proObj[i]["schedule_date"]+'</td>';
               states_val +='<td >'+proObj[i]["schedule_time"]+'</td>';
               states_val +='<td >'+proObj[i]["status"]+'</td>';
               var serv_id=APP_URL+"/staff/service_responce/"+proObj[i]["id"];
               states_val +='<td > <a class="btn btn-primary btn-xs" onclick="edit_first_responce('+proObj[i]["id"]+')"  title="Edit">Edit</span></a>'+
                 '<a class="btn btn-danger btn-xs  deleteItemajax" onclick="deleteItemajax()"  data-link="'+serv_id+'"  id="deleteItem'+proObj[i]["id"]+'" data-tr="tr_'+proObj[i]["id"]+'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>'+
                 '</td>';

               states_val +='</tr>';
              j++;
               }
              
               for (var i = 0; i < proObj_visit.length; i++) {
              states_val +='<tr data-from ="service_visit" id="tr_'+proObj_visit[i]["id"]+'" data-id="'+proObj_visit[i]["id"]+'">';
              states_val +='<td >'+j+'</td>';
              states_val +='<td >Visit</td>';
               states_val +='<td >'+proObj_visit[i]["travel_start_time"]+'</td>';
               states_val +='<td >'+proObj_visit[i]["travel_end_time"]+'</td>';
               states_val +='<td >'+proObj_visit[i]["status"]+'</td>';
               var serv_id=APP_URL+"/staff/service_visit/"+proObj_visit[i]["id"];
               states_val +='<td > <a class="btn btn-primary btn-xs" onclick="edit_visit('+proObj_visit[i]["id"]+')"  title="Edit">Edit</span></a>'+
                 '<a class="btn btn-danger btn-xs  deleteItemajax" onclick="deleteItemajax()"  data-link="'+serv_id+'"  id="deleteItem'+proObj_visit[i]["id"]+'" data-tr="tr_'+proObj_visit[i]["id"]+'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>'+
                 '</td>';

               states_val +='</tr>';
              j++;
               }

                 for (var i = 0; i < proObj_part.length; i++) {
              states_val +='<tr data-from ="service_part" id="tr_'+proObj_part[i]["id"]+'" data-id="'+proObj_part[i]["id"]+'">';
              states_val +='<td >'+j+'</td>';
              states_val +='<td >Part Intent</td>';
               states_val +='<td >'+proObj_part[i]["intened_date"]+'</td>';
               states_val +='<td >'+proObj_part[i]["expect_date"]+'</td>';
               states_val +='<td ></td>';
               var serv_id=APP_URL+"/staff/service_part/"+proObj_part[i]["id"];
               states_val +='<td > <a class="btn btn-primary btn-xs" onclick="edit_part('+proObj_part[i]["id"]+')"  title="Edit">Edit</span></a>'+
                 '<a class="btn btn-danger btn-xs  deleteItemajax" onclick="deleteItemajax()"  data-link="'+serv_id+'"  id="deleteItem'+proObj_part[i]["id"]+'" data-tr="tr_'+proObj_part[i]["id"]+'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>'+
                 '</td>';

               states_val +='</tr>';
              j++;
               }


               $("#responce_sec").html(states_val);
              
              $("#travel_start_time").val('');
              $("#travel_end_time").val('');
              $("#work_start").val('');
              $("#work_end").val('');
              $("#observed_prob").val('');

              $("#visit_action_taken").val('');
              $("#visit_status").val('');
              $("#visit_action_plan").val('');
            
           }
         });

  }

 }


 function add_part(id)
 {
  var part_no=$("#part_no").val();
  var intened_date=$("#intened_date").val();
  var expect_date=$("#expect_date").val();
  
  var description=$("#description").val();
  if(part_no=="")
  {
    $("#part_no_message").show();
  }
  else{
    $("#part_no_message").hide();
  }

  if(description=="")
  {
    $("#description_message").show();
  }
  else{
    $("#description_message").hide();
  }
  //   if(intened_date=="")
  // {
  //   $("#intened_date_message").show();
  // }
  // else{
  //   $("#intened_date_message").hide();
  // }

  //   if(expect_date=="")
  // {
  //   $("#expect_date_message").show();
  // }
  // else{
  //   $("#expect_date_message").hide();
  // }

if(part_no!='' && description!='' )
  {
    var url = APP_URL+'/staff/save_part';
    
    $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:$("#frm_part").serialize(),
           success: function (data)
           {    
            
            var res=data.split('*');
             var proObj = JSON.parse(res[0]);
             var proObj_visit = JSON.parse(res[1]);
             var proObj_part = JSON.parse(res[2]);
             states_val='';
            
             var j=1;
             for (var i = 0; i < proObj.length; i++) {
              states_val +='<tr data-from ="service_responce" id="tr_'+proObj[i]["id"]+'" data-id="'+proObj[i]["id"]+'">';
              states_val +='<td >'+j+'</td>';
              states_val +='<td >First Responce</td>';
               states_val +='<td >'+proObj[i]["schedule_date"]+'</td>';
               states_val +='<td >'+proObj[i]["schedule_time"]+'</td>';
               states_val +='<td >'+proObj[i]["status"]+'</td>';
               var serv_id=APP_URL+"/staff/service_responce/"+proObj[i]["id"];
               states_val +='<td > <a class="btn btn-primary btn-xs" onclick="edit_first_responce('+proObj[i]["id"]+')"  title="Edit">Edit</span></a>'+
                 '<a class="btn btn-danger btn-xs  deleteItemajax" onclick="deleteItemajax()"  data-link="'+serv_id+'"  id="deleteItem'+proObj[i]["id"]+'" data-tr="tr_'+proObj[i]["id"]+'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>'+
                 '</td>';

               states_val +='</tr>';
              j++;
               }
              
               for (var i = 0; i < proObj_visit.length; i++) {
              states_val +='<tr data-from ="service_visit" id="tr_'+proObj_visit[i]["id"]+'" data-id="'+proObj_visit[i]["id"]+'">';
              states_val +='<td >'+j+'</td>';
              states_val +='<td >Visit</td>';
               states_val +='<td >'+proObj_visit[i]["travel_start_time"]+'</td>';
               states_val +='<td >'+proObj_visit[i]["travel_end_time"]+'</td>';
               states_val +='<td >'+proObj_visit[i]["status"]+'</td>';
               var serv_id=APP_URL+"/staff/service_visit/"+proObj_visit[i]["id"];
               states_val +='<td > <a class="btn btn-primary btn-xs" onclick="edit_visit('+proObj_visit[i]["id"]+')"  title="Edit">Edit</span></a>'+
                 '<a class="btn btn-danger btn-xs  deleteItemajax" onclick="deleteItemajax()"  data-link="'+serv_id+'"  id="deleteItem'+proObj_visit[i]["id"]+'" data-tr="tr_'+proObj_visit[i]["id"]+'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>'+
                 '</td>';

               states_val +='</tr>';
              j++;
               }

                 for (var i = 0; i < proObj_part.length; i++) {
              states_val +='<tr data-from ="service_part" id="tr_'+proObj_part[i]["id"]+'" data-id="'+proObj_part[i]["id"]+'">';
              states_val +='<td >'+j+'</td>';
              states_val +='<td >Part Intent</td>';
               states_val +='<td >'+proObj_part[i]["intened_date"]+'</td>';
               states_val +='<td >'+proObj_part[i]["expect_date"]+'</td>';
               states_val +='<td ></td>';
               var serv_id=APP_URL+"/staff/service_part/"+proObj_part[i]["id"];
               states_val +='<td > <a class="btn btn-primary btn-xs" onclick="edit_part('+proObj_part[i]["id"]+')"  title="Edit">Edit</span></a>'+
                 '<a class="btn btn-danger btn-xs  deleteItemajax" onclick="deleteItemajax()"  data-link="'+serv_id+'"  id="deleteItem'+proObj_part[i]["id"]+'" data-tr="tr_'+proObj_part[i]["id"]+'" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>'+
                 '</td>';

               states_val +='</tr>';
              j++;
               }


               $("#responce_sec").html(states_val);
              
              $("#part_no").val('');
              $("#description").val('');
              $("#intened_date").val('');
              $("#expect_date").val('');
              $("#part_reference").val('');

              $("#part_action_plan").val('');
             
            
           }
         });

  }

 }

 

 function edit_first_responce(id)
 {
  $(".intent_start").hide();
    $(".service_task").hide();
    $(".first_responce").show();
    $(".visit").hide();
    $("#service").val('first_res');
  var url = APP_URL+'/staff/edit_first_responce';

  $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:{
            id: id
           },
           success: function (data)
           {    
             var proObj = JSON.parse(data);
             states_val='';
          $("#resp_date").focus();
             var j=1;
            $("#resp_date").val(proObj[0]["response_date"]);
       
            $("#resp_contact_id").val(proObj[0]["contact_id"]);
            $("#resp_contact_id_val").val(proObj[0]["contact_id"]);
            $("#resp_contact_no").val(proObj[0]["contact_no"]);
            $("#resp_contact_no_val").val(proObj[0]["contact_no"]);
            $("#resp_responce").val(proObj[0]["responce"]);
            $("#resp_status").val(proObj[0]["status"]);
            $("#resp_action_plan").val(proObj[0]["action_plan"]);
            $("#resp_schedule_date").val(proObj[0]["schedule_date"]);
            $("#resp_schedule_time").val(proObj[0]["schedule_time"]);
            $("#service_responce_id").val(proObj[0]["id"]);
            
              
              
           }
         });
 }


 
 function edit_visit(id)
 {
   $(".intent_start").hide();
    $(".service_task").hide();
    $(".first_responce").hide();
    $(".visit").show();
    $("#service").val('visit');
  var url = APP_URL+'/staff/edit_visit';

  $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:{
            id: id
           },
           success: function (data)
           {    
             var proObj = JSON.parse(data);
             states_val='';
       //   $("#travel_start_time").focus();
             var j=1;
            $("#travel_start_time").val(proObj[0]["travel_start_time"]);
            $("#travel_end_time").val(proObj[0]["travel_end_time"]);
            $("#work_start").val(proObj[0]["work_start"]);
            $("#work_end").val(proObj[0]["work_end"]);
            $("#observed_prob").val(proObj[0]["observed_prob"]);
            $("#visit_action_taken").val(proObj[0]["action_taken"]);
            $("#visit_status").val(proObj[0]["status"]);
            $("#visit_action_plan").val(proObj[0]["action_plan"]);
          
            $("#service_visit_id").val(proObj[0]["id"]);
            
              
              
           }
         });
 }


 
 function edit_part(id)
 {
  $(".intent_start").show();
    $(".service_task").hide();
    $(".first_responce").hide();
    $(".visit").hide();
    $("#service").val('part');
  var url = APP_URL+'/staff/edit_part';

  $.ajax({
           type: "POST",
           cache: false,
           url: url,
           data:{
            id: id
           },
           success: function (data)
           {    
             var proObj = JSON.parse(data);
             states_val='';
       //   $("#travel_start_time").focus();
             var j=1;
            $("#part_no").val(proObj[0]["part_no"]);
            $("#description").val(proObj[0]["description"]);
            $("#intened_date").val(proObj[0]["intened_date"]);
            $("#expect_date").val(proObj[0]["expect_date"]);
            $("#part_reference").val(proObj[0]["reference"]);
            $("#part_action_plan").val(proObj[0]["action_plan"]);
          
          
            $("#service_part_id").val(proObj[0]["id"]);
            
              
              
           }
         });
 }



     </script>
 
 
 <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
 <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
 <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
 <script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

<link rel="stylesheet" href="{{ asset('AdminLTE/timepicker/jquery-ui-timepicker-addon.css') }}" type="text/css"/>

<script src="{{ asset('AdminLTE/timepicker/jquery-ui-timepicker-addon.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


 <script type="text/javascript">
$('#product_id').select2();
$('#part_no').select2();




 
 $('#visit_time').timepicker({
   timeFormat: 'H:mm',
   'scrollDefaultNow': true
         });
         $('#visit_schedule_time').timepicker({
   timeFormat: 'H:mm',
   'scrollDefaultNow': true
         });
         
 $('#planned_time').timepicker({
   timeFormat: 'H:mm',
   'scrollDefaultNow': true
         });      
 
        
 $('#start_date').datetimepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
            // minDate: 0  
         });
 $('#visit_date').datepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
            // minDate: 0  
         });
   
$('#next_schedule_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         //  minDate: 0  
        });
 $('#end_date').datetimepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
           // minDate: 0  
         });
         $('#resp_date').datetimepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
          //  minDate: 0  
         });

 $('#resp_schedule_time').datetimepicker({
  dateFormat:'yy-mm-dd',
           // minDate: 0  
         });
     $('#resp_schedule_date').datetimepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
          //   minDate: 0  
         });
         
  
     $('#travel_start_time').datetimepicker({
      dateFormat:'yy-mm-dd',
    //  minDate: 0  
      });     
 
 $('#travel_end_time').datetimepicker({
  dateFormat:'yy-mm-dd',
 // minDate: 0  
         });

  $('#return_travel_start_time').datetimepicker({
      dateFormat:'yy-mm-dd',
    //  minDate: 0  
      });     
 
 $('#return_travel_end_time').datetimepicker({
  dateFormat:'yy-mm-dd',
 // minDate: 0  
         });

 $('#work_start').datetimepicker({
  dateFormat:'yy-mm-dd',
  //minDate: 0  
         });

    $('#work_end').datetimepicker({
      dateFormat:'yy-mm-dd',
      //minDate: 0  
    });
   
    $('#expect_date').datetimepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
            // minDate: 0  
         }); 

  $('#intened_date').datetimepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
            // minDate: 0  
         });

 // $('#schedule_date').datepicker({
 //             //dateFormat:'yy-mm-dd',
 //             dateFormat:'yy-mm-dd',
 //            minDate: 0  
 //         });   
 $('#visit_schedule_date').datepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
          //  minDate: 0  
         });      
 
         $('#schedule_date').datetimepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
            minDate: '<?php echo $service_task->start_date;?>',
            maxDate: '<?php echo $service_task->end_date;?>' 
         });    


 function change_date()
    {
     var start_date=$("#start_date").val();
     var end_date=$("#end_date").val();
     if(start_date!='' && end_date!='')
     {
      $("#schedule_date").datetimepicker("destroy");
       $('#schedule_date').datetimepicker({
             //dateFormat:'yy-mm-dd',
             dateFormat:'yy-mm-dd',
            minDate: start_date,
            maxDate: end_date 
         });    
     }
    }     
 function service_change()
 {
   var service=$("#service").val();
   
   if(service=="service_task")
   {
    
    $("#responceTable-sec").hide();
    $(".first_responce").hide();
     $(".service_task").show();
     $(".visit").hide();
     $(".intent_start").hide();
     $(".quotesec").hide();
   }
   if(service=="first_res")
   {$("#responceTable-sec").show();
    $(".first_responce").show();
     $(".service_task").hide();
     $(".visit").hide();
     $(".intent_start").hide();
     $(".quotesec").hide();
   }
   if(service=="visit")
   {$("#responceTable-sec").show();
    $(".visit").show();
    $(".first_responce").hide();
    $(".service_task").hide();
    $(".intent_start").hide();
    $(".quotesec").hide();
   }
   if(service=="part")
   {
    $("#responceTable-sec").show();
    $(".intent_start").show();
    $(".service_task").hide();
    $(".first_responce").hide();
    $(".visit").hide();
    $(".quotesec").hide();
   }
   if(service=="quote")
   {
    var url = APP_URL+'/staff/list_oppertunity';
    //window.location.href = url;
    window.open(url, '_blank');
   /* $("#responceTable-sec").show();
    $(".intent_start").hide();
    $(".service_task").hide();
    $(".first_responce").hide();
    $(".visit").hide();
    $(".quotesec").show();*/
    
   }

   
 }
 </script>



@endsection
