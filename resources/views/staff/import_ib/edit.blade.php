

@extends('staff/layouts.app')

@section('title', 'Add IB')

@section('content')

<section class="content-header">
      <h1>
        Edit IB Import
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.staff_import_ib')}}">Import IB</a></li>
        <li class="active">Edit IB Import</li>
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
                
            <form role="form" name="frm_ibEdit" id="frm_ibEdit" method="post" action="{{route('staff.import_update-staff',$ib_import->id)}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body row">

                {{-- <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Internal Ref No*</label>
                  <input type="text" id="" name="internal_ref_no_read" value="" class="form-control" placeholder="" readonly>
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="name">Internal Ref No*</label>
                    <input type="text" id="internal_ref_no" name="internal_ref_no" value="" class="form-control" placeholder="" >
                    <span class="error_message" id="name_message" style="display: none">Field is required</span>
                  </div> --}}


                {{-- {{ $ib->op_customer }} --}}
                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">External Ref No</label>
                  <input type="text" id="" name="external_ref_no_read" value="" class="form-control" readonly placeholder="External Ref No">
                  {{-- <span class="error_message" id="" style="display: none">Field is required</span> --}}
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="name">External Ref No</label>
                    <input type="text" id="external_ref_no" name="external_ref_no" value="{{old('external_ref_no',optional($import_save)->external_ref_no)}}"  class="form-control"  placeholder="External Ref No">

                    {{-- @if($errors->has('external_ref_no')) <span class="error_message">{{ $errors->first('external_ref_no') }}</span> @endif --}}
   
                  </div>

                <div class="form-group col-md-6">
                  <label>State*</label>
                  <select name="state_id_read" id="" class="form-control" readonly onchange="change_state()">
                    <option value="">-- Select State --</option>
                    <?php
                    
                    foreach($state as $item) {

                      $sel = (strtolower($ib_import->state) == strtolower($item->name ) ) ? 'selected': '';
                      
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                    } ?>

                  </select>
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6">
                    <label>State*</label>
                    <select name="state_id" id="state_id" class="form-control"  onchange="change_state()">
                      <option value="">-- Select State --</option>
                      <?php
                      
                      foreach($state as $item) {
  
                        if(old('state_id') != "")
                        {
                           $sel = (old('state_id') == $item->id) ? 'selected': '';
                        }
                        elseif(!empty(optional($import_save)->state_id))
                        {
                            $sel = ($import_save->state_id == $item->id) ? 'selected': '';
                        }
                        else
                        {
                            $sel = (old('state_id',strtolower($ib_import->state)) == strtolower($item->name ) ) ? 'selected': '';
                        }
                        
                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
  
                      } ?>
  
                    </select>
                    <span class="error_message" id="state_id_message" style="display: none">Field is required</span>

                    @if($errors->has('state_id')) <span class="error_message">{{ $errors->first('state_id') }}</span> @endif
                </div>

                <div class="form-group col-md-6">
                  <label>District*</label>

                  <select name="district_id_read" id="" readonly class="form-control" onchange="change_district()">

                    <option value="">-- Select District --</option>

                     <option value="{{ $ib_import->district }}" selected>{{ $ib_import->district }}</option>

                    <?php
                      
                      foreach($district as $item) {

                        $sel = (strtolower($ib_import->district) == strtolower($item->name ) ) ? 'selected': '';

                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                      
                      } ?>
                  </select>
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div> 
                
                <div class="form-group col-md-6">
                    <label>District*</label>
                    <select name="district_id" id="district_id" class="form-control" onchange="change_district()">
                      <option value="">-- Select District --</option>
                      <?php
                      
                      foreach($district as $item) {
  
                        if(old('district_id') != "")
                        {
                           $sel = (old('district_id') == $item->id) ? 'selected': '';
                        }
                        elseif(!empty(optional($import_save)->district_id))
                        {
                            $sel = ($import_save->district_id == $item->id) ? 'selected': '';
                        }
                        else
                        {
                           $sel = (old('district_id',strtolower($ib_import->district)) == strtolower($item->name ) ) ? 'selected': '';
                        }
                       

                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                      } ?>
                    </select>
                    <span class="error_message" id="district_id_message" style="display: none">Field is required</span>

                      @if($errors->has('district_id')) <span class="error_message">{{ $errors->first('district_id') }}</span> @endif

                  </div> 

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Customer*</label>
                    <select class="form-control" name="user_id_read" id="" readonly>

                         <option value="">-- Select Customer --</option> 
                         
                         <option value="{{ $ib_import->customer_name }}" selected>{{ $ib_import->customer_name }}</option>  

                        @foreach($users as $user) 

                            @php $sel = (($ib_import->customer_name) == $user->business_name) ? 'selected': ''; @endphp

                            @if($ib_import->customer_name != $user->business_name)

                            <option value="{{ $user->id }}" {{ $sel }}> {{ $user->business_name }}</option>

                            @endif

                        @endforeach
                    </select>

                    <span class="error_message" id="user_id_message" style="display: none">Field is required</span>
                    
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Customer*</label>
                    <select class="form-control" name="user_id" id="user_id" >

                        <option value="">-- Select Customer --</option> 
                         
                        @foreach($users as $user) 

                            @if(old('user_id') != "")
                          
                            @php $sel = (old('user_id') == $user->id) ? 'selected': '';  @endphp
                          
                            @elseif(!empty(optional($import_save)->user_id))

                            @php $sel = (($import_save->user_id) == $user->id) ? 'selected': ''; @endphp

                            @else
                                  @php $sel = (old('user_id',($ib_import->customer_name)) == $user->business_name) ? 'selected': ''; @endphp

                            @endif

                            <option value="{{ $user->id }}" {{ $sel }}> {{ $user->business_name }}</option>

                        @endforeach

                    </select>

                    <span class="error_message" id="" style="display: none">Field is required</span>
                   @if($errors->has('user_id')) <span class="error_message">{{ $errors->first('user_id') }}</span> @endif

                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Department</label>
                    <select class="form-control" name="department_id_read" id="" readonly>
                         <option value="">-- Select Department --</option>
                          @foreach($categories as $department) 
                           
                              <option value="{{ $department->id }}">{{ $department->name }}</option>

                          @endforeach   
                    </select>
                    {{-- <span class="error_message" id="" style="display: none">Field is required</span> --}}
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Department</label>
                    <select class="form-control" name="department_id" id="department_id" >
                         <option value="">-- Select Department --</option>
                          @foreach($categories as $department) 
                           
                                @php $sel = (old('department_id',(optional($import_save)->department_id)) == $department->id) ? 'selected': ''; @endphp

                              <option value="{{ $department->id }}" {{$sel}}>{{ $department->name }}</option>

                          @endforeach   
                    </select>
                    {{-- <span class="error_message" id="department_id_message" style="display: none">Field is required</span>

                       @if($errors->has('department_id')) <span class="error_message">{{ $errors->first('department_id') }}</span> @endif --}}

                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Equipment*</label>
                    <select class="form-control selectpicker" name="equipment_id_read" id="" readonly>
                         <option value="">-- Select Equipment --</option>

                         <option value="{{ $ib_import->equipment }}" selected>{{ $ib_import->equipment }}</option> 

                          @foreach($products as $equipment) 

                            @php $sel = (($ib_import->equipment) == $equipment->name) ? 'selected': ''; @endphp

                            @if($ib_import->equipment != $equipment->name)

                            <option value="{{ $equipment->id }}" {{ $sel }}>{{$equipment->name}}</option>

                            @endif

                          @endforeach    

                    </select>

                    <span class="error_message" id="" style="display: none">Field is required</span>

                    

                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Equipment*</label>
                    <select class="form-control selectpicker" name="equipment_id" id="equipment_id" >
                         <option value="">-- Select Equipment --</option>

                       
                          @foreach($products as $equipment) 

                            @if(old('equipment_id') != "")
                          
                            @php $sel = (old('equipment_id') == $equipment->id) ? 'selected': '';  @endphp

                            @elseif(!empty(optional($import_save)->equipment_id))

                              @php $sel = (($import_save->equipment_id) == $equipment->id) ? 'selected': ''; @endphp

                            @else

                               @php $sel = (old('equipment_id',($ib_import->equipment)) == $equipment->name) ? 'selected': ''; @endphp

                            @endif
                           


                              <option value="{{ $equipment->id }}" {{ $sel }}>{{$equipment->name}}</option>

                          @endforeach   

                    </select>

                    <span class="error_message" id="equipment_id_message" style="display: none">Field is required</span>

                     @if($errors->has('equipment_id')) <span class="error_message">{{ $errors->first('equipment_id') }}</span> @endif

                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Equipment Serial No</label>
                  <input type="text" id="" readonly name="equipment_serial_no_read" value="{{ $ib_import->serial }}" class="form-control" placeholder="Equipment Serial No">
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="name">Equipment Serial No</label>
                    <input type="text" id="equipment_serial_no"  name="equipment_serial_no" value="{{ old('equipment_serial_no',optional($import_save)->equipment_serial_no ?? $ib_import->serial) }}" class="form-control" placeholder="Equipment Serial No">
                    <span class="error_message" id="equipment_serial_no_message" style="display: none">Field is required</span>

                    @if($errors->has('equipment_serial_no')) <span class="error_message">{{ $errors->first('equipment_serial_no') }}</span> @endif

                  </div>
              
                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Equipment Model No</label>
                  <input type="text" id="" readonly name="equipment_model_no_read" value="" class="form-control" placeholder="Equipment Model No">
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="name">Equipment Model No</label>
                    <input type="text" id="equipment_model_no"  name="equipment_model_no" value="{{ old('equipment_model_no',optional($import_save)->equipment_model_no)  }}" class="form-control" placeholder="Equipment Model No">
                    <span class="error_message" id="equipment_model_no_message" style="display: none">Field is required</span>

                   @if($errors->has('equipment_model_no')) <span class="error_message">{{ $errors->first('equipment_model_no') }}</span> @endif

                  </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Equipment Status</label>
                    <select class="form-control" name="equipment_status_id_read" id="" readonly>
                         <option value="">-- Select Equipment Status --</option>

                          @foreach($equipment_status as $equipment_stat) 

                            <option value="{{ $equipment_stat->id }}" {{ $sel }}>{{ $equipment_stat->name }}</option>

                          @endforeach 

                    </select>
                    {{-- <span class="error_message" id="equipment_status_id_message" style="display: none">Field is required</span> --}}
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Equipment Status</label>
                    <select class="form-control" name="equipment_status_id" id="equipment_status_id" >
                         <option value="">-- Select Equipment Status --</option>

                          @foreach($equipment_status as $equipment_stat) 

                            @php $sel = (old('equipment_status_id',(optional($import_save)->equipment_status_id)) == $equipment_stat->id) ? 'selected': ''; @endphp

                            <option value="{{ $equipment_stat->id }}" {{ $sel }}>{{ $equipment_stat->name }}</option>

                          @endforeach 

                    </select>
                    {{-- <span class="error_message" id="equipment_status_id_message" style="display: none">Field is required</span>

                     @if($errors->has('equipment_status_id')) <span class="error_message">{{ $errors->first('equipment_status_id') }}</span> @endif
 --}}
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">

                    <label >Select Staff</label>

                    <select class="form-control" name="staff_id_read" id="" readonly>
                         <option value="">-- Select Staff --</option> 

                         <option value="{{ $ib_import->sales_person }}" selected>{{ $ib_import->sales_person }}</option> 

                         @foreach($staffs as $staff) 
 
                             @php $sel = (($ib_import->sales_person) == $staff->name) ? 'selected': ''; @endphp
 
                             @if($ib_import->customer_name != $user->business_name)
 
                                 <option value="{{ $staff->id }}" {{ $sel }}> {{ $staff->name }}</option>
 
                             @endif
 
                         @endforeach
 
                    </select>

                    {{-- <span class="error_message" id="staff_id_message" style="display: none">Field is required</span></br></br> --}}

                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Staff</label>
                    <select class="form-control" name="staff_id" id="staff_id" >
                         <option value="">-- Select Staff --</option> 
                         
                        
                        @foreach($staffs as $staff) 

                            @if(old('staff_id') != "")
                          
                            @php $sel = (old('staff_id') == $staff->id) ? 'selected': '';  @endphp

                            @elseif(!empty(optional($import_save)->staff_id))

                              @php $sel = (($import_save->staff_id) == $staff->id) ? 'selected': ''; @endphp

                            @else

                              @php $sel = (old('staff_id',($ib_import->sales_person)) == $staff->name) ? 'selected': ''; @endphp

                            @endif

                                <option value="{{ $staff->id }}" {{ $sel }}> {{ $staff->name }}</option>

                        @endforeach

                    </select>
                    {{-- <span class="error_message" id="staff_id_message" style="display: none">Field is required</span>

                   @if($errors->has('staff_id')) <span class="error_message">{{ $errors->first('staff_id') }}</span> @endif --}}

                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Installation Date*</label>
                  <input type="text" id="" readonly name="installation_date_read" value="{{ $ib_import->install_date }}" class="form-control" placeholder="Select Date">
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="name">Installation Date*</label>
                    <input type="text" id="installation_date" name="installation_date" value="{{ old('installation_date',optional($import_save)->installation_date ?? $ib_import->install_date)}}" class="form-control" placeholder="Select Date" >
                    <span class="error_message" id="installation_date_message" style="display: none">Field is required</span>

                    @if($errors->has('installation_date')) <span class="error_message">{{ $errors->first('installation_date') }}</span> @endif

                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Warranty End Date</label>
                  <input type="text" id="" readonly name="warrenty_end_date_read" value="{{  $ib_import->end_date}}" class="form-control" placeholder="Select Date" >
                  {{-- <span class="error_message" id="" style="display: none">Field is required</span> --}}
                </div> 
                
                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="name">Warranty End Date</label>
                    <input type="text" id="warrenty_end_date" name="warrenty_end_date" value="{{ old('warrenty_end_date',optional($import_save)->warrenty_end_date  ?? $ib_import->end_date) }}" class="form-control" placeholder="Select Date" >
                    {{-- <span class="error_message" id="warrenty_end_date_message" style="display: none">Field is required</span>

                     @if($errors->has('warrenty_end_date')) <span class="error_message">{{ $errors->first('warrenty_end_date') }}</span> @endif --}}

                </div>


                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="supplay_order">Supply Order No.</label>
                  <input type="text" id="" readonly name="supplay_order_read" value="" class="form-control" placeholder="Supply Order No">
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="supplay_order">Supply Order No.</label>
                    <input type="text" id="supplay_order"  name="supplay_order" value="{{ old('supplay_order',optional($import_save)->supplay_order) }}" class="form-control" placeholder="Supply Order No">
                    <span class="error_message" id="supplay_order_message" style="display: none">Field is required</span>

                     @if($errors->has('supplay_order')) <span class="error_message">{{ $errors->first('supplay_order') }}</span> @endif

                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="">Invoice No.</label>
                  <input type="text" id="" readonly name="invoice_number_read" value="" class="form-control" placeholder="Invoice No">
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="invoice_number">Invoice No.</label>
                    <input type="text" id="invoice_number" name="invoice_number" value="{{ old('invoice_number',optional($import_save)->invoice_number) }}" class="form-control" placeholder="Invoice No">
                    <span class="error_message" id="invoice_number_message" style="display: none">Field is required</span>

                     @if($errors->has('invoice_number')) <span class="error_message">{{ $errors->first('invoice_number') }}</span> @endif

                </div>


                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="">Invoice date</label>
                  <input type="text" id="" readonly name="invoice_date_read" value="" class="form-control" placeholder="Invoice date" readonly>
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div> 
                
                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="invoice_date">Invoice date</label>
                    <input type="text" id="invoice_date" name="invoice_date" value="{{ old('invoice_date',optional($import_save)->invoice_date) }}" class="form-control" placeholder="Invoice date" >
                    <span class="error_message" id="invoice_date_message" style="display: none">Field is required</span>

                     @if($errors->has('invoice_date')) <span class="error_message">{{ $errors->first('invoice_date') }}</span> @endif

                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="">Description*</label>
                  <textarea id="" readonly name="description_read" class="form-control" placeholder=""></textarea>
                  <span class="error_message" id="" style="display: none">Field is required</span>
                </div> 

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="name">Description*</label>
                    <textarea id="description"  name="description" class="form-control" placeholder="">{{ old('description',optional($import_save)->description) }}</textarea>
                    <span class="error_message" id="description_message" style="display: none">Field is required</span>

                    
                     @if($errors->has('description')) <span class="error_message">{{ $errors->first('description') }}</span> @endif

                </div> 

              </div>
              <!-- /.box-body -->

              <div class="box-footer">

                <input type="submit" class="btn btn-primary" name="submit" value="Save">

                <input type="submit" class="btn btn-success" name="conform" value="Conform">
                <!-- <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button> -->
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.staff_import_ib')}}'">Cancel</button>
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
                    dateFormat:'yy-mm-dd',
                    changeYear: true,
                    changeMonth:true,
                    yearRange: "1990:{{intval(date('Y'))+40}}",

                   // minDate: 0  
                });
        $('#warrenty_end_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            changeYear: true,
            changeMonth:true,
            yearRange: "1990:{{intval(date('Y'))+40}}",
           // minDate: 0  
        });
        $('#invoice_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
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

       $(function() {

      var district =$('#district_id').val();

      console.log(district);

      if(district =="" || district == null)
      {
          change_state();
      }

        var customer =$('#user_id').val();

       if(customer =="" || customer == null)
        {
            change_district();
        }
         console.log(customer);

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
        var equipment_status  =   $("#equipment_status_id").val();
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
        if(equipment_status=="")
        {
          $("#equipment_status_id_message").show();
        }
        else{
          $("#equipment_status_id_message").hide();
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
  
        if(external_ref_no!='' && user!='' && department!='' && equipment!='' && equipment_serial!='' && equipment_model!='' && equipment_status!='' && installation_date!='' && description!='')
        {
         $("#frm_ibEdit").submit(); 
        }


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
  var state_id=$("#state_id").val();
  var district_id=$("#district_id").val();
  var url = APP_URL+'/staff/get_client_use_state_district';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,district_id:district_id
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
    </script>
@endsection
