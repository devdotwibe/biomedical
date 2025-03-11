

@extends('staff/layouts.app')

@section('title', 'Add Opportunity')

@section('content')


<section class="content-header">
      <h1>
        Add Opportunity
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/list_oppertunity')}}">Manage Opportunity</a></li>
        <li class="active">Add Opportunity</li>
      </ol>
</section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-10">
          <!-- general form elements -->
          <div class="box box-primary">
<!--            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>-->
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
            <form role="form" name="frm_products" id="frm_products" method="post"  enctype="multipart/form-data" >
               @csrf
              <div class="box-body">

                <div class="form-group col-md-6">
                  <label for="name">Oppertunity Reference No*</label>
                  @php
                    $rand = 'op'.mt_rand(000000,999999);
                  @endphp
                  <input type="text" id="op_ref" name="op_ref" class="form-control"  value="{{$rand}}" readonly="">
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Opportunity Name*</label>
                  <input type="text" id="name" name="op_name" class="form-control" placeholder="Opportunity Name" value="{{ old('op_name') }}" >
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
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                </div>

                
                <div class="form-group  col-md-6">
                  <label>Account Name*</label>
                  <select name="account_name" id="account_name" class="form-control">
                    <option value="">-- Select Account Name --</option>
                    
                  </select>
                </div>

                <div class="form-group  col-md-6">
                  <label>Deal Stage*</label>
                  <select name="deal_stage" id="deal_stage" class="form-control">
                    <option value="">-- Select Deal stage --</option>
                    <option value="0">Lead Qualified/Key Contact Identified</option>
                    <option value="1">Customer needs analysis</option>
                    <option value="2">Clinical and technical presentation/Demo</option>
                    <option value="3">CPQ(Configure,Price,Quote)</option>
                    <option value="4">Customer Evaluation</option>
                    <option value="5">Final Negotiation</option>
                    <option value="6">Closed-Lost</option>
                    <option value="7">Closed-Cancel</option>
                    <option value="8">Closed Won - Implement</option>
                  </select>
                </div>

                <div class="form-group col-md-6">
                  <label for="name">Es.Order Date*</label>
                  <input type="text" id="order_date" name="order_date" value="{{ old('order_date')}}" class="form-control" placeholder="Es.Order Date">
                  <span class="error_message" id="order_date_message" style="display: none">Field is required</span>
                </div>

                

                <div class="form-group col-md-6">
                  <label for="name">Es.Sales Date*</label>
                  <input type="text" id="sales_date" name="sales_date" value="{{ old('sales_date')}}" class="form-control" placeholder="Es.Sales Date">
                  <span class="error_message" id="sales_date_message" style="display: none">Field is required</span>
                </div>
                
              

              <div class="form-group  col-md-6">
                  <label>Order Forcast Category*</label>
                  <select name="order_forcast" id="order_forcast" class="form-control">
                    <option value="">-- Select Deal stage --</option>
                    <option value="0">Unqualified</option>
                    <option value="1">Not addressable</option>
                    <option value="2">Open</option>
                    <option value="3">Upside</option>
                    <option value="4">Committed w/risk</option>
                    <option value="5">Committed</option>
                  </select>
              </div>

              <div class="form-group col-md-6">
                <label for="name">Amount</label>
                  <input type="text" id="amount" name="amount" value="{{ old('amount')}}" class="form-control" placeholder="Enter amount">
              </div>

              <div class="form-group  col-md-6">
                  <label>Support</label>
                  <select name="support" id="support" class="form-control">
                    <option value="">-- Select Support --</option>
                    <option value="0">Demo</option>
                    <option value="1">Application/ clinical support</option>
                    <option value="2">Direct company support</option>
                    <option value="3">Senior Engineer Support</option>
                    <option value="4">Price deviation </option>
                  </select>
              </div>

              <div class="form-group  col-md-6">
                  <label>Type*</label>
                  <select name="type" id="type" class="form-control">
                     <option value="">-- Select Type --</option>
                     <option value="1">Sales</option>
                     <option value="2">Contract</option>
                     <option value="3">HBS</option>
                  </select>
              </div>

              <div class="form-group col-md-12">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="10" cols="80" placeholder="Description">{{ old('description') }}</textarea>
              </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer col-md-12">
                <input type="submit" class="btn btn-primary">
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{url('staff/list_oppertunity')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>




@endsection

@section('scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
  $('#account_name').select2();
  $('#engineer_name').select2();
  
</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript">

        
    $('#order_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
         
         
            minDate: 0  
            
    });
    $('#sales_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
           
         
            minDate: 0  
            
        });
</script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

  <script type="text/javascript">

$(document).ready(function() {
//$('#category_type_id').multiselect();
$('#category_id').multiselect();
/*$('#related_products').multiselect({
  enableClickableOptGroups: true
});*/


});

$(document).ready(function() {
    $('#related_products').multiselect({
        enableCollapsibleOptGroups: true,
        buttonContainer: '<div id="related_products" />'
    });
    $('#related_products .caret-container').click();
});

$(document).ready(function() {
    $('#competition_product').multiselect({
        enableCollapsibleOptGroups: true,
        buttonContainer: '<div id="competition_product" />'
    });
    $('#competition_product .caret-container').click();
});


 </script>

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

    function change_district(){
      var district_id=$("#district").val();
      var state_id=$("#state").val();
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
                  $("#account_name").html(states_val);
                 
              }
            });

  }
  </script>

@endsection
