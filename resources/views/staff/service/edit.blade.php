

@extends('staff/layouts.app')

@section('title', 'Add Service')

@section('content')

<section class="content-header">
      <h1>
        Add {{ $service->service_type }}
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        
        <li class="active">Add {{ $service->service_type }}</li>
      </ol>
    </section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-10">
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
                @php
                    $inRefno = 'CR-'.rand(1000, 100000);
                @endphp
        

            <form role="form" name="frm_service" id="frm_service" method="post" action="{{ route('staff.service-update',$service->id) }}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body row">

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Internal Ref No*</label>
                  <input type="text" id="internal_ref_no" name="internal_ref_no" value="{{ $service->internal_ref_no }}" class="form-control" placeholder="" readonly>
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>
                <input type="hidden" id="service_type" name="service_type" value="{{ $service->service_type }}">
                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">External Ref No*</label>
                  <input type="text" id="external_ref_no" name="external_ref_no" value="{{ $service->external_ref_no }}" class="form-control" placeholder="External Ref No">
                  <span class="error_message" id="external_ref_no_message" style="display: none">Field is required</span>
                </div>


              <div class="form-group col-md-6 col-sm-6 col-lg-6">
                <label>Select Customer*</label>
                <select class="form-control" name="user_id" id="user_id">
                    <option value="">-- Select Customer --</option>
                    @foreach($users->sortBy(fn($user) => strtolower($user->business_name)) as $user)
                        @php $sel = ($service->user_id == $user->id) ? 'selected' : ''; @endphp
                        <option value="{{ $user->id }}" {{ $sel }}>{{ $user->business_name }}</option>
                    @endforeach
                </select>
                <span class="error_message" id="user_id_message" style="display: none">Field is required</span></br></br>
            </div>

            

               <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label>Select Contact Person*</label>
                  <select class="form-control" name="contact_person_id" id="contact_person_id">
                      <option value="">-- Select Contact Person --</option>
                      @foreach($editContactPersons->sortBy('name') as $editContactPerson)
                          @php $sel = (($service->contact_person_id) == $editContactPerson->id) ? 'selected' : ''; @endphp
                          <option value="{{ $editContactPerson->id }}" {{ $sel }}>{{ $editContactPerson->name }}</option>
                      @endforeach
                  </select>
                  <span class="error_message" id="contact_person_id_message" style="display: none">Field is required</span></br></br>
              </div>
            
                
                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Contact Number</label>
                  <input type="text" id="contact_no" name="contact_no" value="{{ $service->serviceContactPerson->mobile }}" class="form-control" placeholder="Contact Number" readonly>
                  <span class="error_message" id="contact_no_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label>Select Equipment Name*</label>
                  <select class="form-control selectpicker" name="equipment_id" id="equipment_id">
                      <option value="">-- Select Equipment Name --</option>
                      @foreach($editEquipments->sortBy('name') as $editEquipment)
                          @php $sel = (($service->equipment_id) == $editEquipment->id) ? 'selected' : ''; @endphp
                          <option value="{{ $editEquipment->id }}" {{ $sel }}>{{ $editEquipment->name }}</option>
                      @endforeach
                  </select>
                  <span class="error_message" id="equipment_id_message" style="display: none">Field is required</span></br></br>
              </div>
              

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Equipment Serial No*</label>
                    <select class="form-control selectpicker" name="equipment_serial_no" id="equipment_serial_no">
                         <option value="">-- Select Equipment Serial No --</option>
                       
                         @foreach($editEquipmentSerials as $editEquipmentSerial) 
                            @php $sel = (($service->equipment_serial_no) == $editEquipmentSerial->equipment_serial_no) ? 'selected': ''; @endphp
                                <option value="{{ $editEquipmentSerial->equipment_serial_no }}" {{ $sel }}> {{ $editEquipmentSerial->equipment_serial_no }}</option>
                        @endforeach 
                    </select>
                    <span class="error_message" id="equipment_serial_no_message" style="display: none">Field is required</span></br></br>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Equipment Status</label>
                  <input type="text" id="equipment_status_id" name="equipment_status_id" value=" {{ $service->equipment_status_id }} " class="form-control" placeholder="Equipment Status" readonly>
                  <span class="error_message" id="equipment_status_no_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label>Select Machine Status*</label>
                  <select class="form-control" name="machine_status_id" id="machine_status_id">
                      <option value="">-- Select Machine Status --</option>
                      @foreach($machine_status->sortBy(fn($status) => strtolower($status->name)) as $machine_stat)
                          @php $sel = ($service->machine_status_id == $machine_stat->id) ? 'selected' : ''; @endphp
                          <option value="{{ $machine_stat->id }}" {{ $sel }}>{{ $machine_stat->name }}</option>
                      @endforeach
                  </select>
                  <span class="error_message" id="machine_status_id_message" style="display: none">Field is required</span>
              </div>
              

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label>Select Engineer *</label>
                  <select class="form-control" name="engineer_id" id="engineer_id">
                      <option value="">-- Select Engineer --</option>
                      @foreach($staffs->sortBy('name') as $engineer)
                          @php $sel = (($service->engineer_id) == $engineer->id) ? 'selected' : ''; @endphp
                          <option value="{{ $engineer->id }}" {{ $sel }}>{{ $engineer->name }}</option>
                      @endforeach
                  </select>
                  <span class="error_message" id="engineer_id_message" style="display: none">Field is required</span>
              </div>
              

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Call Details*</label>
                  <textarea id="call_details" name="call_details" class="form-control" placeholder="">{{ $service->call_details }}</textarea>
                  <span class="error_message" id="call_details_message" style="display: none">Field is required</span>
                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.ib-index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')
  
<!-- Select2 CSS --> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 

<!-- Select2 JS --> 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

  <script>
      $(document).ready(function() {

          $("#user_id").select2({
            enableFiltering: true,
          });
          $("#equipment_id").select2({
            enableFiltering: true,
          });
          $("#engineer_id").select2({
            enableFiltering: true,
          });
          $("#equipment_serial_no").select2({
            enableFiltering: true,
          });

      });   
  
        $("#user_id").change(function(){
            var user_id = $("#user_id").val();
            
            var APP_URL = {!! json_encode(url('/')) !!};
           
            var url = APP_URL+'/staff/customer_contact_person';
            var html_contact=" ";
            var html_equipment=" ";
                $('#contact_no').val(" ");
                $('#equipment_status_no').val(" ");
                $('#equipment_status_id').val(" ");
                
                $.ajax
                    ({
                        type: "POST",
                        cache: false,
                        url: url,
                        data:{
                            user_id : user_id
                    },
                success: function (data)
                {       
                  
                  html_contact+= "<option value=''>---Select Contact Person---</option>"             
                  $.each(data['contactPersons'],function (){ 
                    html_contact+="<option value='"+this.id+"'>"+this.name+"</option>"; 
                  });
                      $("#contact_person_id").html(html_contact);

                      html_equipment+= "<option value=''>---Select Equipment Name---</option>"             
                  $.each(data['equipments'],function (){ 
                    html_equipment+="<option value='"+this.id+"'>"+this.name+"</option>"; 
                  });
                      $("#equipment_id").html(html_equipment);
                }          
            });    
          }); 
          $("#equipment_id").change(function(){
            var id = $("#equipment_id").val();
            var user_id = $("#user_id").val();
            
            var APP_URL = {!! json_encode(url('/')) !!};
           
            var url = APP_URL+'/staff/equipment_serial';
            var html_equipment_serial = " ";
            $('#equipment_status_no').val(" ");
                $.ajax
                    ({
                        type: "POST",
                        cache: false,
                        url: url,
                        data:{
                          id : id,
                          user_id : user_id
                    },
                success: function (data)
                {       
                  html_equipment_serial+= "<option value=''>---Select Equipment Name---</option>"  
                  $.each(data['equipmentSerial'],function (){ 
                               
                    html_equipment_serial+="<option value='"+this.id+"'>"+this.equipment_serial_no+"</option>"; 
                  });
                      $("#equipment_serial_no").html(html_equipment_serial);  
                }          
            });    
          }); 
          $("#contact_person_id").change(function(){
            var id = $("#contact_person_id").val();
            $('#contact_no').val("");
            var APP_URL = {!! json_encode(url('/')) !!};
           
            var url = APP_URL+'/staff/customer_contact_detail';

                $.ajax
                    ({
                        type: "POST",
                        cache: false,
                        url: url,
                        data:{
                          id : id
                    },
                success: function (data)
                {       
                  $('#contact_no').val(data['contactDetails']['mobile']);
                }
              });          
            });   

            $("#equipment_serial_no").change(function(){
            var id = $("#equipment_serial_no").val();
            $('#equipment_status_id').val("");
            var APP_URL = {!! json_encode(url('/')) !!};
           
            var url = APP_URL+'/staff/equipment_detail';

                $.ajax
                    ({
                        type: "POST",
                        cache: false,
                        url: url,
                        data:{
                          id : id
                    },
                success: function (data)
                {       
                  console.log(data['equipmentDetails']['ib_equipment_status']['name']);
                  $('#equipment_status_id').val(data['equipmentDetails']['ib_equipment_status']['name']);
                }          
            });  
          }); 
  </script>

  <script type="text/javascript">

      function validate_from()
      {
        
        var external_ref_no       =   $("#external_ref_no").val();
        var user                  =   $("#user_id").val();
        var contact_person_id     =   $("#contact_person_id").val();
        var contact_no            =   $("#contact_no").val();
        var equipment_id          =   $("#equipment_id").val();
        var equipment_serial_no   =   $("#equipment_serial_no").val();
        var equipment_status_id   =   $("#equipment_status_id").val();
        var machine_status_id     =   $("#machine_status_id").val();
        var call_details          =   $("#call_details").val();
        var engineer_id           =   $("#engineer_id").val();
        
        if(external_ref_no=="")
        {
          $("#external_ref_no_message").show();
        }
        else{
          $("#external_ref_no_message").hide();
        }
        if(user=="")
        {
          $("#user_id_message").show();
        }
        else{
          $("#user_id_message").hide();
        }
        if(contact_person_id=="")
        {
          $("#contact_person_id_message").show();
        }
        else{
          $("#contact_person_id_message").hide();
        }
        if(contact_no=="")
        {
          $("#contact_no_message").show();
        }
        else{
          $("#contact_no_message").hide();
        }
        if(equipment_id=="")
        {
          $("#equipment_id_message").show();
        }
        else{
          $("#equipment_id_message").hide();
        }
        if(equipment_serial_no=="")
        {
          $("#equipment_serial_no_message").show();
        }
        else{
          $("#equipment_serial_no_message").hide();
        }
        if(equipment_status_id=="")
        {
          $("#equipment_status_id_message").show();
        }
        else{
          $("#equipment_status_id_message").hide();
        }
        if(machine_status_id=="")
        {
          $("#machine_status_id_message").show();
        }
        else{
          $("#machine_status_id_message").hide();
        }
        if(call_details=="")
        {
          $("#call_details_message").show();
        }
        else{
          $("#call_details_message").hide();
        }
        if(engineer_id=="")
        {
          $("#engineer_id_message").show();
        }
        else{
          $("#engineer_id_message").hide();
        }
  
        if(external_ref_no!='' && user!='' && contact_person_id!='' && contact_no!='' && equipment_id!='' && equipment_serial_no!='' && equipment_status_id!='' && machine_status_id!='' && call_details!='' && engineer_id!='')
        {
         $("#frm_service").submit(); 
        }
      }

    </script>
@endsection
