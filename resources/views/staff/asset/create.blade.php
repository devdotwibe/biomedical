@extends('staff/layouts.app')

@section('title', 'Add Asset')

@section('content')

<section class="content-header">
    <h1>
      Add Asset
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{route('staff.asset')}}">Manage Asset</a></li>
      <li class="active">Add Asset</li>
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

           @if (count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif


            <p class="error-content alert-danger">
            
            </p>

            <form method="post" action="{{route('staff.asset.store')}}" enctype="multipart/form-data">
               @csrf
              <div class="box-body">

                <div class="form-group col-md-6">
                  <label>Asset No</label>
                  <input type="text" id="" name="asset_no" value="{{ old('asset_no')}}" class="form-control" placeholder="Asset No">
                  
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Serial No</label>
                  <input type="text" name="serial_no" value="{{ old('serial_no')}}" class="form-control" placeholder="Serial No">
                </div>
                
                <div class="form-group col-md-6">
                    <label >System Id</label>
                    <input type="text" name="system_id" value="{{ old('system_id')}}" class="form-control" placeholder="System Id">
                </div>

                <div class="form-group col-md-6">
                    <label >Company</label>
                    <input type="text" name="company" value="{{ old('company')}}" class="form-control" placeholder="Company">
                </div>

                <div class="form-group col-md-6">
                    <label >Product No</label>
                    <input type="text" name="product_no" value="{{ old('product_no')}}" class="form-control" placeholder="Product No">
                </div>

                <div class="form-group col-md-6">
                    <label >Product Description</label>
                    <input type="text" name="product_descrption" value="{{ old('product_descrption')}}" class="form-control" placeholder="Product Description">
                </div>

                <div class="form-group col-md-6">
                    <label >Asset Segment</label>
                    <input type="text" name="assign_segment" value="{{ old('assign_segment')}}" class="form-control" placeholder="Segment">
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Modality*</label>
                  <select id="modality" name="modality" class="form-control"> 
                    <option value="">Select Modality</option>
                    @foreach($modality->sortBy(fn($value) => strtolower($value->name)) as $values)
                      <option value="{{ $values->id }}">{{ $values->name }}</option>
                    @endforeach
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                </div>
                
                

                <div class="form-group col-md-6">
                  <label for="name">State*</label>
                  <select id="state" name="state" class="form-control" onchange="change_state()"> 
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
                  <select id="district" name="district" class="form-control" onchange="change_district()">
                    <option value="">Select District</option>
                    @foreach($district as $values)
                    <?php
                      echo '<option value="'.$values->id.'" >'.$values->name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label>Account Name*</label>
                  <select name="account_name" id="account_name" class="form-control">
                    <option value="">-- Select Customer --</option>
                  </select>
                </div>

                <div class="form-group col-md-6">
                    <label >Installed At*</label>
                    <input type="text" name="installed_at" value="{{ old('installed_at')}}" class="form-control" placeholder="Installed At">
                </div>

                <div class="form-group  col-md-6">
                  <label>Product Type*</label>
                  <select name="asset_description" id="asset_description" class="form-control">
                    <option value="">-- Select Product Type --</option>
                    <?php
                    foreach($product_type as $item) {
                      $sel = (old('asset_desc') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                </div>

                <div class="form-group  col-md-6">
                  <label>Brand*</label>
                  <select name="manufacturer" class="form-control">
                    <option value="">-- Select Brand --</option>
                    <?php
                    foreach($brand as $item) {
                      $sel = (old('category_id') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                    } ?>
                  </select>
                </div>

                <div class="form-group col-md-6">
                    <label >Product Name*</label>
                    <select name="product_name" id="product_id" class="form-control product_id">
                    <option value="">-- Select Product --</option>
                    <?php
                    foreach($products as $item) {
                     
                        echo '<option value="'.$item->id.','.$item->product_type.'" >'.$item->name.'</option>';
                       
                    } ?>
                  </select>
                </div>

                <div class="form-group col-md-6">
                    <label >Department</label>
                    <select name="department" class="form-control">
                    <option value="">-- Select Department --</option>
                    <?php
                    foreach($department as $item) {
                      $sel = (old('department') == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
    
                    } ?>
                  </select>
                </div>

                <div class="form-group  col-md-6">
                  <label>Equipment Status</label>
                  <select name="equipment_status" class="form-control">
                    <option value="">-- Select Equipment Status --</option>
                    <option value="working">Working</option>
                    <option value="not_working">Not Working</option>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Installed On</label>
                  <input type="text" id = "installed_on" name="installed_on" value="{{ old('sales_date')}}" class="form-control" placeholder="Installed On">
                  <span class="error_message" id="sales_date_message" style="display: none">Field is required</span>
                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.asset')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')

  <script type="text/javascript">
    function change_state(){
      var state_id=$("#state").val();
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
                  $("#district").html(states_val);
                 
              }
            });

    }

    function change_district() {
    var district_id = $("#district").val();
    var state_id = $("#state").val();
    var url = APP_URL + '/staff/get_client_use_state_district';

    $.ajax({
        type: "POST",
        cache: false,
        url: url,
        data: {
            district_id: district_id,
            state_id: state_id
        },
        success: function(data) {
            var proObj = JSON.parse(data);

            proObj.sort(function(a, b) {
                return a.business_name.toLowerCase().localeCompare(b.business_name.toLowerCase());
            });

            var states_val = '';
            states_val += '<option value="">Select Customer</option>';

            for (var i = 0; i < proObj.length; i++) {
                states_val += '<option value="' + proObj[i]["id"] + '">' + proObj[i]["business_name"] + '</option>';
            }

            $("#account_name").html(states_val);
        }
    });
}

  </script> 

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
  <script type="text/javascript">  
    $('#installed_on').datepicker({
      //dateFormat:'yy-mm-dd',
      dateFormat:'yy-mm-dd',
            
    });
  </script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  
  <script>
    $('.product_id').select2();
  </script>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    const selectElement = document.getElementById('asset_description');
    const options = Array.from(selectElement.options).slice(1); 
    
    options.sort((a, b) => a.text.localeCompare(b.text));
    
    options.forEach(option => selectElement.add(option));
  });
</script>

@endsection


