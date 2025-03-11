

@extends('staff/layouts.app')

@section('title', 'Add IB')

@section('content')

<section class="content-header">
      <h1>
        Edit IB
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.ib-index')}}">Manage IB</a></li>
        <li class="active">Edit IB</li>
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
            @php $staffId = session('STAFF_ID'); @endphp

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
                
            <form role="form" name="frm_ibEdit" id="frm_ibEdit" method="post" action="{{route('staff.ib-update',$ib->id)}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body row">

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Internal Ref No*</label>
                  <input type="text" id="internal_ref_no" name="internal_ref_no" value="{{ $ib->internal_ref_no }}" class="form-control" placeholder="" readonly>
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>
                {{ $ib->op_customer }}
                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">External Ref No*</label>
                  <input type="text" id="external_ref_no" name="external_ref_no" value="{{ $ib->external_ref_no }}" class="form-control" placeholder="External Ref No">
                  <span class="error_message" id="external_ref_no_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label>Select Customer*</label>
                  <select class="form-control" name="user_id" id="user_id">
                      <option value="">-- Select Customer --</option>
                      @foreach($users->sortBy('business_name') as $user)
                          @php $sel = (($ib->user_id) == $user->id) ? 'selected': ''; @endphp
                          <option value="{{ $user->id }}" {{ $sel }}>  {{ $user->business_name }} ({{ $user->district_name }})</option>
</option>
                      @endforeach
                  </select>
                  <span class="error_message" id="user_id_message" style="display: none">Field is required</span></br></br>
              </div>
              

              <div class="form-group col-md-6 col-sm-6 col-lg-6">
                <label>Select Department*</label>
                <select class="form-control" name="department_id" id="department_id">
                    <option value="">-- Select Department --</option>
                    @foreach($categories->sortBy('name') as $department)
                        @php $sel = (($ib->department_id) == $department->id) ? 'selected' : ''; @endphp
                        <option value="{{ $department->id }}" {{ $sel }}>{{ $department->name }}</option>
                    @endforeach
                </select>
                <span class="error_message" id="department_id_message" style="display: none">Field is required</span></br></br>
            </div>
            

            <div class="form-group col-md-6 col-sm-6 col-lg-6">
              <label>Select Equipment*</label>
              <select class="form-control selectpicker" name="equipment_id" id="equipment_id">
                  <option value="">-- Select Equipment --</option>
                  @foreach($products->sortBy('name') as $equipment)
                      @php $sel = (($ib->equipment_id) == $equipment->id) ? 'selected' : ''; @endphp
                      <option value="{{ $equipment->id }}" {{ $sel }}>{{ $equipment->name }}</option>
                  @endforeach
              </select>
              <span class="error_message" id="equipment_id_message" style="display: none">Field is required</span></br></br>
          </div>
          

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Equipment Serial No</label>
                  <input type="text" id="equipment_serial_no" name="equipment_serial_no" value="{{ $ib->equipment_serial_no }}" class="form-control" placeholder="Equipment Serial No">
                  <span class="error_message" id="equipment_serial_no_message" style="display: none">Field is required</span>
                </div>
              
                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Equipment Model No</label>
                  <input type="text" id="equipment_model_no" name="equipment_model_no" value="{{ $ib->equipment_model_no }}" class="form-control" placeholder="Equipment Model No">
                  <span class="error_message" id="equipment_model_no_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label>Select Equipment Status*</label>
                  <select class="form-control" name="equipment_status_id" id="equipment_status_id">
                      <option value="">-- Select Equipment Status --</option>
                      @foreach($equipment_status->sortBy('name') as $equipment_stat)
                          @php $sel = (($ib->equipment_status_id) == $equipment_stat->id) ? 'selected' : ''; @endphp
                          <option value="{{ $equipment_stat->id }}" {{ $sel }}>{{ $equipment_stat->name }}</option>
                      @endforeach
                  </select>
                  <span class="error_message" id="equipment_status_id_message" style="display: none">Field is required</span>
              </div>

              
                @if($staffId==55 || $staffId==127)
                  <div class="form-group col-md-6 col-sm-6 col-lg-6">
                      <label >Select Staff*</label>
                      <select class="form-control" name="staff_id" id="staff_id">
                          <option value="">-- Select Staff --</option>                            
                      @foreach($staffs as $staff) 
                        @php $sel = (($ib->staff_id) == $staff->id) ? 'selected': ''; @endphp
                          <option value="{{ $staff->id }}" {{ $sel }}> {{ $staff->name }}</option>
                      @endforeach
                      </select>
                      <span class="error_message" id="staff_id_message" style="display: none">Field is required</span></br></br>
                  </div>
                @endif

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Installation Date*</label>
                  <input type="text" id="installation_date" name="installation_date" value="{{ $ib->installation_date ? \Carbon\Carbon::parse($ib->installation_date)->format('d-m-Y') : '' }}" class="form-control" placeholder="Select Date" readonly>
                  <span class="error_message" id="installation_date_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Warranty End Date*</label>
                  <input type="text" id="warrenty_end_date" name="warrenty_end_date" value="{{ $ib->warrenty_end_date ? \Carbon\Carbon::parse($ib->warrenty_end_date)->format('d-m-Y') : '' }}" class="form-control" placeholder="Select Date" readonly>
                  <span class="error_message" id="warrenty_end_date_message" style="display: none">Field is required</span>
                </div>  

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="supplay_order">Supply Order No.</label>
                  <input type="text" id="supplay_order" name="supplay_order" value="{{ $ib->supplay_order }}" class="form-control" placeholder="Equipment Model No">
                  <span class="error_message" id="supplay_order_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="invoice_number">Invoice No.</label>
                  <input type="text" id="invoice_number" name="invoice_number" value="{{ $ib->invoice_number }}" class="form-control" placeholder="Equipment Model No">
                  <span class="error_message" id="invoice_number_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="invoice_date">Invoice date</label>
                  <input type="text" id="invoice_date" name="invoice_date" value="{{ $ib->invoice_date ? \Carbon\Carbon::parse($ib->invoice_date)->format('d-m-Y') : '' }}" class="form-control" placeholder="Select Date" readonly>
                  <span class="error_message" id="invoice_date_message" style="display: none">Field is required</span>
                </div>  

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Description*</label>
                  <textarea id="description" name="description" class="form-control" placeholder="">{{ $ib->description }}</textarea>
                  <span class="error_message" id="description_message" style="display: none">Field is required</span>
                </div> 

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
              @if($staffId == 55 || $staffId == 127)
                <input type="submit" class="btn btn-primary" name="submit" value="Submit">
              @else
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
              @endif                
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('admin.ib-index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#installation_date').datepicker({
                    //dateFormat:'yy-mm-dd',
                    dateFormat:'dd-mm-yy',
                    changeYear: true,
                    changeMonth:true,
                    yearRange: "1990:{{intval(date('Y'))+40}}",
                   // minDate: 0  
                });
        $('#warrenty_end_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'dd-mm-yy',
            changeYear: true,
            changeMonth:true,
            yearRange: "1990:{{intval(date('Y'))+40}}",
           // minDate: 0  
        });
        $('#invoice_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'dd-mm-yy',
            changeYear: true,
            changeMonth:true,
            yearRange: "1990:{{intval(date('Y'))+40}}",
            //minDate: 0  
        });
    </script>

<!-- Select2 CSS --> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 

<!-- Select2 JS --> 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

  <script>
      $(document).ready(function() {

          $("#user_id").select2({
            enableFiltering: true,
          });
          $("#department_id").select2({
            enableFiltering: true,
          });
          $("#equipment_id").select2({
            enableFiltering: true,
          });
          $("#staff_id").select2({
            enableFiltering: true,
          });

      });
  </script>

    <script type="text/javascript">

      function validate_from()
      {
        
        var external_ref_no   =   $("#external_ref_no").val();
        var user              =   $("#user_id").val();
        var department        =   $("#department_id").val();
        var equipment         =   $("#equipment_id").val();
        var equipment_serial  =   $("#equipment_serial_no").val();
        var equipment_model   =   $("#equipment_model_no").val();
        var installation_date =   $("#installation_date").val();
        var warrenty_end_date =   $("#warrenty_end_date").val();
        var description       =   $("#description").val();
        
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
        if(department=="")
        {
          $("#department_id_message").show();
        }
        else{
          $("#department_id_message").hide();
        }
        if(equipment=="")
        {
          $("#equipment_id_message").show();
        }
        else{
          $("#equipment_id_message").hide();
        }
        if(equipment_serial=="")
        {
          $("#equipment_serial_no_message").show();
        }
        else{
          $("#equipment_serial_no_message").hide();
        }
        if(equipment_model=="")
        {
          $("#equipment_model_no_message").show();
        }
        else{
          $("#equipment_model_no_message").hide();
        }
        if(installation_date=="")
        {
          $("#installation_date_message").show();
        }
        else{
          $("#installation_date_message").hide();
        }
        if(description=="")
        {
          $("#description_message").show();
        }
        else{
          $("#description_message").hide();
        }
        if(external_ref_no!='' && user!='' && department!='' && equipment!='' && equipment_serial!='' && equipment_model!='' && installation_date!='' && description!='')
        {
         $("#frm_ibEdit").submit(); 
        }


      }

    </script>
@endsection

 