

@extends('staff/layouts.app')

@section('title', 'Add Contract')

@section('content')

<section class="content-header">
      <h1>
        Add Contract
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.contract-index')}}">Manage Contract</a></li>
        <li class="active">Add Contract</li>
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
                    $inRefno = 'Contract-'.rand(1000, 100000);
                @endphp
        

            <form role="form" name="frm_contract" id="frm_contract" method="post" action="{{route('staff.contract-store')}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body row">

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Internal Ref No*</label>
                  <input type="text" id="in_ref_no" name="in_ref_no" value="{{ $inRefno }}" class="form-control" placeholder="" readonly>
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">External Ref No*</label>
                  <input type="text" id="ex_ref_no" name="ex_ref_no" value="" class="form-control" placeholder="External Ref No">
                  <span class="error_message" id="external_ref_no_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label for="name">Contract Name*</label>
                    <input type="text" id="contract_name" name="contract_name" value="" class="form-control" placeholder="Contract Name">
                    <span class="error_message" id="contract_name_no_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Customer*</label>
                    <select class="form-control" name="user_id" id="user_id">
                         <option value="">-- Select Customer --</option>
                             @foreach($users as $user)
                               @if(app('request')->input('user_id') == $user->id) 
                                    $sel = 'selected';
                                @endif
                                <option value="{{ $user->id }}">{{ $user->business_name }}</option>
                             @endforeach    
                    </select>
                    <span class="error_message" id="user_id_message" style="display: none">Field is required</span></br></br>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Contact Person*</label>
                    <select class="form-control" name="department_id" id="department_id">
                         <option value="">-- Select Contact Person --</option>
                               
                    </select>
                    <span class="error_message" id="department_id_message" style="display: none">Field is required</span></br></br>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Equipment*</label>
                    <select class="form-control selectpicker" name="equipment_id" id="equipment_id">
                         <option value="">-- Select Equipment --</option>
                             @foreach($products as $equipment)
                               @if(app('request')->input('equipment_id') == $equipment->id) 
                                    $sel = 'selected';
                                @endif
                                <option value="{{ $equipment->id }}">{{$equipment->name}}</option>
                             @endforeach    
                    </select>
                    <span class="error_message" id="equipment_id_message" style="display: none">Field is required</span></br></br>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Equipment Serial No</label>
                  <input type="text" id="equipment_serial_no" name="equipment_serial_no" value="" class="form-control" placeholder="Equipment Serial No">
                  <span class="error_message" id="equipment_serial_no_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                    <label >Select Equipment Status*</label>
                    <select class="form-control" name="equipment_status_id" id="equipment_status_id">
                         <option value="">-- Select Equipment Status --</option>
                             @foreach($equipment_status as $equipment_stat)
                               @if(app('request')->input('equipment_status_id') == $equipment_stat->id) 
                                    $sel = 'selected';
                                @endif
                                <option value="{{ $equipment_stat->id }}">{{ $equipment_stat->name }}</option>
                             @endforeach    
                    </select>
                    <span class="error_message" id="equipment_status_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Contract Start Date*</label>
                  <input type="text" id="contract_start_date" name="contract_start_date" value="" class="form-control" placeholder="Select Date" readonly>
                  <span class="error_message" id="contract_start_date_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Contract End Date*</label>
                  <input type="text" id="contract_end_date" name="contract_end_date" value="" class="form-control" placeholder="Select Date" readonly>
                  <span class="error_message" id="contract_end_date_message" style="display: none">Field is required</span>
                </div>  

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Contract No</label>
                  <input  id="contract_no" name="contract_no" value="" class="form-control" placeholder="contract_no">
                  <span class="error_message" id="equipment_model_no_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">No.of PM*</label>
                  <input type="text" id="no_of_pm" name="no_of_pm" class="form-control" placeholder="">
                  <span class="error_message" id="no_of_pm_message" style="display: none">Field is required</span>
                </div> 

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Date of PM*</label>
                  <input type="text" id="date_pm" name="date_pm" class="form-control"placeholder="Select Date" readonly>
                  <span class="error_message" id="no_of_pm_message" style="display: none">Field is required</span>
                </div> 

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Amount*</label>
                  <input type="text" id="no_of_pm" name="no_of_pm" class="form-control" placeholder="">
                  <span class="error_message" id="no_of_pm_message" style="display: none">Field is required</span>
                </div> 

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Received Amount*</label>
                  <input type="text" id="no_of_pm" name="no_of_pm" class="form-control" placeholder="">
                  <span class="error_message" id="no_of_pm_message" style="display: none">Field is required</span>
                </div> 

                <div class="form-group col-md-6 col-sm-6 col-lg-6">
                  <label for="name">Billing Date*</label>
                  <input type="text" id="no_of_pm" name="no_of_pm" class="form-control"placeholder="Select Date" readonly>
                  <span class="error_message" id="no_of_pm_message" style="display: none">Field is required</span>
                </div> 

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.contract-index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>

@endsection



