@extends('staff/layouts.app')
@section('title', 'Add Dispatching Details')
@section('content')
<section class="content-header">
      <h1>
      Add Dispatching Details
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="{{route('staff.Pendingtransaction')}}">Add Dispatching Details</a></li>
        <!-- <li class="active">Add Dispatching Details</li> -->
      </ol>
    </section>
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
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

            <form role="form" name="frm_dispatch" id="frm_dispatch" method="post" action="{{route('staff.dispatch.store')}}" enctype="multipart/form-data" >
               @csrf
              

                <div class="box-body row">

                
                <a class="btn btn-sm btn-default" target="_blank" id="btn_preview" href="{{url('staff/preview_invoice/'.$invoice_id)}}">Invoice Preview</a>
             
                
                <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/transation/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data- hideform">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Product Name</th>
                  <th>Invoice Quantity</th>
                  <th>Dispatch Quantity</th>
                  <th>HSN</th>
                  <th>Source</th>
                  <th>Dispatch From</th>
                  <th>Action</th>
                 
                </tr>
                </thead>
                <tbody>
                @php($i=1)
                  @if(count($invoice_product)>0)
                  @foreach($invoice_product as $values)
                  <tr class="row_{{$values->id}}">
                  <td>{{$i}}</td>
                  <td>{{$values->product_name}}
                  <input type="hidden"  id="product_name_{{$values->id}}" name="product_name[]" value="{{$values->product_name}}" class="form-control" >
                  <input type="hidden"  id="product_id_{{$values->id}}" name="product_id[]" value="{{$values->product_id}}" class="form-control" >
                  <input type="hidden"  id="transaction_id_{{$values->id}}" name="transaction_id[]" value="{{$values->transation_id}}" class="form-control" >
                  </td>
                  <td>{{$values->quantity}}
                  <input attr-id="{{$values->id}}" type="hidden" name="invoice_qty[]" id="invoice_qty_{{$values->id}}" value="{{$values->quantity}}"> 
                  <span class="error_message" id="error_tot_qty_{{$values->id}}" style="display: none"></span>
                  </td>
                  <td>
                  <input min="1" attr-id="{{$values->id}}" attr-row="0" onchange="change_qty(this.value,{{$values->id}},0)" type="number" @if($values->quantity==1) readonly="true" @endif id="quantity_{{$values->id}}_0" name="quantity[]" value="{{$values->quantity}}" class="form-control" placeholder="Quantity">
                  <span class="error_message" id="quantity_message_{{$values->id}}_0" style="display: none">Field is required</span>
                  </td>
                  <td>
                  <input readonly="true" attr-id="{{$values->id}}" attr-row="0" type="text" id="hsn_{{$values->id}}_0" name="hsn[]" value="{{$values->hsn}}" class="form-control" placeholder="HSN">
                  <span class="error_message" id="hsn_message_{{$values->id}}_0" style="display: none">Field is required</span>
                  </td>
                  <td>
                  <select attr-id="{{$values->id}}" attr-row="0"  id="source_{{$values->id}}_0" name="source[]" class="form-control" onchange="change_source(this.value,{{$values->id}},0)">
                  <option value="">Source</option>
                  <option value="Staff">Staff</option>
                  <option value="Warehouse">Warehouse</option>
                  </select>
                  <span class="error_message" id="source_message_{{$values->id}}_0" style="display: none">Field is required</span>
                  </td>
                  <td>
                  <select id="staff_id_{{$values->id}}_0" name="staff_id[]"  class="form-control stafftext staff_sel" disabled="true">
                  <option value="">Staff</option>
                  @foreach($staff as $staff_value)
                  <option value="{{$staff_value->id}}">{{$staff_value->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="staff_id_message_{{$values->id}}_0" style="display: none">Field is required</span>

                  <select id="warehouse_id_{{$values->id}}_0" name="warehouse_id[]"  class="form-control" style="display:none">
                  <option value="">Warehouse</option>
                  @foreach($warehouse as $warehouse_value)
                  <option value="{{$warehouse_value->id}}">{{$warehouse_value->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="warehouse_id_message_{{$values->id}}_0" style="display: none">Field is required</span>

                  </td>
                  <td >
                  @if($values->quantity!=1)
                  <input type="hidden" name="countpro[]" id="countpro_{{$values->id}}" value="0"> 
                  <button type="button" attr-product_id="{{$values->product_id}}" attr-transaction_id="{{$values->transation_id}}" attr-hsn="{{$values->hsn}}" attr-id="{{$values->id}}" attr-name="{{$values->product_name}}" class="sml-btn submit-btn add_dispatch addbtn_{{$values->id}}_0">+</button>
                  @endif
                  </td>
                 
                </tr>
                @php($i++)
                  @endforeach
                  @endif
                </tbody>
                </table>

                


                </div>

                <div class="box-body row">

                <div class="form-group col-md-4 ">
                  <label for="name">Courier*</label>
                  <select id="courier_id" name="courier_id"  class="form-control" >
                  <option value="">Courier</option>
                  @foreach($courier as $courier_value)
                  <option value="{{$courier_value->id}}">{{$courier_value->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="courier_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-4">
                  <label for="name">Verified Staff*</label>
                  <select id="verified_staff" name="verified_staff"  class="form-control" >
                  <option value="">Verified Staff</option>
                  @foreach($staff as $staff_value)
                  <option value="{{$staff_value->id}}">{{$staff_value->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="verified_staff_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-12 ">
                <input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice_id}}">
                <button type="button" class="lg-btn submit-btn " onclick="validate_form()">Submit For Verification</button>
                </div>

               

                </div>


              <!-- /.box-body -->

            </form>
          </div>
        </div>
      </div>
</section>






@endsection
@section('scripts')


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />


 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

    <script type="text/javascript">
function change_qty(qty,id,row_no)
  {
  var invoice_qty=$("#invoice_qty_"+id).val();
  var countpro=$("#countpro_"+id).val();
  var qty=0;
  for(var j=0;j<=countpro;j++)
  {
    var quantity=$("#quantity_"+id+'_'+j).val();
    qty=parseInt(qty)+parseInt(quantity);
   
  }
  if(qty>invoice_qty)
  {
    $("#quantity_"+id+'_'+row_no).val('');
    alert("Please Check Quantity Invoice Quantity : "+invoice_qty);
  }
  else{
    if(qty!=invoice_qty)
    {
     // $(".addbtn_"+id+'_'+row_no).removeAttr('disabled');
    }
  }
  }
function validate_form()
{
  var tot_taxable=0
  var i=0;
  var qty_empty_flag=0;
 $('input[name^="quantity[]"]').each(function() {
  var id=$(this).attr('attr-id');
  var row=$(this).attr('attr-row');
  if($(this).val()=="" || $(this).val()==0)
  {qty_empty_flag=1;
    $("#quantity_message_"+id+'_'+row).show();
  }
  else{
    $("#quantity_message_"+id+'_'+row).hide();
  }
  i++;
 });
 var count_flag=0;
 $('input[name^="invoice_qty[]"]').each(function() {
  var id=$(this).attr('attr-id');
var invoice_qty=$("#invoice_qty_"+id).val();
  var countpro=$("#countpro_"+id).val();
  var qty=0;
  for(var j=0;j<=countpro;j++)
  {
    var quantity=$("#quantity_"+id+'_'+j).val();
    qty=parseInt(qty)+parseInt(quantity);
  }
  if(qty!=invoice_qty)
  {
    $("#error_tot_qty_"+id).html('Please Check Quantity Invoice Quantity Not Equal Current Quantity ');
    $("#error_tot_qty_"+id).show();
     count_flag=1;
  }
  else{
    $("#error_tot_qty_"+id).html('');
    $("#error_tot_qty_"+id).hide();
  }
  
 });
 var hsn_flag=0;
 $('input[name^="hsn[]"]').each(function() {
  var id=$(this).attr('attr-id');
  var row=$(this).attr('attr-row');
  if($(this).val()=="" || $(this).val()==0)
  {hsn_flag=1;
    $("#hsn_message_"+id+'_'+row).show();
  }
  else{
    $("#hsn_message_"+id+'_'+row).hide();
  }
 });
 var source_option=0;
 var source_flag=0;
 $('input[name^="quantity[]"]').each(function() {
  var id=$(this).attr('attr-id');
  var row=$(this).attr('attr-row');
  var val_source=$("#source_"+id+'_'+row).val();
  
  if(val_source=="")
  {source_flag=1;
    $("#source_message_"+id+'_'+row).show();
  }
  else{
    if(val_source=="Staff")
    {
      var val_staff=$("#staff_id_"+id+'_'+row).val();
      if(val_staff=="")
      {source_option=1;
        $("#staff_id_message_"+id+'_'+row).show();
      }
      else{
        $("#staff_id_message_"+id+'_'+row).hide();
      }
    }else{
      var val_ware=$("#warehouse_id_"+id+'_'+row).val();
      if(val_ware=="")
      {source_option=1;
        $("#warehouse_id_message_"+id+'_'+row).show();
      }
      else{
        $("#warehouse_id_message_"+id+'_'+row).hide();
      }
    }
    $("#source_message_"+id+'_'+row).hide();
  }
 
 });
//alert(qty_empty_flag+'--'+count_flag+'--'+hsn_flag+'--'+source_flag+'--'+source_option)
  
  var courier_id=$("#courier_id").val();
  var verified_staff=$("#verified_staff").val();
  
  if(courier_id=="")
  {
    $("#courier_id_message").show();
  }
  else{
    $("#courier_id_message").hide();
  }
  if(verified_staff=="")
  {
    $("#verified_staff_message").show();
  }
  else{
    $("#verified_staff_message").hide();
  }
  if(courier_id!='' &&  verified_staff!='' && qty_empty_flag==0 && count_flag==0 && hsn_flag==0 && source_flag==0 && source_option==0)
  {
    $("#frm_dispatch").submit(); 
  }
}
function change_source(val,id,row)
{
  if(val=="Staff")
  {
  $("#staff_id_"+id+'_'+row).show();
  $("#warehouse_id_"+id+'_'+row).hide();
  $("#staff_id_"+id+'_'+row).removeAttr('disabled');
  }
  else{
    $("#staff_id_"+id+'_'+row).hide();
    $("#warehouse_id_"+id+'_'+row).show();
  }
}

$(".add_dispatch").click(function() {
var id=$(this).attr("attr-id");
var hsn=$(this).attr("attr-hsn");
var pro_name=$(this).attr("attr-name");

var transaction_id=$(this).attr("attr-transaction_id");
var product_id=$(this).attr("attr-product_id");
 

var count_pro=$("#countpro_"+id).val();
var add=parseInt(count_pro)+1;
$("#countpro_"+id).val(add);
var htmls='<tr class="childrow_'+id+'_'+count_pro+'">'+
'<input type="hidden" id="product_name_'+id+'" name="product_name[]" value="'+pro_name+'" >'+
'<input type="hidden"  id="product_id_'+id+'" name="product_id[]" value="'+product_id+'" class="form-control" >'+
'<input type="hidden"  id="transaction_id_'+id+'" name="transaction_id[]" value="'+transaction_id+'" class="form-control" >'+
'<td></td>'+
'<td></td>'+
'<td></td>'+
'<td> <input min="1" attr-id="'+id+'" attr-row="'+add+'" onchange="change_qty(this.value,'+id+','+add+')" type="number" id="quantity_'+id+'_'+add+'" name="quantity[]" value="" class="form-control" placeholder="Quantity">'+
 '<span class="error_message" id="quantity_message_'+id+'_'+add+'" style="display: none">Field is required</span></td>'+
'<td> <input readonly="true" attr-id="'+id+'" attr-row="'+add+'" type="text" id="hsn_'+id+'_'+add+'" name="hsn[]" value="'+hsn+'" class="form-control" placeholder="HSN">'+
 '<span class="error_message" id="hsn_message_'+id+'_'+add+'" style="display: none">Field is required</span></td>'+
'<td> <select attr-id="'+id+'" attr-row="'+add+'" id="source_'+id+'_'+add+'" name="source[]"  class="form-control" onchange="change_source(this.value,'+id+','+add+')">'+
' <option value="">Source</option>'+
' <option value="Staff">Staff</option>'+
' <option value="Warehouse">Warehouse</option>'+
' </select><span class="error_message" id="source_message_'+id+'_'+add+'" style="display: none">Field is required</span></td>'+
'<td>'+
'<select id="staff_id_'+id+'_'+add+'" name="staff_id[]"  class="form-control stafftext staff_sel" disabled="true">'+
 '<option value="">Staff</option>'+
  @foreach($staff as $staff_value)
  '<option value="{{$staff_value->id}}">{{$staff_value->name}}</option>'+
  @endforeach
  '</select>'+
  '<span class="error_message" id="staff_id_message_'+id+'_'+add+'" style="display: none">Field is required</span>'+
  '<select id="warehouse_id_'+id+'_'+add+'" name="warehouse_id[]"  class="form-control" style="display:none">'+
  '<option value="">Warehouse</option>'+
  @foreach($warehouse as $warehouse_value)
  '<option value="{{$warehouse_value->id}}">{{$warehouse_value->name}}</option>'+
  @endforeach
  '</select>'+
  '<span class="error_message" id="warehouse_id_message_'+id+'_'+add+'" style="display: none">Field is required</span>'+
'</td>'+
'<td><button type="button" attr-id="'+id+'" attr-row="'+count_pro+'" class="sml-btn submit-btn remove_dispatch">-</button></td>';
$(".row_"+id).closest( "tr" ).after(htmls);
$(".remove_dispatch").click(function() {
  var id=$(this).attr("attr-id");
  var row_no=$(this).attr("attr-row");
  
  var count_pro=$("#countpro_"+id).val();
var add=parseInt(count_pro)-1;
$("#countpro_"+id).val(add);
$('.childrow_'+id+'_'+row_no).remove();
});
});
$(".remove_dispatch").click(function() {
  var id=$(this).attr("attr-id");
  var row_no=$(this).attr("attr-row");
  
  var count_pro=$("#countpro_"+id).val();
var add=parseInt(count_pro)-1;
$("#countpro_"+id).val(add);
$('.childrow_'+id+'_'+row_no).remove();
});
$(document).ready(function() {
   
   
    $('#verified_staff').select2();
    //$('.staff_sel').select2();
});
  </script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$('#po_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            minDate: 0
        });
$('#collect_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            minDate: 0
        });
$('#expect_date').datepicker({
    //dateFormat:'yy-mm-dd',
    dateFormat:'yy-mm-dd',
    minDate: 0
});
</script>

@endsection