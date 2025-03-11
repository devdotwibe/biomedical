

@extends('staff/layouts.app')

@section('title', 'Edit Product')

@section('content')

@php
$staff_id = session('STAFF_ID');
@endphp



<section class="content-header">
      <h1>
        Edit Product
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/list_oppertunity')}}">Manage Opportunity</a></li>
        <li class="active">Edit Product</li>
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

            @if (count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
          <h4><b>{{$op_name->oppertunity_name}}</b></h4>
            <form   role="form" name="frm_company" id="frm_company" method="post" action="{{ route('staff.edit_oppertunity_contract_product') }}"  enctype="multipart/form-data" >
               @csrf
               <input type="hidden" name="op_id" value="{{ $op_name->id }}">
               <input type="hidden" name="pdt_id" value="{{ $pdt->id }}">
                <div class="box-body">
                   <div class="row">
                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label>Product*</label>
                  <select name="product_id" id="product_id" class="form-control" disabled="disabled">
                    <option value="">-- Select Product --</option>
                    
                    @foreach($products as $item) 
                       <option value='{{$item->id}}' @if(old('product_id',$pdt->product_id)==$item->id){{'selected'}} @endif>{{$item->name}}</option>
                    @endforeach
                  </select>
                  <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                </div>
              </div>
                <div class="row edit_orty-row">

                  <div class="form-group col-md-2 col-sm-6 col-lg-2">
                    <label>Quantity*</label>
                  
                    <input type="text" id="quantity" name="quantity" class="form-control" value="{{old('quantity',$pdt->quantity)}}" onfocus="checkNumber(this)">
                    <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
                  </div>


                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                  <label>PMs</label>
                  <input type="hidden" id="type" name="type" class="form-control" value="2">
                  <input type="hidden" id="product_id_hidden" name="product_id" class="form-control" value="{{old('product_id',$pdt->product_id)}}" >
                  <input type="text" id="pms" name="pm" class="form-control" value="{{old('pm',$pdt->pm)}}" onfocus="checkNumber(this)">
                  <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                   <label>CRs</label>
                   <input type="text"  id="crs" name="cr" class="form-control" value="{{old('cr',$pdt->cr)}}" onfocus="checkNumber(this)">
                   <div style="display:none;" class="error_message error_sale"></div>
                </div>

                @if($staff_id == 35 )

                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                  <label>Previous Amount</label>
                  <input type="text" id="oldprice" name="oldprice" class="form-control oldprice" value="{{ old('oldprice', $pdt->oldprice) }}" oninput="calculateAmount(this)">
                  <div style="display:none;" id="oldprice_error" class="error_message error_sale"></div>
              </div>
              
              <div class="form-group col-md-2 col-sm-6 col-lg-2">
                  <label>Hike(%)</label>
                  <input type="text" id="hike" name="hike" class="form-control hike" value="{{ old('hike', $pdt->hike) }}" oninput="calculateAmount(this)">
                  <div style="display:none;" id="hike_error" class="error_message error_sale"></div>
              </div>
              

@endif

<div class="form-group col-md-2 col-sm-6 col-lg-2">
  <label>Amount</label>
  <input type="text" id="amount" name="amount" class="form-control amount" value="{{ old('amount', $pdt->sale_amount) }}" >
  <div style="display:none;" id="amount_error" class="error_message error_sale"></div>
</div>

                <?php /*
                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                   <label>Tax %</label>
                   <input type="text"  id="tax" name="tax" class="form-control" value="{{old('tax',$pdt->tax)}}">
                </div>

                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                   <label>Tax Amount</label>
                   <input type="text"  id="tax_amount" name="tax_amount" class="form-control" value="{{old('tax_amount',$pdt->tax_amount)}}" readonly>
                   <div style="display:none;" class="error_message error_sale"></div>
                </div>

                <div class="form-group col-md-2 col-sm-6 col-lg-2">
                   <label>Total</label>
                   <input type="text"  id="prd_total" name="prd_total" class="form-control" value="{{old('amount',$pdt->amount)}}" readonly>
                   <div style="display:none;" class="error_message error_sale"></div>
                </div>

              </div>
              */ ?>

              <div class="box-footer">
               
               <input type="submit" id="save_btn" class="mdm-btn submit-btn  " value="submit">
          
              </div>

            </form>

          </div>

        </div>
      </div>
</section>



@endsection

@section('scripts')

<script type="text/javascript">

  function checkNumber(element) {
        
        $(element).on('keypress', function(event) {
            const key = event.which || event.keyCode;
        
            if (key >= 48 && key <= 57 || key === 8 || key === 9 || key === 27 || key === 13) {
                return;
            }
            event.preventDefault(); 
        });
    }

    function checkFloat(element) {
   
            $(element).on('keypress', function(event) {
                const key = event.which || event.keyCode;
                const value = $(element).val();

                console.log('keypress');  

                if ((key >= 48 && key <= 57) || key === 8 || key === 9 || key === 27 || key === 13) {
                    return; 
                }

                if (key === 46 && value.indexOf('.') === -1) {
                    return; 
                }

                event.preventDefault();
            });

            $(element).on('paste', function(event) {
          
            let pastedText = event.originalEvent.clipboardData.getData('text');
            
            let sanitizedText = pastedText.replace(/[^0-9.]/g, ''); 

            $('#amount_error').hide();

            if (sanitizedText !== pastedText) {
                event.preventDefault(); 
                $('#amount_error').text("Invalid characters detected. Please use only numbers and a single decimal point.").show();
                return; 
            }

            const value = $(element).val();
          
            const newValue = value;
            $(element).val(newValue);
        });

  }



      
$('#single_amount').on('keyup', function() {
  var singleAmountInput = parseFloat(this.value);
  var taxInput = parseFloat($('#tax').val());
  if(taxInput == '')
      {
        $('#prd_total').val(singleAmountInput);
      }
  else
      {
        var taxAmount = parseFloat(singleAmountInput) / 100 * taxInput ;
        $('#tax_amount').val(taxAmount);
        $('#prd_total').val(singleAmountInput + taxAmount);
      }
});
$('#tax').on('keyup', function() {
  var taxInput = parseFloat(this.value);
  var singleAmountInput = parseFloat($('#single_amount').val());
  if(singleAmountInput == '')
      {
        $('#prd_total').val(taxInput);
      }
  else
      {
        var taxAmount = parseFloat(singleAmountInput) / 100 * taxInput ;
        $('#tax_amount').val(taxAmount);
        $('#prd_total').val(singleAmountInput + taxAmount);
      }
});





function calculateAmount(inputElement) {
    const oldPriceInput = document.getElementById('oldprice');
    const hikeInput = document.getElementById('hike');
    const amountInput = document.getElementById('amount');

    const oldPrice = parseFloat(oldPriceInput.value) || 0;
    const hike = parseFloat(hikeInput.value) || 0;

    if (oldPrice && hike) {
        const newAmount = oldPrice + (oldPrice * hike) / 100;
        amountInput.value = newAmount.toFixed(2); 
    } else {
        amountInput.value = ''; 
    }
}



  </script>
   
  
@endsection
