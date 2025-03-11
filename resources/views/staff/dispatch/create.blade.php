@extends('staff/layouts.app')
@section('title', 'Add Dispatching Details')
@section('content')
<?php
if(isset($_GET['id']))
{
  $id=$_GET['id'];
}
else{
  $id=0;
}
?>
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
              

                <div class="box-body row" @if($id>0) style="display:none;" @endif>

        
                
                <div class="form-group  col-md-3 col-sm-6 col-lg-3"  >
                  <label for="name">Transaction</label>
                  <select id="transaction_id" name="transaction_id" class="form-control" onchange="get_transaction_details(this.value)">
                  <option value="">Transaction</option>
                  <?php
                      foreach($transaction as $item) {
                        $sel = ($id == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>Trans_'.$item->id.'</option>';
                      } ?>
                  </select>
                  <div class="loader_trans_id" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                  <span class="error_message" id="transaction_id_message" style="display: none">Field is required</span>
                </div>
              </div>

               <div class="ajax_resp"></div>

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
    $(document).ready(function() {
   
   
   $('#verified_staff').select2();
   //$('.staff_sel').select2();
});

var id="<?php echo $id;?>";
if(id>0)
{
  get_transaction_details(id)
}
    function get_transaction_details(transaction_id)
    {
      var url = APP_URL+'/staff/get_transaction_for_dispatch';
           $.post("{{url('staff/get_transaction_for_dispatch')}}", {transaction_id: transaction_id, "_token": "{{ csrf_token() }}"}, function(result,status){
            // row.child( result ).show();
            $(".ajax_resp").html('');
            $(".ajax_resp").html(result);
            // tr.addClass('shown');
            $('#verified_staff').select2();
      });

    }
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
 /*$('input[name^="invoice_qty[]"]').each(function() {
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
  
 });*/
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
  if(courier_id!='' &&  verified_staff!='' && qty_empty_flag==0 &&  hsn_flag==0 && source_flag==0 && source_option==0)
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
var pro_name=$(this).attr("attr-name");
var product_id=$(this).attr("attr-product_id");
 var hsn=$(this).attr("attr-hsn");
var count_pro=$("#countpro_"+id).val();
var add=parseInt(count_pro)+1;
$("#countpro_"+id).val(add);
var htmls='<tr class="childrow_'+id+'_'+count_pro+'">'+
'<input type="hidden" id="product_name_'+id+'" name="product_name[]" value="'+pro_name+'" >'+
'<input type="hidden" id="product_id_'+id+'" name="product_id[]" value="'+product_id+'" >'+
'<input type="hidden" id="ids_'+id+'" name="ids[]" value="'+id+'" >'+
'<td></td>'+
'<td></td>'+
'<td></td>'+
'<td> <input attr-id="'+id+'" attr-row="'+add+'" onchange="change_qty(this.value,'+id+','+add+')" type="number" id="quantity_'+id+'_'+add+'" name="quantity[]" value="" class="form-control" placeholder="Quantity">'+
 '<span class="error_message" id="quantity_message_'+id+'_'+add+'" style="display: none">Field is required</span></td>'+
'<td> <input readonly="true" attr-id="'+id+'" attr-row="'+add+'" type="text" id="hsn_'+id+'_'+add+'" name="hsn[]" value="'+hsn+'" class="form-control hideform" placeholder="HSN">'+
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