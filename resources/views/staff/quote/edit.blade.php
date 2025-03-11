

@extends('staff/layouts.app')

@section('title', 'Edit Quote')

@section('content')


<section class="content-header">
      <h1>
        Edit Quote
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.quote.index')}}">Manage Quote</a></li>
        <li class="active">Edit Quote</li>
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

            <p class="error-content alert-danger">
            {{ $errors->first('name') }}
            {{ $errors->first('image_name') }}
            </p>

            <form   role="form" name="frm_staffquote" id="frm_staffquote" method="post" action="{{ route('staff.quote.update', $staff_quote->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
                <div class="box-body">


                <div class="form-group col-md-3">
                  <label>Company*</label>
                  <select name="company_id" id="company_id" class="form-control">
                    <option value="">-- Select Company --</option>
                    <?php
                    foreach($company as $item) {
                      $sel = ($staff_quote->company_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';
                       
                    } ?>
                  </select>
                  <span class="error_message" id="company_id_message" style="display: none">Field is required</span>
                </div>

              <div class="form-group col-md-3">
                  <label>Product*</label>
                  <select name="product_id" id="product_id" class="form-control">
                    <option value="">-- Select Product --</option>
                    <?php
                    foreach($products as $item) {
                     // $sel = ($staff_quote->product_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.' selected>'.$item->name.'</option>';
                       
                    } ?>

                  </select>
                  <span class="error_message" id="product_id_message" style="display: none">Field is required</span>
                </div>

              </div>

                <div class="form-group col-md-3">
                  <label>Customer*</label>
                  <select name="user_id" id="user_id" class="form-control">
                    <option value="">-- Select Customer --</option>
                    <?php
                    foreach($user as $item) {
                      $sel = ($staff_quote->user_id == $item->id) ? 'selected': '';
                        echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                       
                    } ?>
                  </select>
                  <span class="error_message" id="user_id_message" style="display: none">Field is required</span>
                </div>


              <!-- /.box-body -->

              <div class="box-footer">
             
              
              <span class="error_message" id="alreadytexit" style="display: none">Product already exit</span>
              <button type="button" class="btn btn-primary"  onclick="add_product()">Add</button>
              
              </div>


               <table id="cmsTable" class="table table-bordered table-striped data-" style="">
                <thead>
                <tr>
                
                  <th>Image</th>
                  <th>Name</th>
                   <th>Price</th>
                   <th>Action</th>
                  </tr>
                </thead>
                <tbody id="tabledata">
                
                <?php
                $k=0;
                    foreach($quote_details as $item) {
                    
                      $products_det = App\Product::find($item->product_id);
                      ?>
                      <input type="hidden" name="product_id[]" value="<?php echo $item->product_id;?>">
                      <input type="hidden" name="staff_quote_id[]" value="<?php echo $item->id;?>">
                    <tr  data-from ="staffquote" class="tr_<?php echo $item->product_id;?>">
                    <td>
                       <?php 
                       if($products_det->image_name!='')
                       {
                        ?>
                        <img width="100px" height="100px" src="<?php echo asset("storage/app/public/products/thumbnail/$products_det->image_name") ?>" />
                        <?php 
                       }
                       else{
                         ?>
                         <img  width="100px" height="100px"  src="{{ asset('images/no-image.jpg') }}" alt="">
                        <?php 
                       }
                       ?>
                       </td>
                       <td><?php echo $products_det->name; ?></td>
                       <td><?php echo $products_det->unit_price; ?></td>
                       <td><a class="btn btn-danger btn-xs " onclick="deletepro_ajax('<?php echo $item->product_id;?>','<?php echo $item->id; ?>')" data-id="<?php echo $item->product_id;?>"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td>
                    </tr>
                    <?php
                    $k++;
                    }
                    ?>
               </table>
               <input type="hidden" name="count_product" id="count_product" value="<?php echo $k;?>">

              <div class="box-footer">
  
               <!-- <a target="_blank" href="{{ route('staff.generate_pdfstaff') }}" class="btn btn-primary" >Preview</a> -->
               <button type="submit" class="btn btn-primary" >Save</button>
          
              </div>


            </form>

            <form name="pdfview" id="pdfview" action="{{route('staff.generate_pdfstaff')}}" target="_blank">
          <div id="pdfsec">
          <?php 
             foreach($quote_details as $item) {
          ?>
              <input type="hidden" name="product_id[]" value="<?php echo $item->product_id;?>">
          <?php
            }
          ?>
          </div>
          <input type="hidden" name="company_id" id="company_id_pdf" value="<?php echo $staff_quote->company_id;?>">
          <input type="hidden" name="user_id" id="user_id_pdf" value="<?php echo $staff_quote->user_id;?>">
          
            <button type="submit" class="btn btn-primary" >Preview</button>
            </form>

          </div>

        </div>
      </div>
</section>


@endsection

@section('scripts')
 
<script type="text/javascript">
    var arr_product = [];
    
  function generate_pdf()
  {
    var url = APP_URL+'/staff/generate_pdf';
    $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: 10,
          },
          success: function (data)
          {  
            $("#modalpdf").modal("show");
           // alert(APP_URL)
           var imgs="{{asset('pdf/')}}/"+data;
            var iframe = $('<iframe>');
            iframe.attr('src',imgs);
            $('#targetDiv').append(iframe);

          }
    });
  }  
  function add_product()
  {
    var company_id=$("#company_id").val();
    var user_id=$("#user_id").val();
    var product_id=$("#product_id").val();
    if(company_id=="")
    {
      $("#company_id_message").show();
    }
    else{
      $("#company_id_message").hide();
    }

    if(user_id=="")
    {
      $("#user_id_message").show();
    }
    else{
      $("#user_id_message").hide();
    }

    if(product_id=="")
    {
      $("#product_id_message").show();
    }
    else{
      $("#product_id_message").hide();
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
    if(company_id!='' && product_id!='' && user_id!='' && exit_product!=1)
    {
     
      arr_product.push(product_id);
     //console.log(arr_product);
     

      var add_counts=parseInt(count_product)+1;
      $("#count_product").val(add_counts); 
    
      var url = APP_URL+'/staff/get_product_all_details';
      $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: product_id,
          },
          success: function (data)
          {     $(".noresult").hide();
            $("#preview_btn").show();
            $("#save_btn").show();
            var proObj = JSON.parse(data);
             htmls='';
           
            for (var i = 0; i < proObj.length; i++) {
              if(proObj[i]["image_name"]=='null' || proObj[i]["image_name"]=='')
              {
                var imgs="{{asset('images/')}}/no-image.jpg";
                
              }
              else{
                var imgs="{{asset('storage/app/public/products/thumbnail/')}}/"+proObj[i]["image_name"];
              }
              pdfsec='<input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'">';
              htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="100px" height="100px" src="'+imgs+'" /></td><td>'+proObj[i]["name"]+'</td><td>'+proObj[i]["unit_price"]+'</td><td> <a class="btn btn-danger btn-xs " onclick="deletepro('+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="staff_quote_id[]" value="0"></tr>';
              }
              $("#tabledata").append(htmlscontent);
              $("#pdfsec").append(pdfsec);
              
           
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
      if(add_counts=="0"){
        $("#preview_btn").hide();
            $("#save_btn").hide();
            $(".noresult").show();
      }

   


    }

    function deletepro_ajax(product_id,staff_quote_id)
    {
      var count_product=$("#count_product").val();
      
      var add_counts=parseInt(count_product)-1;
      $("#count_product").val(add_counts); 
      $(".tr_"+product_id).remove();
      if(add_counts=="0"){
        $("#preview_btn").hide();
            $("#save_btn").hide();
            $(".noresult").show();
      }
      var url = APP_URL+'/staff/delete_product_staff';
         $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            staff_quote_id: staff_quote_id,
          },
          success: function (data)
          {  
            
          }
        });  



    }
    jQuery(document).ready(function() {
  
      jQuery(".deleteItem").click(function() {alert()
   /*    var product_id=$(this).attr("data-id");
        alert(product_id)
        $(".tr_"+product_id).remove();*/
      });

        jQuery("#user_id").change(function() {
            var user_id=$(this).val();
            $("#user_id_pdf").val(user_id);
        });
       jQuery("#company_id").change(function() {
        $("#preview_btn").hide();
            $("#save_btn").hide();

             $(".noresult").show();
              $("#pdfsec").html('');


         var company_id=$(this).val();
         
         $("#company_id_pdf").val(company_id);
        
         var url = APP_URL+'/staff/get_product_company';
         //alert(company_id);
         $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
              company_id: company_id,
          },
          success: function (data)
          {  
            var proObj = JSON.parse(data);
             htmls='';
             htmls +='<option value="">--Select product--</option>';
            for (var i = 0; i < proObj.length; i++) {
               htmls +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
             
              }
           
              $("#product_id").html(htmls);
              

//console.log(htmls);
         //$('#product_id').html(htmls);
          }
        });  

       });

    });

     

    </script>

@endsection
