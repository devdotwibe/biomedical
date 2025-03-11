@extends('staff/layouts.app')

@section('title', 'Manage Assets')

@section('content')


<section class="content-header">
      <h1>
        Assets
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Assets</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

            <div class="row">

              <div class="col-lg-12 margin-tb">

                  <div class="pull-left">

                      <a class="btn btn-sm btn-success" href="{{ route('staff.asset.show') }}"> <span class="glyphicon glyphicon-plus"></span>Add Asset</a>

                  </div>

                  <form action="{{route('admin.asset')}}" method="get">
                    <div class="pull-right">
                        <input type="text" name="q" id="q" value="{{ app('request')->input('q') }}" placeholder="Search...!" class="form-control"/>
                    </div>
                  </form>

              </div>

            </div>
            @if (session('success'))
                <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/asset/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th>No.</th>
                  <th>Serial No</th>
                  <th>Installed At</th>
                  <th>State</th>
                  <th>District</th>
                  <th>Account Name</th>
                  <th>Product Description</th>
                  <th>Product Type</th>
                  <th>Brand</th>
                  <th>Product Name</th>
                  <th>Modality</th>
                 
                  
                  <!-- <th>Thumbnail</th> -->
                 
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach($assets as $product)
 
                      <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="assign_supervisor">
                          <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                          </td>
                          <td>
                              <span class="slNo">{{$i}} </span>
                          </td>
                          <td><input type="text" class="form-control" id="serial_no_{{$product->id}}" value="<?php echo $product->serial_no ?>" style="width: 60px;"></td>
                          <td><input type="text" class="form-control" id="installed_at_{{$product->id}}" value="<?php echo $product->installed_at ?>" style="width: 60px;"></td>
                          <td>
                            <select id="state" name="state" class="form-control state_{{$product->id}}" onchange="change_state({{ $product->id }})"> 
                              <option value="">Select State</option>
                              @foreach($state as $values)
                              <?php
                                $sel = ($product->state == $values->id) ? 'selected': '';
                                echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                              ?>
                              @endforeach
                            </select>
                          </td>

                          <td>
                            <select id="district" name="district" class="form-control district_{{$product->id}}" onchange="change_district({{ $product->id }})">
                              <option value="">Select District</option>
                              @foreach($district as $values)
                              <?php
                                $sel = ($product->district == $values->id) ? 'selected': '';
                                 echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                              ?>
                              @endforeach
                            </select>
                          </td>

                          <td>
                            <select name="account_name" id="account_id_{{$product->id}}" class="form-control account_name select-width">
                              <option value="">-- Select Account Name --</option>
                              @foreach($customer as $item) 
                                 <option value='{{$item->id}}' @if(old('account_name',$product->account_name)==$item->id){{'selected'}} @endif>{{$item->business_name}}</option>
                              @endforeach
                            </select>
                          </td>
                          <td><input type="text" class="form-control" id="description_{{$product->id}}" value="<?php echo $product->product_descrption ?>" style="width: 60px;"></td>
                          <td>
                            <select name="asset_description" id="asset_description_{{$product->id}}" class="form-control">
                              <option value="">-- Select Product Type --</option>
                              <?php
                                foreach($product_type as $item) {
                                  $sel = ($product->asset_description == $item->id) ? 'selected': '';
                                    echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                
                                }
                              ?>
                            </select>
                          </td>

                          <td>
                            <select name="manufacturer" id="manufacturer_{{$product->id}}" class="form-control">
                              <option value="">-- Select Brand --</option>
                              <?php
                              foreach($brand as $item) {
                                $sel = ($product->manufacturer == $item->id) ? 'selected': '';
                                  echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                                 
                              } ?>
                            </select>
                          </td>

                          <td>
                            <select name="product_name" id="product_id_{{$product->id}}" class="form-control product_name select-width">
                              <option value="">-- Select Product --</option>
                              <?php
                              foreach($products as $item) {
                               
                                $sel = ($product->product_name == $item->id) ? 'selected': '';
                                echo '<option value="'.$item->id.','.$item->product_type.'" '.$sel.'>'.$item->name.'</option>';
                                 
                              } ?>
                            </select>
                          </td>

                          <td>
                            <select id="modality_{{$product->id}}" name="modality" class="form-control"> 
                              <option value="">Select Modality</option>
                              <?php
                                foreach($modality as $item) {
                                  $sel = ($product->modality == $item->id) ? 'selected': '';
                                    echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                
                                }
                              ?>
                            </select>
                          </td>

                          <td class="alignCenter">
                              <a class="btn btn-primary btn-xs" onclick="save( {{ $product->id }} );" title="Save"><span class="glyphicon glyphicon-save"></span></a>
                              <a class="btn btn-primary btn-xs" href="{{ route('staff.asset.edit',$product->id) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                              <a class="btn btn-danger btn-xs deleteItem" href="asset/destroy/{{ $product->id }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                          </td>
                      </tr>


                       <?php $i++ ?>
                    @endforeach

                    <?php if(count($assets) > 0) { ?>
                    
              <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('assets');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a>
              </div>
               
              <?php } ?>
              </table>
            </form>
            </div>

            <div class="pull-right">
              {{ $assets->links() }}
            </div>
            
          <!-- /.box-body -->
          </div>
        <!-- /.box -->
        </div>
      <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>

@endsection

@section('scripts')
  
  <script type="text/javascript">
    function save(id){
      
      var installed_at        = $("#installed_at_"+id).val();
      var state               = $(".state_"+id).val();
      var district            = $(".district_"+id).val();
      var asset_description   = $("#asset_description_"+id).val();
      var manufacturer        = $("#manufacturer_"+id).val();
      var modality            = $("#modality_"+id).val();
      var product             = $("#product_id_"+id).val();
      var account             = $("#account_id_"+id).val();
      var serialNo            = $("#serial_no_"+id).val();
      var description         = $("#description_"+id).val();

      var url = APP_URL+'/staff/asset/save';
      $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              id:id,installed_at: installed_at,state: state,district: district,asset_description: asset_description,account_name:account,manufacturer: manufacturer,modality: modality,product:product,serialNo:serialNo,description:description
            },
            success: function (data)
            {   
              location.reload();
            }
    });
      
    }
  </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

  <script>
    $('.account_name').select2();
    $('.product_name').select2();
  </script>

<script type="text/javascript">
    jQuery(document).ready(function() {

        // var oTable = $('#cmsTable').DataTable({
        });
 
</script>

<script type="text/javascript">

  function change_state(id){
    var state_id=$(".state_"+id).val();
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
              $(".district_"+id).html(states_val);
                 
            }
    });

  }

  function change_district(id){
    var district_id=$(".district_"+id).val();
    var state_id=$(".state_"+id).val();
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
              $("#account_id_"+id).html(states_val);
                 
            }
    });

  }

</script>

<script type="text/javascript">
  $( "#q" ).keyup(function() {
    var q = $("#q").val();
    var url = '{{ route("staff.asset", "q=id") }}';
    url = url.replace('id', q);
    document.location.href=url;
  });
</script>
  
@endsection
