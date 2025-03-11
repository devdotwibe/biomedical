

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
    @php
        $inRefno = 'Contract-'.rand(1000, 100000);
    @endphp

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

                <div class="form-group col-md-4 col-sm-6 col-lg-4">
                  <label for="name">Internal Ref No*</label>
                  <input type="text" id="in_ref_no" name="in_ref_no" value="{{ $inRefno }}" class="form-control" placeholder="" readonly>
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-4 col-sm-6 col-lg-4">
                  <label for="name">External Ref No*</label>
                  <input type="text" id="ex_ref_no" name="ex_ref_no" value="" class="form-control" placeholder="External Ref No">
                  <span class="error_message" id="external_ref_no_message" style="display: none">Field is required</span>
                </div>

            <div class="form-group col-md-4 col-sm-6 col-lg-4">
                  <label>State*</label>
                  <select name="state_id" id="state_id" class="form-control selectpicker" data-live-search="true" onchange="change_state()">
                    <option value="">-- Select State --</option>
                    <?php
                    foreach ($state as $item)
                    {
                        $sel = (old('state_id') == $item->id) ? 'selected' : '';
                        echo '<option value="' . $item->id . '" ' . $sel . '>' . $item->name . '</option>';
                    } ?>
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                 
                </div>

                 <div class="form-group col-md-4 col-sm-6 col-lg-4 ">
                  <label>District*</label>
                  <select name="district_id" id="district_id" class="form-control selectpicker" data-live-search="true" onchange="change_district()">
                    <option value="">-- Select District --</option>
                    <?php
                    foreach ($district as $item)
                    {
                        $sel = (old('district_id') == $item->id) ? 'selected' : '';
                        echo '<option value="' . $item->id . '" ' . $sel . '>' . $item->name . '</option>';
                    } ?>
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                  <div class="loader_district_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                </div>

                   <div class="form-group col-md-4 col-sm-6 col-lg-4">
                  <label for="name">Customer Name *</label>
                  <input type="hidden" name="user_id_hidden" id="user_id_hidden">
                  <input type="hidden" name="oppur_id" id="oppur_id">
                  <input type="hidden" name="tran_type" id="tran_type">
                  <input type="hidden" name="oppur_status" id="oppur_status">
                  <select class="form-control" name="user_id" id="user_id" onchange="">
                    <option value="">Customer Name</option>
                    @foreach($user as $values)
                    <?php
                      echo '<option value="' . $values->id . '" >' . $values->business_name . '</option>';
                    ?>
                    @endforeach
                  </select>
                  <span class="error_message" id="user_id_message" style="display: none">Field is required</span>
                     <div class="loader_user_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                </div>

                <div class="form-group col-md-4 col-sm-6 col-lg-4">
                    <label >Contract Status</label>
                    <select class="form-control" name="contract_status" id="contract_status" onchange="change_contract()">
                         <option value="">-- Select Contract --</option>
                                <option value="new">New Contract</option>
                                <option value="opp">Opportunity Win</option>
                    </select>
                    <span class="error_message" id="equipment_id_message" style="display: none">Field is required</span></br></br>
                </div>

                </div>

                
                
                <div style="display:none" id="oppertunity_div" class="form-group col-md-4 col-sm-6 col-lg-4">

                    </br></br>
                </div>

                <div style="display:none" id="quote_div" class="form-group col-md-4 col-sm-6 col-lg-4">

                    </br></br>
                </div>
                
                <div style="display:none" id="equipment_div" class="form-group col-md-4 col-sm-6 col-lg-4">
                    <label >Select Equipment*</label>
                    <select class="form-control" name="equipment_id" id="equipment_id">
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

                <div style="display:none" id="add_button_div" class="form-group col-md-4 col-sm-6 col-lg-4">
                    <a class="add-product btn btn-primary">Add</a>
                    <span class="error_message" id="equipment_id_message" style="display: none">Field is required</span></br></br>
                </div>

              </div>

              <div class="form-group col-md-12 col-sm-6 col-lg-12 box-body row cmsTable" style="display:none;">

               <table id="cmsTable" class="table table-bordered table-striped data- hideform" style="display:none;">
                <thead>
                  <tr>
                    <th>Name</th>
                  
                    <th>Start Date</th>
              
                    <th>End Date</th>
                    <th>Amount</th>
                    <th>Tax %</th>
                    <th>Total Amount</th>
                    <th>Contract Type</th>
                    <th>No.of PM</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="tabledata">
                    
               </table>
               <br><br>
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

@section('scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />
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
          

      });  
  </script>

<script type="text/javascript">

function change_state(){
  var state_id=$("#state_id").val();
  $("#district_id").html('<option value="">Select District</option>');
  $("#user_id").html('<option value="">Select Client</option>');
  $('#district_id').selectpicker('refresh');
  $('#user_id').selectpicker('refresh');
$(".loader_district_id").show();
  var url = APP_URL+'/staff/change_state';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,
          },
          success: function (data)
          {    $(".loader_district_id").hide();
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='';
            states_val +='<option value="">Select District</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#district_id").html(states_val);
              $('#district_id').selectpicker('refresh');
              
           
          }
        });

  }


  function change_district(){
  var state_id=$("#state_id").val();
  var district_id=$("#district_id").val();
  $("#user_id").val('<option value="">Select Client</option>');
  $('#user_id').selectpicker('refresh');
  $(".loader_user_id").show();
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
            $(".loader_user_id").hide();
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Client</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
           
              }
              $("#user_id").html(states_val);
             
              $('#user_id').selectpicker('refresh');
           
          }
        });
  }

  $('.add-product').on('click', function () { 

    var product_id = $("#equipment_id option:selected").val();
              var APP_URL = {!! json_encode(url('/')) !!};
              var url = APP_URL+'/staff/contract-product';
                  $.ajax
                      ({
                          type: "POST",
                          cache: false,
                          url: url,
                          data:{
                            product_id : product_id
                      },
                    success: function (data)
                    {                            
                      htmls = '';
                      htmls += '<tr id="'+data.product.id+'">';
                      htmls += '<td>'+data.product.name+'</td>';
                      htmls += '<td><input id="start_date" type="text" name="start_date" class="form-control"></td>';
                      htmls += '<td><input type="text" name="end_date" class="form-control end_date"></td>';
                      htmls += '<td><input type="text" name="amount" class="form-control"></td>';
                      htmls += '<td><input type="text" name="amount" class="form-control"></td>';
                      htmls += '<td><input type="text" name="amount" class="form-control"></td>';
                      htmls += '<td><select class="form-control" name="con_type_id" id="con_type_id">'+
                                '<option>--Select Contract Type--</option>'+
                                '<option>AMC</option>'+
                                '<option>HBC</option>'+
                                '<option>CMC</option>'+
                                '</select></td>';
                      htmls += '<td><input type="number" name="no_of_pm" class="form-control"></td>';
                      htmls += '<td><a class="del_product" attr-product_id="'+data.product.id+'"><img src="{{ asset("images/delete.svg") }}"></a></td>';
                      $('#tabledata').append(htmls);
                    }
                    
                  }); 
                  $('.cmsTable').show();
                  $('#cmsTable').show();
                  $('.start_date').datepicker({
                      //dateFormat:'yy-mm-dd',
                      dateFormat:'yy-mm-dd',
                      changeYear: true,
                      yearRange: "1990:2040",
                      // minDate: 0  
                  });
                  $('.end_date').datepicker({
                      //dateFormat:'yy-mm-dd',
                      dateFormat:'yy-mm-dd',
                      changeYear: true,
                      yearRange: "1990:2040",
                      //minDate: 0  
                  });
                  
  });
  $( "#tabledata").on('click', '.del_product', function() {
    $(this).closest('tr').remove();
  });

  function change_contract(){

    var contract_status=$("#contract_status").val();
    if(contract_status == 'new'){
      alert('new');
      $("#equipment_div").show();
      $('#oppertunity_div').hide();
      $("#quote_id").hide();
      $("#add_button_div").show();
    }else{
      alert('opp');
      $("#add_button_div").hide();
      var user_id = $("#user_id").val();
      alert(user_id);
      var APP_URL = {!! json_encode(url('/')) !!};
              var url = APP_URL+'/staff/contract-oppertunity';
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
                      $('#oppertunity_div').show();
                      $("#equipment_div").hide();
                      var opp_option = ''; 
                       opp_option +='<label>Select Opportunity</label>';                    
                       opp_option +='<select id="oppertunity_id" name="oppertunity_id" class="form-control" onchange="change_oppertunity()"><option>----Select Oppertunity----</option>';
                      $.each( data.oppertunities, function( key, value ) {
                        opp_option +='<option value="'+value.id+'">'+value.oppertunity_name+'</option>';
                      });  
                      opp_option +='</select>';
                      $('#oppertunity_div').html(opp_option);
                    }                  
                    
                  }); 
      
    }
  }
  function change_oppertunity(){

        var oppertunity_id=$("#oppertunity_id").val();
        alert(oppertunity_id);
          var APP_URL = {!! json_encode(url('/')) !!};
                  var url = APP_URL+'/staff/contract-quote';
                      $.ajax
                          ({
                              type: "POST",
                              cache: false,
                              url: url,
                              data:{
                                oppertunity_id : oppertunity_id
                          },
                        success: function (data)
                        { 
                          $('#quote_div').show();
                          var quote_option = ''; 
                          quote_option +='<label>Select Quote</label>';                    
                          quote_option +='<select id="quote_id" name="quote_id" class="form-control" onchange="change_quote()"><option>----Select Oppertunity----</option>';
                          $.each( data.quotes, function( key, value) {
                            quote_option +='<option value="'+value.id+'">'+value.quote_reference_no+'</option>';
                          });  
                          quote_option +='</select>';
                          $('#quote_div').html(quote_option);
                        }                  
                        
                      }); 
          
        }

function change_quote(){

var quote_id=$("#quote_id").val();
alert(quote_id);
  var APP_URL = {!! json_encode(url('/')) !!};
          var url = APP_URL+'/staff/contract-quote-product';
              $.ajax
                  ({
                      type: "POST",
                      cache: false,
                      url: url,
                      data:{
                        quote_id : quote_id
                  },
                success: function (data)
                { 
                  htmls = '';
                  $.each( data.oppProducts, function( key, value) {
                      htmls += '<tr id="'+value.id+'">';
                      htmls += '<td>'+value.oppertunity_product.name+'</td>';
                      htmls += '<td><input id="start_date" type="text" name="start_date" class="form-control"></td>';
                      htmls += '<td><input type="text" name="end_date" class="form-control end_date"></td>';
                      htmls += '<td><input type="text" name="single_amount" value="'+value.single_amount+'" class="form-control"></td>';
                      htmls += '<td><input type="text" name="tax" value="'+value.tax+'%" class="form-control"></td>';
                      htmls += '<td><input type="text" name="amount" value="'+value.amount+'" class="form-control"></td>';
                      htmls += '<td><select class="form-control" name="con_type_id" id="con_type_id">'+
                                '<option>--Select Contract Type--</option>'+
                                '<option>AMC</option>'+
                                '<option>HBC</option>'+
                                '<option>CMC</option>'+
                                '</select></td>';
                      htmls += '<td><input type="number" name="no_of_pm" value="'+value.pm+'" class="form-control"></td>';
                      htmls += '<td><a class="del_product" attr-product_id=""><img src="{{ asset("images/delete.svg") }}"></a></td>';
                    }); 
                      $('#tabledata').append(htmls);
                }                  
              }); 
              $('.cmsTable').show();
                  $('#cmsTable').show();
                  $('.start_date').datepicker({
                      //dateFormat:'yy-mm-dd',
                      dateFormat:'yy-mm-dd',
                      changeYear: true,
                      yearRange: "1990:2040",
                      // minDate: 0  
                  });
                  $('.end_date').datepicker({
                      //dateFormat:'yy-mm-dd',
                      dateFormat:'yy-mm-dd',
                      changeYear: true,
                      yearRange: "1990:2040",
                      //minDate: 0  
                  });
  
}
  </script>
  @endsection



