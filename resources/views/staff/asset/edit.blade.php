@extends('staff/layouts.app')

@section('title', 'Edit Asset')

@section('content')

<section class="content-header">
    <h1>
      Edit Asset
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{route('staff.asset')}}">Manage Asset</a></li>
      <li class="active">Edit Asset</li>
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

            <form method="post" action="{{ route('staff.asset.update', [$asset->id]) }}" enctype="multipart/form-data">
               @csrf
              <div class="box-body">

                <div class="form-group col-md-6">
                  <label>Asset No</label>
                  <input type="text" name="asset_no" class="form-control"  value="{{ old('asset_no',$asset->asset_no)}}">
                  
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Serial No</label>
                  <input type="text" name="serial_no" class="form-control"  value="{{ old('serial_no',$asset->serial_no)}}" readonly="">
                </div>
                
                <div class="form-group col-md-6">
                    <label >System Id</label>
                    <input type="text" name="system_id" value="{{ old('system_id',$asset->system_id)}}" class="form-control" placeholder="System Id">
                </div>

                <div class="form-group col-md-6">
                    <label >Company</label>
                    <input type="text" name="company" value="{{ old('company',$asset->company)}}" class="form-control" placeholder="Company">
                </div>

                <div class="form-group col-md-6">
                    <label >Product No</label>
                    <input type="text" name="product_no" value="{{ old('product_no',$asset->product_no)}}" class="form-control" placeholder="Product No">
                </div>

                <div class="form-group col-md-6">
                    <label >Product Description</label>
                    <input type="text" name="product_descrption" value="{{ old('product_descrption',$asset->product_descrption)}}" class="form-control" placeholder="Product Description">
                </div>

                <div class="form-group col-md-6">
                    <label >Asset Segment</label>
                    <input type="text" name="assign_segment" value="{{ old('assign_segment',$asset->assign_segment)}}" class="form-control" placeholder="Segment">
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Modality*</label>
                  <select id="modality" name="modality" class="form-control"> 
                    <option value="">Select Modality</option>
                    <?php
                      foreach($modality as $item) {
                        $sel = ($asset->modality == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
      
                      }
                    ?>
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">State*</label>
                  <select id="state" name="state" class="form-control" onchange="change_state()"> 
                    <option value="">Select State</option>
                    @foreach($state as $values)
                    <?php
                      $sel = ($asset->state == $values->id) ? 'selected': '';
                      echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
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
                      $sel = ($asset->district == $values->id) ? 'selected': '';
                      echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label>Account Name*</label>
                  <select name="account_name" id="account_name" class="form-control">
                    <option value="">-- Select Account Name --</option>
                    @foreach($customer as $item) 
                       <option value='{{$item->id}}' @if(old('account_name',$asset->account_name)==$item->id){{'selected'}} @endif>{{$item->business_name}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="form-group col-md-6">
                    <label >Installed At*</label>
                    <input type="text" name="installed_at" value="{{ old('installed_at',$asset->installed_at)}}" class="form-control" placeholder="Installed At">
                </div>
                
                <div class="form-group  col-md-6">
                  <label>Product Type*</label>
                  <select name="asset_description" id="asset_description" class="form-control">
                    <option value="">-- Select Product Type --</option>
                    <?php
                      foreach($product_type as $item) {
                        $sel = ($asset->asset_description == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
      
                      }
                    ?>
                  </select>
                </div>

                <div class="form-group  col-md-6">
                  <label>Brand*</label>
                  <select name="manufacturer" id="manufacturer" class="form-control">
                    <option value="">-- Select Brand --</option>
                    <?php
                    foreach($brand as $item) {
                      $sel = ($asset->manufacturer == $item->id) ? 'selected': '';
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
                     
                      $sel = ($asset->product_name == $item->id) ? 'selected': '';
                      echo '<option value="'.$item->id.','.$item->product_type.'" '.$sel.'>'.$item->name.'</option>';
                       
                    } ?>
                  </select>
                </div>

                <div class="form-group col-md-6">
                    <label >Department</label>
                    <select name="department" class="form-control">
                    <option value="">-- Select Department --</option>
                    <?php
                    foreach($department as $item) {
                      $sel = ($asset->department == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                    } ?>
                  </select>
                </div>

                <div class="form-group  col-md-6">
                  <label>Equipment Status</label>
                  <select name="equipment_status" class="form-control">
                    <option value="">-- Select Equipment Status --</option>
                    <option value="working" @if ($asset->equipment_status == 'working') {{ 'selected' }} @endif>Working</option>
                    <option value="not_working" @if ($asset->equipment_status == 'not_working') {{ 'selected' }} @endif>Not Working</option>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Installed On</label>
                  <input type="text" id="installed_on" name="installed_on" value="{{ old('installed_on',$asset->installed_on)}}" class="form-control" placeholder="Installed On">
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
    $('#account_name').select2();
     $('.product_id').select2();
  </script>
  
@endsection


