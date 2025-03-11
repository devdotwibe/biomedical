@extends('staff/layouts.app')
@section('title', 'Verify Dispatch')
@section('content')
<section class="content-header">
      <h1>
      Verify Dispatch
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.Pendingtransaction')}}">Verify Dispatch</a></li>
        <li class="active">Verify Dispatch</li>
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

            <form autocomplete="off" role="form" name="frm_dispatch" id="frm_dispatch" method="post" action="{{route('staff.dispatch_approval_update')}}" enctype="multipart/form-data" >
               @csrf
               <div class="box-body row">

               <div class="form-group col-md-6 col-sm-6 col-lg-6 ">
                  <label for="name">Customer*</label>
                  <select id="user_id" name="user_id"  class="form-control" disabled="true">
                  <option value="{{$user->id}}">{{$user->business_name}}</option>
                  
                  </select>
                  
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6 ">
                  <label for="name">Invoice No*</label>
                  <input type="text" id="invoice_no" disabled="true" name="invoice_no" value="{{$dispatch->invoice_no}}" class="form-control" placeholder="Invoice No">
                  
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6 ">
                  <label for="name">Despatch Prepared date*</label>
                  <input type="text" id="dispatch_date" disabled="true" name="dispatch_date" value="{{$dispatch->dispatch_date}}" class="form-control" placeholder="Despatch Prepared date">
                
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6 ">
                  <label for="name">Courier Details*</label>
                  <textarea id="courier" name="courier"  disabled="true" class="form-control" placeholder="Courier Details">{{$courier->name}} {{$courier->address}}</textarea>
                
                </div>


               </div>

                <div class="box-body row">

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th>No.</th>
                  <th>Product Name</th>
              
                  <th>Dispatch Quantity</th>
                  <th>HSN</th>
                  <th>Source</th>
                  <th>Dispatch From</th>
                  <th>Approve Dispatch Details</th>
                 
                </tr>
                </thead>
                <tbody>
                @php($i=1)
                  @if(count($dispatch_product)>0)
                  @foreach($dispatch_product as $values)
                  <tr class="row_{{$values->id}}">
                  <td>{{$i}}</td>
                  <td>{{$values->product_name}}</td>
                
                  <td>
                  {{$values->quantity}}
                  </td>
                  <td>
                  {{$values->hsn}}
                  </td>
                  <td>
                  {{$values->source}}
                  <td>
                 @if($values->source=="Staff")
                 @if($values->staff_id>0)
                 {{$values->getstaff->name}}
                 @endif
                 @endif
                 @if($values->source=="Warehouse")
                 @if($values->warehouse_id>0)
                 {{$values->getwarehouse->name}}
                 @endif
                 @endif
                  </td>
                  <td >
                  
                  <input type="checkbox" name="product_approve[]" id="product_approve{{$values->id}}" value="{{$values->id}}">
               
                 
                  </td>
                 
                </tr>
                @php($i++)
                  @endforeach
                  @endif
                </tbody>
                </table>

                
                <span class="error_message" id="error_checkbox" style="display: none">Approve Dispatch Details</span>

                </div>

                <div class="box-body row">

             
               

                <div class="form-group col-md-6 col-sm-6 col-lg-6 ">
                  <label for="name">E-way bill* </label>
                  <input type="text" id="eway_bill_no"  name="eway_bill_no" class="form-control" >
                  <span class="error_message" id="error_eway_bill_no" style="display: none">Approve Dispatch Details</span>
                  <span>E-way bill is not applicable  please enter text ' Not applicable'</span>
                 
                </div>
                <div class="form-group col-md-6 col-sm-6 col-lg-6 ">
                  <label for="name">E-way bill Date</label>
                  <input type="text" id="eway_bill_date"  name="eway_bill_date" class="form-control" >
                 
                </div>

                <div class="form-group col-md-6 col-sm-6 col-lg-6 ">
                  <label for="name">Remark</label>
                   <textarea id="eway_bill_remark"  name="eway_bill_remark" class="form-control" ></textarea>
                 
                </div>
                <div class="form-group col-md-6 col-sm-6 col-lg-6 ">
                  <label for="name">If the invoice amount is above 50k it will generate a E-way bill </label>
                  <input type="checkbox" name="eway_status" id="eway_status" value="Y">
                 
                </div>


                <div class="form-group col-md-12 col-sm-6 col-lg-12 ">
                  <label for="name">Upload E-way bill </label>
                  <input type="file" id="upload_eway_bill"  name="upload_eway_bill" class="form-control" >
                 
                </div>


                <div class="form-group col-md-12 col-sm-6 col-lg-12 ">
                <input type="hidden" name="invoice_id" id="invoice_id" value="{{$invoice_id}}">
                <input type="hidden" name="dispatch_id" id="dispatch_id" value="{{$dispatch->id}}">
                <input type="hidden" name="transaction_id" id="transaction_id" value="{{$dispatch->transation_id}}">
                <button type="button" class="lg-btn submit-btn " onclick="validate_form()">Verify</button>
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
function validate_form()
{
 
  var flag =0; 
  var product = []; 
    $("input:checkbox[name='product_approve[]']:checked").each(function() { 
      product.push($(this).val()); 
    });
var count_tot_product="{{count($dispatch_product)}}";
  
var eway_bill_no=$("#eway_bill_no").val();
if(eway_bill_no=='')
{
$("#error_eway_bill_no").show();
}
else{
  $("#error_eway_bill_no").hide();
}
  if(product.length==count_tot_product && eway_bill_no!='')
  {
    
    $("#user_id").removeAttr("disabled");
    $("#error_checkbox").hide();
    $("#frm_dispatch").submit(); 
  }
  else{
    $("#error_checkbox").show();
  }
}

$(document).ready(function() {
 
    $("#state_id").selectpicker({
      enableFiltering: true,
    });
    $("#user_id").selectpicker({
      enableFiltering: true,
    });
    $("#district_id").selectpicker({
      enableFiltering: true,
    });
});
  </script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$('#eway_bill_date').datepicker({
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