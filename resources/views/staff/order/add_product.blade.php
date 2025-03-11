

@extends('staff/layouts.app')

@section('title', 'Add Product')

@section('content')

<section class="content-header">
      <h1>
        Add Product
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/list_order')}}">Manage Order</a></li>
        <li class="active">Add Product</li>
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

                <div class="form-group col-md-3">
                  <label>Product*</label>
                  <select name="product_id" id="product_id" class="form-control product_id">
                    <option value="">-- Select Product --</option>
                    <?php
                    foreach($products as $item) {
                     
                        echo '<option value="'.$item->id.'" >'.$item->name.'</option>';
                       
                    } ?>
                  </select>
                  <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                  <span class="rows_selected" id="select_count" style="display: none"></span><br>
                </div>

                <div class="form-group col-md-3">
                  <label>Quantity*</label>
                  <select name="quantity" id="quantity" class="form-control quantity">
                    <option value="">-- Select quantity --</option>
                    @for($i=1;$i<=10;$i++)
                      <option value="{{$i}}">{{$i}}</option>
                    @endfor
                  </select>
                  <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-3">
                  <label>Sale Amount</label>
                  <input type="text" id="sale_amount" name="sale_amount" class="form-control" placeholder="sale amount" value="0">
                  <span class="error_message" id="sale_message" style="display: none">Invalid amount. Please contact admin</span>
                  <div id="samt"></div>
                </div>


                

              <div class="box-footer">
              <input type="hidden" name="count_product" id="count_product" value="0">
              <input type="hidden" name="op_id" id="op_id" value="0">
              <br>
              <span class="error_message" id="alreadytexit" style="display: none">Product already exit</span>
                <button type="button" class="btn btn-primary"  onclick="add_product()">Add</button>
              
              </div>

              </div>

              <!-- /.box-body -->

            


               <table id="cmsTable" class="table table-bordered table-striped data-" style="">
                <thead>
                  <tr>
                  
                    <th>No.</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Net Amount</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody id="tabledata">
                   
                    <tr  data-from ="staffquote">
                       
                       <td colspan="4" class="noresult">No result</td>
                    </tr>
               </table>


              <div class="box-footer">
  
               
               <button type="submit" id="save_btn" style="display:none;" class="btn btn-primary" >Save</button>
          
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
    $('.product_id').select2();
    $('.quantity').select2();
    
  </script>

  <script type="text/javascript">
    var samt;
    $(function() {

        var url = APP_URL+'/staff/get_product_all_details';

        $(".product_id").change(function() {
          var pid =  $("#product_id").val();
          
          $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: pid,
            },
            success: function (data)
            {     
               var proObj   = JSON.parse(data);
               var htmlscon = '';
               for (var i = 0; i < proObj.length; i++) {
                 var amount     = proObj[i]["unit_price"];
                  htmlscon      ='<input type="hidden" id = "min_sales_amt" name="min_sales_amt" value="'+proObj[i]["min_sale_amount"]+'"><input type="hidden" name="max_sales_amt" id="max_sales_amt" value="'+proObj[i]["max_sale_amount"]+'"></tr>';
               }
               $("#select_count").show();
               $("#select_count").html(" Amount : "+amount);
               $("#samt").html(htmlscon);
               $("#sale_amount").val(amount);
            }
          });   
        });
          
    });

  </script>
  <script type="text/javascript">

  var arr_product = [];
  var url         = APP_URL+'/staff/get_product_all_details';
    
  function add_product()
  {
  
    var product_id = $("#product_id").val();
    var quantity   = $("#quantity").val();
    var sale_amount= $('#sale_amount').val();
    var min_sale   = $('#min_sales_amt').val();
    var max_sale   = $('#max_sales_amt').val();
    var flag       = 1;

    //alert("sale" +sale_amount+ "min" +min_sale+ "max" +max_sale);

    if(product_id=="")
    {
      $("#product_id_message").show();
    }
    else{
      $("#product_id_message").hide();
    }
    
    if(quantity=="")
    {
      $("#quantity_message").show();
    }
    else{
      $("#quantity_message").hide();
    }

    if(max_sale!=0 && sale_amount>max_sale) //
    {
      //alert("123");
      flag = 0;
      $("#sale_message").show();
      
    }
    else if(min_sale!=0 && sale_amount<min_sale) //
    {
      //alert("456");
      flag = 0;
      $("#sale_message").show();
    }
    else
    {
      flag = 1;
      $("#sale_message").hide();
    }

    var count_product=$("#count_product").val();
    if(product_id!='')
    {
       exit_product=findValueInArray(product_id, arr_product);
       if(exit_product=="1")
       {
         $("#alreadytexit").show();
       }
       else{
        $("#alreadytexit").hide();
       }
    }
    if(product_id!='' && quantity !='' && exit_product!=1 && flag==1)
    {
      $("#user_id").attr('disabled', true);
      arr_product.push(product_id);
     //console.log(arr_product);

      var add_counts=parseInt(count_product)+1;
      $("#count_product").val(add_counts); 
    
      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: product_id,
          },
          success: function (data)
          {     
           // $("#sale_message").hide();
            $(".noresult").hide();
            $("#preview_btn").show();
            $("#save_btn").show();
            var proObj = JSON.parse(data);
             htmls='';
           
            for (var i = 0; i < proObj.length; i++) {
                if(proObj[i]["image_name"]==null || proObj[i]["image_name"]=='')
                {
                  
                  var imgs="{{asset('images/')}}/no-image.jpg";
                  
                }
                else{
                 
                  var imgs="{{asset('storage/app/public/products/thumbnail/')}}/"+proObj[i]["image_name"];
                }

                  var amt = quantity * sale_amount;
                
                  //pdfsec='<input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'">';
                
                  htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="100px" height="100px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td><td>'+quantity+'</td><td>'+sale_amount+'</td><td>'+amt+'</td><td> <a class="btn btn-danger btn-xs " onclick="deletepro('+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'"><input type="hidden" name="amount[]" value="'+amt+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'"></tr>';
                

                  $("#tabledata").append(htmlscontent);
                //$("#pdfsec").append(pdfsec);
            }
           
          }
        });  
    }
  }

      function findValueInArray(value,arr){
     
      for(var i=0; i<arr.length; i++){
        var name = arr[i];
        if(name == value){
         var result = '1';
          break;
        }
        else{
         var result = '0';
        }
      }

      return result;
    }

    function deletepro(product_id)
    {
      var count_product=$("#count_product").val();
  
      var add_counts=parseInt(count_product)-1;
      $("#count_product").val(add_counts); 
      $(".tr_"+product_id).remove();
      //alert(arr_product.length);
      for( var i = 0; i <= arr_product.length; i++){ 
        
        if ( arr_product[i] == product_id) { 
         // alert(arr_product[i]);
          arr_product.splice(i, 1); 
        }
      }
      if(add_counts=="0"){
            $("#preview_btn").hide();
            $("#save_btn").hide();
            $(".noresult").show();
            $("#user_id").val('');
            $("#product_id").val('');
            $("#user_id").attr('disabled', false);
      }
    }

    </script>
@endsection
