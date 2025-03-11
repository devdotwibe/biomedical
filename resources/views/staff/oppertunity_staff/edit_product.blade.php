

@extends('staff/layouts.app')

@section('title', 'Edit Product')

@section('content')

<section class="content-header">
      <h1>
        Edit Product ({{$op_name}})
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/list_oppertunity')}}">Manage Opportunity</a></li>
        <li class="active">Edit Product ({{$op_name}})</li>
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

            <form   role="form" name="frm_company" id="frm_company" method="post"  enctype="multipart/form-data" >
               @csrf
                <div class="box-body">

                <div class="form-group col-md-12">
                  <label>Product*</label>
                  <select name="product_id" id="product_id" class="form-control" readonly>
                    <option value="">-- Select Product --</option>
                    
                    @foreach($products as $item) 
                       <option value='{{$item->id}}' @if(old('product_id',$pdt->product_id)==$item->id){{'selected'}} @endif>{{$item->name}}</option>
                    @endforeach
                  </select>
                  <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6">
                  <label>Quantity*</label>
                  <input type="text" id="quantity" name="quantity" class="form-control" value="{{old('quantity',$pdt->quantity)}}">
                  <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-6">
                   <label> Sale Amount</label>
                   <input type="text" id="amount" name="amount" class="form-control" value="{{old('amount',$pdt->amount)}}">
                </div>


              <div class="box-footer">
               
               <input type="submit" id="save_btn" class="btn btn-primary" value="submit">
          
              </div>

            </form>

          </div>

        </div>
      </div>
</section>



@endsection

@section('scripts')

  <script type="text/javascript">
    $("#quantity").change(function(){
        var quantity   = $(this).val();
        var product_id = $("#product_id").val();

        //alert(quantity);
      
        var url = APP_URL+'/staff/get_product_all_details';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
              var proObj = JSON.parse(data);
              for (var i = 0; i < proObj.length; i++) {
                var amt    = quantity * proObj[i]["unit_price"];
                $("#amount").val(amt);
              }
              
            }
        });

    });
      
  </script>
   
  
@endsection
