@extends('staff/layouts.app')

@section('title', 'Add First Responce')

@section('content')

<section class="content-header">
      <h1>
        Add Service task
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="">Manage Service task</a></li>
        <li class="active">Add Service task</li>
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

            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif


            @if(session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session()->get('error_message') }}
                </div>
            @endif

            <p class="error-content alert-danger">
            {{ $errors->first('name') }}
            {{ $errors->first('image_name') }}
            </p>

            <form autocomplete="off" role="form" name="frm_responce" id="frm_responce" method="post" action="{{route('staff.service_task.store')}}" enctype="multipart/form-data" >
               @csrf
               <div class="box-body">


               
				

					<div class="tab-pane active" id="service_task">
            <!-- Service task start -->
            <div class="box-body">
                 <div class="form-group col-md-6">
                  <label for="name">Service Reference No*</label>
                  <?php 
                  $rand_val='SV'.rand(100000,1000000);
                  ?>
                  <input type="text" id="service_ref_no" disabled="true" name="service_ref_no" value="<?php echo $rand_val;?>" class="form-control" placeholder="Service Reference No">
                  <input type="hidden" id="service_ref_no_val"  name="service_ref_no_val" value="<?php echo $rand_val;?>" class="form-control">
                  <span class="error_message" id="service_ref_no_message" style="display: none">Field is required</span>
                </div> 

                  <div class="form-group col-md-6">
                  <label for="name">Start Date*</label>
                  <input type="text" id="start_date" onchange="change_date()" name="start_date" value="{{ old('start_date')}}" class="form-control" placeholder="Start Date">
                  <span class="error_message" id="start_date_message" style="display: none">Field is required</span>
                </div>

                
                
                <div class="form-group col-md-6">
                  <label for="name">End Date*</label>
                  <input type="text" onchange="change_date()" id="end_date" name="end_date" value="{{ old('end_date')}}" class="form-control" placeholder="End Date">
                  <span class="error_message" id="end_date_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group col-md-6">
                  <label for="name">Schedule Date*</label>
                  <input type="text" id="schedule_date" name="schedule_date" value="{{ old('schedule_date')}}" class="form-control" placeholder="Schedule Date">
                  <span class="error_message" id="schedule_date_message" style="display: none">Field is required</span>
                </div>
               


              

                <div class="form-group col-md-6">
                  <label for="name">Service For Other Reference</label>
                  <input type="text" id="other_ref" name="other_ref" value="{{ old('other_ref')}}" class="form-control" placeholder="Other Reference">
                  <span class="error_message" id="other_ref_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group col-md-6">
                  <label for="name">Service Status </label>
                  <select id="service_status" name="service_status" class="form-control" onchange="change_service_status()">
                  <option value="">Select Service Status </option>
                  <option value="Open" >Open</option>
                <option value="Closed" >Closed</option>
                <option value="Visit" >Visit</option>
               
                  </select>
                  <span class="error_message" id="service_status_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group col-md-12 next_sch_date" style="display:none;">
                  <label for="name">Next Schedule Date</label>
                  <input type="text" id="next_schedule_date" name="next_schedule_date" value="{{ old('next_schedule_date')}}" class="form-control" placeholder="Next Schedule Date">
                  <span class="error_message" id="next_schedule_date_message" style="display: none">Field is required</span>
                </div>


                 <div class="form-group col-md-6">
                  <label for="name">Job Type*</label>
                  <select id="job_type" name="job_type" class="form-control">
                  <option value="">Select Job Type</option>
                  <option value="CR" >CR</option>
                  <option value="PM" >PM</option>
                  <option value="Instalation" >Instalation</option>
                <option value="FMI" >FMI</option>
                <option value="Data Collection" >Data Collection</option>
                <option value="Others" >Others</option>
                <option value="MSA Followup" >MSA Followup</option>
              
             
                  </select>
                  <span class="error_message" id="job_type_message" style="display: none">Field is required</span>
                </div>

               

                <div class="form-group col-md-6">
                  <label for="name">State*</label>
                  <select id="state_id" name="state_id" class="form-control" onchange="change_state()"> 
                  <option value="">Select State</option>
                  @foreach($state as $values)
                  <?php
                  
                  echo '<option value="'.$values->id.'" >'.$values->name.'</option>';
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
                 
                  echo '<option value="'.$values->id.'" >'.$values->name.'</option>';
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
                 
                  </select>
                  <span class="error_message" id="user_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-12">
              
                  <label for="name">Contact Person*  <a style="display:none;" id="contact_link" href="{{route('staff.customer.index')}}" target='_blank'>Add contact</a> </label>
                  <select id="contact_id" name="contact_id" class="form-control" onchange="change_contact_person()">
                  <option value="">Select Contact Person</option>
                 
                  </select>
                  <span class="error_message" id="contact_id_message" style="display: none">Field is required</span>
                </div>



                 <div class="form-group col-md-12">
                  <label for="name">Product Description*</label>
                  <select id="product_id" name="product_id" class="form-control">
                  <option value="">Select Product Description</option>
                  @foreach($product as $values)
                  <?php
                  
                  echo '<option value="'.$values->id.'" >'.$values->name.'</option>';
                  ?>
                 
                  @endforeach
                  </select>

                  <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Customer Equipment Status</label>
                  <select id="cus_eq_status" name="cus_eq_status" class="form-control" onchange="change_state()"> 
                  <option value="">Select Customer Equipment Status</option>
                <option  >Systen Use Or Not</option>
                 
                  </select>
                  <span class="error_message" id="cus_eq_status_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group col-md-6">
                  <label for="name">Serial Number</label>
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
                  
                  echo '<option value="'.$values->id.'" >'.$values->name.'</option>';
                  ?>
                 
                  @endforeach
                  </select>
                  <span class="error_message" id="staff_id_message" style="display: none">Field is required</span>
                </div>

                 

                <div class="form-group col-md-6">
                  <label for="name">Contact Number</label>
                  <input type="text" disabled="true" id="contact_no" name="contact_no" value="{{ old('contact_no')}}" class="form-control" placeholder="Contact Number">
                  <input type="hidden" id="contact_no_val" name="contact_no_val" value="">
                  <span class="error_message" id="contact_no_message" style="display: none">Field is required</span>
                </div>


                 <div class="form-group col-md-6">
                  <label for="name">Email</label>
                  <input type="text" id="email"  disabled="true" name="email" value="{{ old('email')}}" class="form-control" placeholder="Email">
                  <input type="hidden" id="email_val" name="email_val" value="">
                  <span class="error_message" id="email_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group">
                  <label for="name">Problem Reported</label>
                  <textarea id="problem_reported" name="problem_reported"  class="form-control" placeholder="Problem Reported">{{ old('action_plan')}}</textarea>
                  <span class="error_message" id="problem_reported_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group">
                  <label for="name">Problem Description</label>
                  <textarea id="prob_desc" name="prob_desc"  class="form-control" placeholder="Problem Description">{{ old('action_plan')}}</textarea>
                  <span class="error_message" id="prob_desc_message" style="display: none">Field is required</span>
                </div>

              


             </div>
            <!-- Service task end -->
          </div>
     
         

              
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href=''">Cancel</button>
              </div>
            </form>
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

$('#response_time').timepicker({
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
          //  minDate: 0  
        });
 
  
$('#end_date').datetimepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         //  minDate: 0  
        });

  
$('#next_schedule_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         //  minDate: 0  
        });
               
// $('#schedule_date').datepicker({
//             //dateFormat:'yy-mm-dd',
//             dateFormat:'yy-mm-dd',
//            minDate: 0  
//         });   
$('#planned_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
          // minDate: 0  
        });      

function change_date()
   {
    var start_date=$("#start_date").val();
    var end_date=$("#end_date").val();
    if(start_date!='' && end_date!='')
    {
      $('#schedule_date').datetimepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
           minDate: start_date,
           maxDate: end_date 
        });    
    }
   }     

</script>
@endsection
