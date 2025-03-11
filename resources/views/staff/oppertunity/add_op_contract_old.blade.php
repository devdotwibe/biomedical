

@extends('staff/layouts.app')

@section('title', 'Add Product')

@section('content')

<section class="content-header">
      <h1>
        Add Product
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/list_oppertunity')}}">Manage Opportunity</a></li>
        <li class="active">Add Product ({{$oppertunity->oppertunity_name}})</li>
      </ol>
    </section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12 crte-oprty-page">
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

            @if (count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <h3><b>{{$oppertunity->oppertunity_name}}</b></h3>
            <form   role="form" name="frm_company" id="frm_company" method="post" action="{{ route('staff.oppertunity_contract_product_store') }}"  enctype="multipart/form-data" >
               @csrf
                <div class="box-body">

              <div class="row oprty-crete-row">

          
                <input type="hidden" value="{{ $oppertunity->id}}" id="oppertunity_id" name="oppertunity_id">
                <input type="hidden" value="{{ $oppertunity->user_id}}" id="oppertunity_user_id" name="oppertunity_user_id">
                <input type="hidden" value="{{ $asset }}" id="asset" name="asset">
                <div class="form-group col-md-12">
                  <h4>Select Product</h4> 
                    <table class="table table-bordered table-striped ">
                      <thead>
                        <tr>
                          <th>Sl.No</th>
                          <th>Equipment</th>
                          <th>Serial. No.</th>
                          <th>Warrenty Date</th>
                        </tr>
                      </thead>
                      <tbody id="product_list">

                        @foreach($products as $k=> $item) 
                            <tr>
                              <td>
                                <span>{{$k+1}} <input type="checkbox" name="product_id[]" class="product_chbox"  id="{{ $item->id }}" value="{{ $item->id }}" @if(empty($item->ibEquipmentStatus)) disabled @endif></span>
                              </td>
                              <td>{{$item->ibProduct->name??""}}</td>
                              <td>{{$item->equipment_serial_no??""}}</td>
                              <td>{{$item->warrenty_end_date?? ' '}}</td>
                            </tr> 
                        @endforeach
                      </tbody>
                    </table> 
                  <!-- <select name="product_id" id="product_id" class="form-control product_id">
                    <option value="">-- Select Product --</option>
                    
                    @foreach($products as $item) {
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                   
                  </select> -->
                  <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                  <!-- <span class="rows_selected" id="select_count" style="display: none"></span><br> -->
                </div>
                <!-- <div class="form-group col-md-3">
                  <label>Quantity*</label>
                
                  <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-3">
                  <label>Sale Amount</label>
                  
                  <span class="error_message" id="sale_message" style="display: none">Invalid amount. Please contact admin</span>
                  <div id="samt"></div>
                </div>
                 -->
               
              <div class="box-footer col-md-12 pd-lr-none">
              <br>
              <span class="error_message" id="alreadytexit" style="display: none">Product already exit</span>
                <button type="button" class="mdm-btn submit-btn  "  onclick="add_product()">Add</button>
              
              </div>

              </div>

              <!-- /.box-body -->
            <div class="col-md-12">
                <table id="contractProduct" class="table table-bordered table-striped data-" style="display:none;">
                    <thead>
                    <tr>
                    
                        <th>No.</th>
                        <th>Name</th>
                        <th>SL#</th>
                        <th>PMs</th>
                        <th>CRs</th>
                        <th>Amount</th>
                        <th>Tax Amount  18% </th>
                        <th>Total</th>
                        {{-- <th>%</th> --}}
                        <th>Amount Before Ex</th>
                        <th>Ex Date</th>
                        <th>Ex. Type</th>
                        <th>Action</th>

                    </tr>
                    </thead>
                    <tbody id="contractProductBody">
                    
                    </tbody>  
                </table>
            </div> 

            <div class="box-footer col-md-12">
  
                <button type="submit" id="save_btn" style="display:none;" class="mdm-btn submit-btn " >Save</button>

              </div>


           </form>

          </div>

        </div>
      </div>
</section>



@endsection

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

  <script type="text/javascript">

    $(document).ready(function() {
      $('#opt_pdt').multiselect();
      $('#cmsTable').hide();
      $('#contractProduct').hide();
      var type = $('#asset').val();

    $("#save_btn").click(function() {
      var product_id=$("#product_id").val();
      var op_id=$("#op_id").val();
    //  alert(op_id)
      /*
        var url = APP_URL+'/admin/get_product_exit_oppertunity';
        $.ajax({
                    type: "POST",
                    cache: false,
                    url: url,
                    data:{
                      product_id: pid,
                    },
                    success: function (data)
                    {  

                    }
        });*/
    });
       


    });
  </script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  
  <script>
    $('.product_id').select2();
    
  </script>

  <script type="text/javascript">
    

  </script>


    <script type="text/javascript">
    var product = [];
  function add_product()
  {
      $('#contractProduct').show();
      $('#save_btn').show();
      
      var product_save = [];
    $("input:checkbox[name='product_id[]']:checked").each(function() { 
      product.push($(this).val()); 
      product_save.push($(this).val()); 
    });
    //alert(product);
      var id = $("#product_id").val();
      //var oppertunity_user_id = $("#oppertunity_user_id").val();
      
            var APP_URL = {!! json_encode(url('/')) !!};
           
            var url = APP_URL+'/staff/oppertunity_contract_product';
            

                $.ajax
                    ({
                        type: "POST",
                        cache: false,
                        url: url,
                        data:{
                            product : product_save,
                        oppertunity_id:{{$oppertunity->id}},
                            
                    },
                success: function (data)
                {     
                  html_contract_product = " ";  
                  html_product_history = " ";
                  $.each(data.products, function (key, value){ 
                      html_contract_product +="<tr><td>"+parseFloat(key + 1)+"<input type='hidden' name='ib_id[]' value='"+value.id+"'><input type='hidden' name='product_ids[]' value='"+value.ib_product.id+"'></td>";
                      html_contract_product +="<td>"+value.ib_product.name+"</td>";
                      html_contract_product +="<td>"+value.equipment_serial_no+"</td>";
                      html_contract_product +="<td><input name='pm[]' type='text'></td>";
                      html_contract_product +="<td><input name='cr[]' type='text'></td>";
                      html_contract_product +="<td><input class='amount' name='amount[]' id='amount_"+value.id+"' attr-ib_id='"+value.id+"' type='text' value=''></td>"; 
                      html_contract_product +="<td><input style='display:none' class='tax' name='tax[]' id='tax_"+value.id+"' attr-ib_id='"+value.id+"' type='text' value='18'><input class='tax_amount' name='tax_amount[]' id='tax_amount_"+value.id+"' attr-ib_id='"+value.id+"' type='text' value='' readonly><input style='display:none' type='text' id='history_percentage_"+value.id+"' name='history_percentage[]' readonly></td>"; 
                      html_contract_product +="<td><input class='prd_total' name='total[]' id='prd_total_"+value.id+"' attr-ib_id='"+value.id+"' type='text' value='' readonly></td>"; 
                      

                     
                      html_contract_product +="<td class='history'><input class='prev_amount' name='prev_amount[]' id='prev_amount_"+value.id+"' attr-ib_id='"+value.id+"' type='text' value='' readonly></td>";
                      html_contract_product +="<td class='history'>"+value.warrenty_end_date+"</td>";
                      html_contract_product +="<td class='history'>"+value.ib_equipment_status.name+"</td>";  
                      html_contract_product +="<td><a class='del_product' attr-product_id='"+value.id+"'><img src='{{ asset('images/delete.svg') }}'></a></td></td><tr>"; 
                  });
                  if($('#total_td').val() == ''){
                    var prev_total = '';  
                  }else{
                    var prev_total = $('#total_td').val();
                  }
                  $('#total_tr').remove();
                      html_contract_product +="<tr id='total_tr'><td colspan='6'></td><td ><b>Total</b></td><td><input id='total_td' name='total_td' type='text' value='' readonly></td><tr>";   
                  $("#contractProductBody").append(html_contract_product);
                  list_product(product_save);
                   
                  

                 // $("#contractProductHistoryBody").append(html_product_history);
                  //console.log(data.product);
                }          
            });
    }   

$(document).on('keyup', '#contractProductBody .amount', function() {
                    var amountInput = parseFloat(this.value);
                    amountInput = isNaN(amountInput)?0:amountInput;
                    //alert(amountInput);
                    var product_id = $(this).attr("attr-ib_id");
                    var taxInput = $('#tax_'+product_id).val();
                    var prevAmount = $('#prev_amount_'+product_id).val();
                    if(taxInput == ''){
                       $('#prd_total_'+product_id).val(amountInput);
                       //$('#total_td').val(amountInput);
                       var productAmount = amountInput;
                    }else{
                      taxInput=parseFloat(taxInput);
                      taxInput = isNaN(taxInput)?0:taxInput;
                      var taxAmount = amountInput / 100 * taxInput ;
                      $('#tax_amount_'+product_id).val(taxAmount);
                      $('#prd_total_'+product_id).val(amountInput + taxAmount);
                      //$('#total_td').val(amountInput + taxAmount);
                      var productAmount = amountInput + taxAmount;
                    }
                    prevAmount=parseFloat(prevAmount)
                    prevAmount = isNaN(prevAmount)?0:prevAmount;
                    var hAmount_nAmount = productAmount - prevAmount;
                    var his_percentage  = hAmount_nAmount / prevAmount * 100; 
                     
                    $('#history_percentage_'+product_id).val(his_percentage);
                    var tot=0;
                    $('input.prd_total').each(function() {
                      tot = parseFloat($(this).val()||0)+parseFloat(tot);
                    });
                    $('#total_td').val(tot);
                  });
$('#contractProductBody').on('keyup', '.tax', function() {
                    var taxInput = parseFloat(this.value);
                    var product_id = $(this).attr("attr-ib_id");
                    var amountInput = $('#amount_'+product_id).val();
                    var prevAmount = $('#prev_amount_'+product_id).val();
                    if(amountInput == ''){
                      $('#prd_total_'+product_id).val(taxInput);
                      $('#total_td').val(taxInput);
                      var productAmount = 0;
                    }else{
                      var taxAmount = parseFloat(amountInput) / 100 * taxInput ;
                      $('#tax_amount_'+product_id).val(taxAmount);
                      $('#prd_total_'+product_id).val(taxAmount + parseFloat(amountInput));
                      $('#total_td').val(taxAmount + parseFloat(amountInput));
                      var productAmount = parseFloat(taxAmount) + parseFloat(amountInput);
                    }
                    var hAmount_nAmount = parseFloat(productAmount) - parseFloat(prevAmount);
                    var his_percentage  = parseFloat(hAmount_nAmount) / parseFloat(prevAmount) * 100;
                    $('#history_percentage_'+product_id).val(parseFloat(his_percentage));
                  });
$( "#contractProductBody").on('click', '.del_product', function() {
    $(this).closest('tr').remove();
    var product_id=$(this).attr("attr-product_id");
    //alert(product)
    console.log(product);
    for( var i = 0; i <= product.length; i++){ 
        
        if ( product[i] == product_id) { 
       
          product.splice(i, 1); 
        }
      }
    // var product = jQuery.grep(product, function(value) {
    //   return value != product_id;
    // });
    console.log(product);
    list_product(product);
  });

  function list_product(product_save)
  {
    var oppertunity_user_id = $("#oppertunity_user_id").val();
    var APP_URL = {!! json_encode(url('/')) !!};
    var url = APP_URL+'/staff/oppertunity_contract_list_product';
            $.ajax
                ({
                    type: "POST",
                    cache: false,
                    url: url,
                    data:{
                        product : product,
                        oppertunity_user_id : oppertunity_user_id,
                        oppertunity_id:{{$oppertunity->id}},
                },
                success: function (data)
                {     
                  html_contract_list_product = '';
                  $('#product_list').html(html_contract_list_product);
                  k=0
                  $.each(data.products_list, function (key, value){ 
                    if(value.ib_product != ''){
                      k++
                      var disabled="disabled";
                      if(value.ib_equipment_status&&value.ib_equipment_status.id){
                        disabled="";
                      }

                      html_contract_list_product += `
                            <tr>
                              <td>
                                <span>${k} <input type="checkbox" name="product_id[]" class="product_chbox"  id="${value.id}" value="${value.id}" ${disabled}></span>
                              </td>
                              <td>${value.ib_product.name}</td>
                              <td>${value.equipment_serial_no}</td>
                              <td>${value.warrenty_end_date}</td>
                            </tr> 
                      
                      `;
                       
                    }
                  });
                  if(product == ''){
                    $('#contractProduct').hide();
                    $('#save_btn').hide();
                  }
                  $('#product_list').html(html_contract_list_product);
                }          
            });
  }

// $('.amount').keyup(function(event) {
  
//   alert('press');
// });

    </script>
@endsection
