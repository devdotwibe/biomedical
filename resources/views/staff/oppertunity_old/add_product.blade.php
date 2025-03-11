

@extends('staff/layouts.app')

@section('title', 'Add Product')

@section('content')

<section class="content-header">
      <h1>
        Add Product ({{$op_name}})
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/list_oppertunity')}}">Manage Opportunity</a></li>
        <li class="active">Add Product ({{$op_name}})</li>
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
                        ?>
                        <option value="{{$item->id}}">@if($asset) 
                          {{$item->product_name}} 
                          @else 
                          {{$item->name}}@endif</option>
                        <?php
                    } ?>
                  </select>
                  <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                  <span class="rows_selected" id="select_count" style="display: none"></span><br>
                </div>

                <div class="form-group col-md-3">
                  <label>Quantity*</label>
                  <input type="text" id="quantity" name="quantity" class="form-control" placeholder="Quantity" value="1">
                  <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group col-md-3">
                  <label>Sale Amount</label>
                  <input type="text" id="sale_amount" name="sale_amount" class="form-control" placeholder="sale amount" value="0">
                  <span class="error_message" id="sale_message" style="display: none">Invalid amount. Please contact admin</span>
                  <div id="samt"></div>
                </div>
                
                <div class="form-group col-md-3" id="opp">
                  <label>Optional Products</label>
                  <select name="opt_pdt[]" id="opt_pdt" class="form-control" multiple="multiple">
                    <option value="">-- Select Product --</option>
                    
                  </select>
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
                    <th>Sale Amount</th>
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

  <script type="text/javascript">

    $(document).ready(function() {
      $('#opt_pdt').multiselect();
    });
  </script>

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
          $('#opt_pdt').find('option').remove();
          $("#opt_pdt").multiselect('rebuild');
          
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
               if(amount=='')
               {
                amount = 0;
               }
               if(opt_pdt!='')
               {

                  $('#opt_pdt').find('option').not(':first').remove();

                   // AJAX request 
                   $.ajax({
                     url: APP_URL+'/staff/loadproductnames/'+pid,
                     type: 'get',
                     dataType: 'json',
                     success: function(response){

                       var len = 0;
                       if(response['data'] != null){
                         len = response['data'].length;
                       }

                       var option = '';
                       if(len > 0){
                         // Read data and create <option >
                         for(var i=0; i<len; i++){

                           var id = response['data'][i].id;
                           var name = response['data'][i].name;

                           option += "<option value='"+id+"'>"+name+"</option>"; 
                           
                         }
                         $("#opt_pdt").html(option); 
                         $("#opt_pdt").multiselect('rebuild');
                       }

                     }
                  });

               }
               $("#select_count").show();
               $("#select_count").html(" Amount : "+amount);
               $("#samt").html(htmlscon);
               $("#sale_amount").val(amount);
               //alert(amount);
            }
          });   
        });
          
    });

  </script>


    <script type="text/javascript">

  

  var arr_product = [];
  var opt_product = '';
  var main_product= '';
  var url         = APP_URL+'/staff/get_multiple_product_all_details';
    
  function add_product()
  {
  
    var product_id = parseInt($("#product_id").val());
    var quantity   = parseInt($("#quantity").val());
    var sale_amount= parseInt($('#sale_amount').val());
    var min_sale   = parseInt($('#min_sales_amt').val());
    var max_sale   = parseInt($('#max_sales_amt').val());
    var op_pdts    = $('#opt_pdt').val();
    var flag       = 1;

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

    if(sale_amount == 0)
    {
      flag = 1;
      $("#sale_message").hide();
    }
    else if(max_sale!=0 && sale_amount>max_sale) //
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
    if(op_pdts!='')
    {
      for(var i=0;i<op_pdts.length;i++)
      {
         exit_product=findValueInArray(op_pdts[i], arr_product);
         if(exit_product=="1")
         {
           $("#alreadytexit").show();
         }
         else{
          $("#alreadytexit").hide();
         }
      }

    }
    if(product_id!='' && quantity !='' && exit_product!=1 && flag==1)
    {
      $("#user_id").attr('disabled', true);
      arr_product.push(product_id);
     //console.log(arr_product);
     if(op_pdts!='')
     {
      arr_product = arr_product.concat(op_pdts)
     }

      var add_counts=parseInt(count_product)+1;
      if(op_pdts!='')
      {
        add_counts = parseInt(add_counts)+op_pdts.length;
      }
      $("#count_product").val(add_counts); 

      var prd_array  = [];

      prd_array.push(product_id);
      if(op_pdts!='')
       {
        prd_array = prd_array.concat(op_pdts)
       }
    
      var amt = '';

      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: prd_array,
          },
          success: function (data)
          {     
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
                
                  if(i!=0)
                  {
                    quantity    = 1;
                    opt_product = 1;
                    sale_amount = proObj[i]["unit_price"];
                    if(sale_amount==""){
                      sale_amount = 0;
                    }
                  }
                  else
                  {
                    var company = proObj[i]["company_id"];
                    opt_product = 0;
                    main_product = proObj[i]["id"];
                  }

                  amt = quantity * sale_amount;
              
                //pdfsec='<input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'">';
              
                htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="100px" height="100px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td><td><input type="text" value="'+quantity+'" id="qn_'+proObj[i]["id"]+'" class="quantity" data-id="'+proObj[i]["id"]+'"></td><td><input type="text" value="'+sale_amount+'" id="sa_'+proObj[i]["id"]+'" class="sale_amt" data-id="'+proObj[i]["id"]+'"></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="btn btn-danger btn-xs " onclick="deletepro('+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a><a class="btn btn-info btn-xs " onclick="editpro('+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'"  title="Edit"><span class="glyphicon glyphicon-edit"></span></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'" class="hqn_'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"></tr>';
              

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

    function editpro(product_id)
    {
      var sale_amt = parseInt($("#sa_"+product_id).val());
      var quantity = parseInt($("#qn_"+product_id).val());
      var url      = APP_URL+'/staff/get_product_all_details';
      var min      = '';
      var max      = '';
      var tot_amt  = sale_amt * quantity;

      $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
               var proObj   = JSON.parse(data);
               var htmlscon = '';
               for (var i = 0; i < proObj.length; i++) 
               {
                   min     = parseInt(proObj[i]["min_sale_amount"]);
                   max     = parseInt(proObj[i]["max_sale_amount"]);

                   if(sale_amt == 0)
                  {
                    $("#am_"+product_id).val(tot_amt);
                    $(".hqn_"+product_id).val(quantity);
                    $(".hamt_"+product_id).val(tot_amt);
                    $(".hsa_"+product_id).val(sale_amt);
                  }
                  else if(max!=0 && sale_amt>max) //max_sale!=0 &&
                  {
                    alert('invalid amount. contact admin');
                  }
                  else if(min!=0 &&  sale_amt<min) //min_sale!=0 && 
                  {
                    alert('invalid amount. contact admin');
                  }
                  else
                  {
                    $("#am_"+product_id).val(tot_amt);
                    $(".hqn_"+product_id).val(quantity);
                    $(".hamt_"+product_id).val(tot_amt);
                    $(".hsa_"+product_id).val(sale_amt);
                  }
                }
            }
      });

    }
    
    </script>
@endsection
