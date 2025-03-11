@extends('staff/layouts.app')



@section('title', 'Add Msp')



@section('content')



<section class="content-header">

      <h1>

        Add Msp

      </h1>

      <ol class="breadcrumb">

        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>

        <li><a href="{{route('staff.msp.index')}}">Manage Msp</a></li>

        <li class="active">Add Msp</li>

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



            <form role="form" name="frm_brand" id="frm_brand" method="post" action="{{route('staff.msp.store')}}" enctype="multipart/form-data" >

               @csrf

                <div class="box-body msp-createpage">
                  <div class="row">

                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="image_name">Brand</label>
                  <select name="brand_id" id="brand_id" class="form-control" onchange="change_brand(this.value)">
                    <option value="">-- Select Brand --</option>
                    <?php
                      foreach($brand as $item) {
                        $sel = (old('brand_id') == $item->id) ? 'selected': '';
                          echo '<option value="'.$item->id.'" '.$sel.'>'.$item->name.'</option>';

                      } ?>
                    </select>
                  <span class="error_message" id="brand_id_message" style="display: none">Field is required</span>
                  <div class="brand_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>


                  <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="image_name">Category</label>
                  <select name="category_type_id" id="category_type_id" class="form-control" >
                    <option value="">-- Select Category --</option>
                    @foreach ($category_type as $cattype)
                      <option value="{{$cattype->id}}">{{$cattype->name}}</option>
                      @endforeach
                    </select>
                  <span class="error_message" id="category_type_id_message" style="display: none">Field is required</span>
                  <div class="categorytype_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>

                    
  <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="image_name">Care Area</label>
                  <select name="category_id" id="category_id" class="form-control" >
                    <option value="">-- Select Care Area --</option>
                   
                    </select>
                  <span class="error_message" id="category_id_message" style="display: none">Field is required</span>
                  <div class="category_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>


                    <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                  <label for="image_name">Search By Product Name</label>
                  <div class="input-group">
                    <input type="text" name="search_word" id="search_word" class="form-control" >
                    <div class="input-group-btn">
                      <button class="btn btn-default" type="button" onclick="$('#search_word').change()">
                        <i class="glyphicon glyphicon-search"></i> search
                      </button>
                    </div>
                  </div>
                  
                  <span class="error_message" id="search_word_message" style="display: none">Field is required</span>
                  <div class="search_word_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                    </div>

                  </div>


    <table id="cmsTable1" class="table table-bordered table-striped data-" style="display:none;">
                <thead>
                <tr>
                  
                  <th>Show in Page</th>
                  <th>Product</th>
                  <th>Cost</th>
                  <th>Transp. Cost</th>
                  <th>Customs Cost</th>
                  <th>Other Cost</th>
                  <th>Total Cost</th>
                  <th>Profit</th>
                  <th>Proposed MSP</th>
                  <th>Quote</th>
                  <th> Quote Price</th>
                  <th> Tax Per.</th>
                  <th> HSN Code</th>
                  <th>Per. Online Price</th>
                  <th >Online Price Site</th>
                  <th>Discount</th>
                  <th>Discount Price</th>
                  <th>Incentive</th>
                  <th>Incentive Amount</th>
                  <th>Diff. MSP from Prev</th>
                  
                </tr>
                </thead>
                <tbody id="form_product">
                   
                </tbody>
              </table>

 <div class="box-footer" style="display:none;">

<button type="button" class="mdm-btn submit-btn"  onclick="validate_from()">Submit</button>
<div class="saveform_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>

<button type="button" class="mdm-btn cancel-btn" onClick="window.location.href='{{route('staff.msp.index')}}'">Cancel</button>

</div>


<br><br><br>
          


                </div>
             

            </form>

          </div>



        </div>

      </div>

</section>


<div class="modal fade" id="prv_msp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
 <div class="modal-dialog" role="document">
 <div class="modal-content">
 <div class="modal-header">
 <h3 class="modal-title" >Pervious MSP</h3>
 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
 <span aria-hidden="true">&times;</span>
 </button>
 </div>
 <div class="modal-body">
 
 <table id="cmsTable1" class="table table-bordered table-striped data-" >
                <thead>
                <tr>
                  
                  
                  <th>Msp Updated Date</th>
                  <th>Proposed MSP</th>
                
                  
                </tr>
                </thead>
                <tbody id="form_mspproduct">
                   
                </tbody>
              </table>

 </div>
 <div class="modal-footer">
 <button type="button" class="mdm-btn submit-btn" data-dismiss="modal">Close</button>
 
 </div>
 </div>
 </div>
</div>


@endsection



@section('scripts')


<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>



  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script type="text/javascript">
function productstatuschange(element,id){
  if($(element).is(':checked'))
  {
    $.get('{{route("staff.product_show_status")}}?status=Y&product_id='+id,function(res){

    })
  }
  else{

    $.get('{{route("staff.product_show_status")}}?status=N&product_id='+id,function(res){

    })
  }
}
function validate_from()
{
  $(".saveform_gif").show();
  var url = APP_URL+'/staff/save_mspusing_ajax';
  $.ajax({
                url: url, // url where to submit the request
                type : "POST", // type of action POST || GET
                dataType : 'json', // data type
                data : $("#frm_brand").serialize(), // post data || get data
                success : function(result) {$(".saveform_gif").hide();
                    // you can see the result from the console
                    // tab of the developer tools
                   // console.log(result);
                   location.reload();
                },
                error: function(xhr, resp, text) {$(".saveform_gif").hide();location.reload();
                    console.log(xhr, resp, text);
                }
            })

}
    function show_prv_msp(product_id)
    {
      var url = APP_URL+'/staff/show_prv_msp';

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
         
            var htmls='';
            
          for (var i = 0; i < proObj.length; i++) {
            htmls +=' <tr>'+
            '<td>'+proObj[i]["updated_at"]+'</td>'+
            '<td>'+proObj[i]["pro_msp"]+'</td></tr>';
          }
         
$("#form_mspproduct").html(htmls);
        }

      });
      $("#prv_msp").modal("show");
    }
    jQuery(document).ready(function() {

      
$("#search_word").keydown(function(event){
    var id = event.key || event.which || event.keyCode || 0;   
    if (id == 13) {
      $("#search_word").change();
      event.preventDefault();
    }
 })
 $("#search_word").change(function(){
        
        var brand_id=$("#brand_id").val();
        var category_type_id=$("#category_type_id").val();
        var category_id=$("#category_id").val();
        
        var search_word=$("#search_word").val();
        
          $(".search_word_gif").show();
        
        var url = APP_URL+'/staff/change_brand_for_product';
        
         $.ajax({
        
                type: "POST",
        
                cache: false,
        
                url: url,
        
                data:{
        
                  brand_id: brand_id,category_type_id:category_type_id,category_id:category_id,search_word:search_word
        
                },
        
                success: function (data)
        
                {       $(".search_word_gif").hide();

                  
                  $(".box-footer").show();
                  $("#cmsTable1").show();
                  var proObj = JSON.parse(data);
        
                  states_val='';
        
                  states_val +='<option value="">Select Product</option>';
                    var htmls='';
                  for (var i = 0; i < proObj.length; i++) {
                   
                      var unit_price=proObj[i]["unit_price"];
                   
                    
                 
                   var trans_cost=proObj[i]["trans_cost"];
                   var customs_cost=proObj[i]["customs_cost"];
                   var other_cost=proObj[i]["other_cost"];
                   var profit=proObj[i]["profit"];
                   
                   
                  var total_per=parseFloat(trans_cost)+parseFloat(customs_cost)+parseFloat(other_cost);
        
                  var total_peramount= unit_price*total_per/100;
                   var tot_price= parseFloat(unit_price)+parseFloat(total_peramount);
        
                   var tot_price=tot_price.toFixed(2);
        
                   var propse_val=tot_price*profit/100;
                   var propse_val=propse_val.toFixed(2);
                   
                   var propse_val=parseFloat(tot_price)+parseFloat(propse_val);
        
                   
                   var quote=proObj[i]["quote"];
                  //  var quote_per=propse_val*quote/100;
                  //  var quote_tot=parseFloat(quote_per)+parseFloat(propse_val);
                  var quote_tot=proObj[i]["quote_price"];
        
                    var online=proObj[i]["percent_online"];
                  //  var online_per=quote_tot*online/100;
                  //  var online_tot=parseFloat(online_per)+parseFloat(quote_tot);
                  var online_tot=proObj[i]["online_price"];
        
                   var discount=proObj[i]["discount"];
                  //  var discount_per=online_tot*discount/100;
                  //  var discount_tot=parseFloat(online_tot)-parseFloat(discount_per);
                  var discount_tot=proObj[i]["discount_price"];
        
                   var incentive = proObj[i]["incentive"];
                   var prop_total = parseFloat(propse_val)-parseFloat(tot_price);
                   var incentive_amount=incentive*prop_total/100;
        
                   var product_id = proObj[i]["id"];
                   var url = '{{ route("staff.products.edit", ":id") }}';
                   url = url.replace(':id', product_id);
                
        
                    htmls +=' <tr>'+
        
                    '<td data-th="Show in Page"><input type="checkbox" '+proObj[i]['show_inPage']+' class="togglebutton" onchange="productstatuschange(this,'+product_id+')" data-toggle="toggle" data-size="mini" data-onstyle="success" data-offstyle="danger" data-on="Yes" data-off="No"></td>'+
        
                              '<td data-th="Product" >'+
                              '<label><a href="'+url+'" target="_blank">'+proObj[i]["name"]+'</a></label>'+
                             
                              '<span class="error_message" id="product_id_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td  data-th="Cost" >'+
                              '<input type="text" id="cost'+i+'" name="cost[]"  value="'+proObj[i]["unit_price"]+'" class="form-control" placeholder="Cost"  onkeyup="change_cost('+i+')">'+
                              '<span class="error_message" id="cost_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td  data-th="Transp. Cost" >'+
                              '<input class="percentage" type="text" id="trans_cost'+i+'" name="trans_cost[]" value="'+trans_cost+'" class="form-control" placeholder="Transportation Cost" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                              '<span class="error_message" id="trans_cost_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td  data-th="Customs Cost" >'+
                              '<input class="percentage" type="text" id="customs_cost'+i+'" name="customs_cost[]" value="'+customs_cost+'" class="form-control" placeholder="Customs Cost"  onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                              '<span class="error_message" id="customs_cost_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Other Cost" >'+
                              '<input class="percentage" class="percentage" type="text" id="other_cost'+i+'" name="other_cost[]" value="'+other_cost+'" class="form-control" placeholder="Other Cost"  onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                              '<span class="error_message" id="other_cost_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Total Cost" >'+
                              '<input type="text" id="total_cost'+i+'" readonly name="total_cost[]" value="'+tot_price+'" class="form-control" placeholder="Total Cost">'+
                              '<span class="error_message" id="total_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Profit" >'+
                              '<input class="percentage" class="percentage" type="text" id="profit'+i+'" name="profit[]" value="'+profit+'" class="form-control" placeholder="Profit" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                              '<span class="error_message" id="profit_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Proposed MSP" >'+
                              '<input type="text" id="pro_msp'+i+'" name="pro_msp[]" readonly value="'+propse_val.toFixed(2)+'" class="form-control" placeholder="Proposed MSP">'+
                              '<span class="error_message" id="pro_msp_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Quote" >'+
                              '<input class="percentage" class="percentage" type="text" id="quote'+i+'" name="quote[]" value="'+quote+'" class="form-control" placeholder="Quote" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                              '<span class="error_message" id="quote_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th=" Quote Price" >'+
                              '<input type="text" id="pro_quote_price'+i+'" readonly name="pro_quote_price[]" value="'+quote_tot.toFixed(2)+'" class="form-control" placeholder="Proposed Quote Price">'+
                              '<span class="error_message" id="pro_quote_price_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th=" Tax Per." >'+
                              '<input type="text" id="tax_per'+i+'"  name="tax_per[]" value="'+proObj[i]["tax_per"]+'" class="percentage" placeholder="Tax Per."><span class="percentage">%</span>'+
                              '<span class="error_message" id="tax_per_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th=" HSN Code" >'+
                              '<input type="text" id="hsn_code'+i+'"  name="hsn_code[]" value="'+proObj[i]["hsn_code"]+'" class="form-control" placeholder="HSN Code">'+
                              '<span class="error_message" id="hsn_code_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Per. Online Price" >'+
                              '<input class="percentage" class="percentage" type="text" id="percent_online'+i+'" name="percent_online[]" value="'+online+'" class="form-control" placeholder="Percentage of Online Price" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                              '<span class="error_message" id="percent_online_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Online Price Site" >'+
                              '<input type="text" id="online_price'+i+'" name="online_price[]" value="'+online_tot.toFixed(2)+'" class="form-control" placeholder="Online Price Site">'+
                              '<span class="error_message" id="online_price_message" style="display: none">Field is required</span>'+
                                
                                '<span class="rq-box"><input type="checkbox" attr-id="'+i+'" class="form-check-input req_quote" id="req_quote'+i+'" onclick="handleClick('+i+',this)">Request Quote</span>'+
                               
                           
                              '</td>'+
        
                              '<td data-th="Discount" >'+
                              '<input type="text" id="discount'+i+'" name="discount[]" value="'+discount+'" class="percentage" placeholder="Discount" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                              '<span class="error_message" id="discount_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Discount Price" >'+
                              '<input type="text" id="discount_price'+i+'" readonly name="discount_price[]" value="'+discount_tot.toFixed(2)+'" class="form-control" placeholder="Discount" >'+
                              '<span class="error_message" id="discount_price_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Incentive" >'+
                              '<input class="percentage" class="percentage" type="text" id="incentive'+i+'"  name="incentive[]" value="'+proObj[i]["incentive"]+'" class="form-control" placeholder="Incentive" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                              '<span class="error_message" id="incentive_message" style="display: none">Field is required</span>'+
                              '</td>'+
                              '<td data-th="Incentive Amount" >'+
                              '<input type="text" id="incentive_amount'+i+'"  name="incentive_amount[]" value="'+incentive_amount+'" class="form-control" placeholder="Incentive" readonly>'+
                              '<span class="error_message" id="incentive_amount_message" style="display: none">Field is required</span>'+
                              '</td>'+
        
                              '<td data-th="Diff. MSP from Prev" >'+
                              '<input type="text" id="mspprev'+i+'" name="mspprev[]" value="" class="form-control" placeholder="Difference in MSP from Previous" >'+
                              '<span class="error_message" id="mspprev_message" style="display: none">Field is required</span>'+
                              '<a onclick="show_prv_msp('+proObj[i]["id"]+')">Prev MSP</a>'+
                              '<input type="hidden" id="product_id'+i+'" name="product_id[]" value="'+proObj[i]["id"]+'" class="form-control" placeholder="Product"  >  '+
                              '</td>'+
        
                             
                              '</tr>';
                   
        
                    }
        
                    $("#form_product").html(htmls);
                    $('.togglebutton').bootstrapToggle({
                      on: 'Yes',
                      off: 'No'
                    });
                }
        
              });
        
        
        
        
              });




 $("#category_id").change(function(){
        
var brand_id=$("#brand_id").val();
var category_type_id=$("#category_type_id").val();
var category_id=$("#category_id").val();
var search_word=$("#search_word").val();


  $(".category_gif").show();

var url = APP_URL+'/staff/change_brand_for_product';

 $.ajax({

        type: "POST",

        cache: false,

        url: url,

        data:{

          brand_id: brand_id,category_type_id:category_type_id,category_id:category_id,search_word:search_word,

        },

        success: function (data)

        {      $(".category_gif").hide();
          $("#cmsTable1").show();
          var proObj = JSON.parse(data);

          states_val='';

          states_val +='<option value="">Select Product</option>';
            var htmls='';
          for (var i = 0; i < proObj.length; i++) {
            
           
              var unit_price=proObj[i]["unit_price"];
           
            
         
           var trans_cost=proObj[i]["trans_cost"];
           var customs_cost=proObj[i]["customs_cost"];
           var other_cost=proObj[i]["other_cost"];
           var profit=proObj[i]["profit"];
           
           
          var total_per=parseFloat(trans_cost)+parseFloat(customs_cost)+parseFloat(other_cost);

          var total_peramount= unit_price*total_per/100;
           var tot_price= parseFloat(unit_price)+parseFloat(total_peramount);

           var tot_price=tot_price.toFixed(2);

           var propse_val=tot_price*profit/100;
           var propse_val=propse_val.toFixed(2);
           
           var propse_val=parseFloat(tot_price)+parseFloat(propse_val);

           
                   
           var quote=proObj[i]["quote"];
            //  var quote_per=propse_val*quote/100;
            //  var quote_tot=parseFloat(quote_per)+parseFloat(propse_val);
            var quote_tot=proObj[i]["quote_price"];
  
              var online=proObj[i]["percent_online"];
            //  var online_per=quote_tot*online/100;
            //  var online_tot=parseFloat(online_per)+parseFloat(quote_tot);
            var online_tot=proObj[i]["online_price"];
  
              var discount=proObj[i]["discount"];
            //  var discount_per=online_tot*discount/100;
            //  var discount_tot=parseFloat(online_tot)-parseFloat(discount_per);
            var discount_tot=proObj[i]["discount_price"];

          

           var incentive = proObj[i]["incentive"];
           var prop_total = parseFloat(propse_val)-parseFloat(tot_price);
           var incentive_amount=incentive*prop_total/10;

           var product_id = proObj[i]["id"];
           var url = '{{ route("staff.products.edit", ":id") }}';
           url = url.replace(':id', product_id);
        

            htmls +=' <tr>'+

                    '<td data-th="Show in Page"><input type="checkbox" '+proObj[i]['show_inPage']+' class="togglebutton" onchange="productstatuschange(this,'+product_id+')" data-toggle="toggle" data-size="mini" data-onstyle="success" data-offstyle="danger" data-on="Yes" data-off="No"></td>'+

                      '<td data-th="Product" >'+
                      '<label><a href="'+url+'" target="_blank">'+proObj[i]["name"]+'</a></label>'+
                     
                      '<span class="error_message" id="product_id_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td  data-th="Cost" >'+
                      '<input type="text" id="cost'+i+'" name="cost[]"  value="'+proObj[i]["unit_price"]+'" class="form-control" placeholder="Cost"  onkeyup="change_cost('+i+')">'+
                      '<span class="error_message" id="cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td  data-th="Transp. Cost" >'+
                      '<input class="percentage" type="text" id="trans_cost'+i+'" name="trans_cost[]" value="'+trans_cost+'" class="form-control" placeholder="Transportation Cost" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="trans_cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td  data-th="Customs Cost" >'+
                      '<input class="percentage" type="text" id="customs_cost'+i+'" name="customs_cost[]" value="'+customs_cost+'" class="form-control" placeholder="Customs Cost"  onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="customs_cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Other Cost" >'+
                      '<input class="percentage" class="percentage" type="text" id="other_cost'+i+'" name="other_cost[]" value="'+other_cost+'" class="form-control" placeholder="Other Cost"  onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="other_cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Total Cost" >'+
                      '<input type="text" id="total_cost'+i+'" readonly name="total_cost[]" value="'+tot_price+'" class="form-control" placeholder="Total Cost">'+
                      '<span class="error_message" id="total_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Profit" >'+
                      '<input class="percentage" class="percentage" type="text" id="profit'+i+'" name="profit[]" value="'+profit+'" class="form-control" placeholder="Profit" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="profit_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Proposed MSP" >'+
                      '<input type="text" id="pro_msp'+i+'" name="pro_msp[]" readonly value="'+propse_val.toFixed(2)+'" class="form-control" placeholder="Proposed MSP">'+
                      '<span class="error_message" id="pro_msp_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Quote" >'+
                      '<input class="percentage" class="percentage" type="text" id="quote'+i+'" name="quote[]" value="'+quote+'" class="form-control" placeholder="Quote" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="quote_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th=" Quote Price" >'+
                      '<input type="text" id="pro_quote_price'+i+'" readonly name="pro_quote_price[]" value="'+quote_tot.toFixed(2)+'" class="form-control" placeholder="Proposed Quote Price">'+
                      '<span class="error_message" id="pro_quote_price_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th=" Tax Per." >'+
                      '<input type="text" id="tax_per'+i+'"  name="tax_per[]" value="'+proObj[i]["tax_per"]+'" class="percentage" placeholder="Tax Per."><span class="percentage">%</span>'+
                      '<span class="error_message" id="tax_per_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th=" HSN Code" >'+
                      '<input type="text" id="hsn_code'+i+'"  name="hsn_code[]" value="'+proObj[i]["hsn_code"]+'" class="form-control" placeholder="HSN Code">'+
                      '<span class="error_message" id="hsn_code_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Per. Online Price" >'+
                      '<input class="percentage" class="percentage" type="text" id="percent_online'+i+'" name="percent_online[]" value="'+online+'" class="form-control" placeholder="Percentage of Online Price" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="percent_online_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Online Price Site" >'+
                      '<input type="text" id="online_price'+i+'" name="online_price[]" value="'+online_tot.toFixed(2)+'" class="form-control" placeholder="Online Price Site">'+
                      '<span class="error_message" id="online_price_message" style="display: none">Field is required</span>'+
                   
                        '<span class="rq-box"><input type="checkbox" attr-id="'+i+'" class="form-check-input req_quote" id="req_quote'+i+'" onclick="handleClick('+i+',this)">Request Quote</span>'+
                       
                   
                      '</td>'+

                      '<td data-th="Discount" >'+
                      '<input type="text" id="discount'+i+'" name="discount[]" value="'+discount+'" class="percentage" placeholder="Discount" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="discount_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Discount Price" >'+
                      '<input type="text" id="discount_price'+i+'" readonly name="discount_price[]" value="'+discount_tot.toFixed(2)+'" class="form-control" placeholder="Discount" >'+
                      '<span class="error_message" id="discount_price_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Incentive" >'+
                      '<input class="percentage" class="percentage" type="text" id="incentive'+i+'"  name="incentive[]" value="'+proObj[i]["incentive"]+'" class="form-control" placeholder="Incentive" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="incentive_message" style="display: none">Field is required</span>'+
                      '</td>'+
                      '<td data-th="Incentive Amount" >'+
                      '<input type="text" id="incentive_amount'+i+'"  name="incentive_amount[]" value="'+incentive_amount+'" class="form-control" placeholder="Incentive" readonly>'+
                      '<span class="error_message" id="incentive_amount_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Diff. MSP from Prev" >'+
                      '<input type="text" id="mspprev'+i+'" name="mspprev[]" value="" class="form-control" placeholder="Difference in MSP from Previous" >'+
                      '<span class="error_message" id="mspprev_message" style="display: none">Field is required</span>'+
                      '<a onclick="show_prv_msp('+proObj[i]["id"]+')">Prev MSP</a>'+
                      '<input type="hidden" id="product_id'+i+'" name="product_id[]" value="'+proObj[i]["id"]+'" class="form-control" placeholder="Product"  >  '+
                      '</td>'+

                     
                      '</tr>';
           

            }

            $("#form_product").html(htmls);
            $('.togglebutton').bootstrapToggle({
              on: 'Yes',
              off: 'No'
            });
        }

      });




      });

      
$("#category_type_id").change(function(){

$("#category_id").html('<option value="">Select Care Area</option>');
  var APP_URL = {!! json_encode(url('/')) !!};
  var url = APP_URL+'/staff/sort_brand_categorytypeuse_carearea';
  $(".categorytype_gif").show();
  
  $.ajax({
    url: url,
   // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    //dataType: "json",
    data: {
      category_type_id:$("#category_type_id").val(),brand_id:$("#brand_id").val(),
    },
    success: function( data ) {
      //alert( data.length );
      $(".categorytype_gif").hide();
      var proObj = JSON.parse(data);
    //  console.log(proObj)
        var  htmls=' ';
        htmls +="<option value=''>Select Care Area</option>";
  /*
  $.each(proObj, function(k, v) {
    //alert(k+'--'+v)
    htmls +="<option value='"+k+"'>"+v+"</option>";
  });*/

         for (var i = 0; i < proObj.length; i++) {
          htmls +="<option value='"+proObj[i]["id"]+"'>"+proObj[i]["name"]+"</option>";
             }
     
        $("#category_id").html(htmls);
        
    }
    });

    
var brand_id=$("#brand_id").val();
var category_type_id=$("#category_type_id").val();
var category_id=$("#category_id").val();
var search_word=$("#search_word").val();




var url = APP_URL+'/staff/change_brand_for_product';
$(".categorytype_gif").show();
 $.ajax({

        type: "POST",

        cache: false,

        url: url,

        data:{

          brand_id: brand_id,category_type_id:category_type_id,category_id:category_id,search_word:search_word,

        },

        success: function (data)

        {    
          $(".categorytype_gif").hide();
          $("#cmsTable1").show();
          var proObj = JSON.parse(data);

          states_val='';

          states_val +='<option value="">Select Product</option>';
            var htmls='';
          for (var i = 0; i < proObj.length; i++) {

             var unit_price=proObj[i]["unit_price"];
         
           var trans_cost=proObj[i]["trans_cost"];
           var customs_cost=proObj[i]["customs_cost"];
           var other_cost=proObj[i]["other_cost"];
           var profit=proObj[i]["profit"];
           
          var total_per=parseFloat(trans_cost)+parseFloat(customs_cost)+parseFloat(other_cost);

          var total_peramount= unit_price*total_per/100;
           var tot_price= parseFloat(unit_price)+parseFloat(total_peramount);

           var tot_price=tot_price.toFixed(2);

           var propse_val=tot_price*profit/100;
           var propse_val=propse_val.toFixed(2);
           
           var propse_val=parseFloat(tot_price)+parseFloat(propse_val);

           
                   
           var quote=proObj[i]["quote"];
            //  var quote_per=propse_val*quote/100;
            //  var quote_tot=parseFloat(quote_per)+parseFloat(propse_val);
            var quote_tot=proObj[i]["quote_price"];
  
              var online=proObj[i]["percent_online"];
            //  var online_per=quote_tot*online/100;
            //  var online_tot=parseFloat(online_per)+parseFloat(quote_tot);
            var online_tot=proObj[i]["online_price"];
  
              var discount=proObj[i]["discount"];
            //  var discount_per=online_tot*discount/100;
            //  var discount_tot=parseFloat(online_tot)-parseFloat(discount_per);
            var discount_tot=proObj[i]["discount_price"];

            

           var incentive = proObj[i]["incentive"];
           var prop_total = parseFloat(propse_val)-parseFloat(tot_price);
           var incentive_amount=incentive*prop_total/100;
           
           var product_id = proObj[i]["id"];
           var url = '{{ route("staff.products.edit", ":id") }}';
           url = url.replace(':id', product_id);

            htmls +=' <tr>'+
            '<td data-th="Show in Page"><input type="checkbox" '+proObj[i]['show_inPage']+' class="togglebutton" onchange="productstatuschange(this,'+product_id+')" data-toggle="toggle" data-size="mini" data-onstyle="success" data-offstyle="danger" data-on="Yes" data-off="No"></td>'+
                   

                      '<td data-th="Product" >'+
                      '<a><label><a href="'+url+'" target="_blank">'+proObj[i]["name"]+'</a></label></a>'+
                     
                      '<span class="error_message" id="product_id_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Cost" >'+
                      '<input type="text" id="cost'+i+'" name="cost[]"  value="'+proObj[i]["unit_price"]+'" class="form-control" placeholder="Cost"  onkeyup="change_cost('+i+')">'+
                      '<span class="error_message" id="cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Transp. Cost" >'+
                      '<input class="percentage" type="text" id="trans_cost'+i+'" name="trans_cost[]" value="'+trans_cost+'" class="form-control" placeholder="Transportation Cost" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="trans_cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Customs Cost" >'+
                      '<input class="percentage" type="text" id="customs_cost'+i+'" name="customs_cost[]" value="'+customs_cost+'" class="form-control" placeholder="Customs Cost"  onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="customs_cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Other Cost" >'+
                      '<input class="percentage" type="text" id="other_cost'+i+'" name="other_cost[]" value="'+other_cost+'" class="form-control" placeholder="Other Cost"  onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="other_cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Total Cost" >'+
                      '<input type="text" id="total_cost'+i+'" readonly name="total_cost[]" value="'+tot_price+'" class="form-control" placeholder="Total Cost">'+
                      '<span class="error_message" id="total_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Profit" >'+
                      '<input class="percentage" type="text" id="profit'+i+'" name="profit[]" value="'+profit+'" class="form-control" placeholder="Profit" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="profit_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Proposed MSP" >'+
                      '<input type="text" id="pro_msp'+i+'" name="pro_msp[]" readonly value="'+propse_val.toFixed(2)+'" class="form-control" placeholder="Proposed MSP">'+
                      '<span class="error_message" id="pro_msp_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Quote" >'+
                      '<input class="percentage" type="text" id="quote'+i+'" name="quote[]" value="'+quote+'" class="form-control" placeholder="Quote" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="quote_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Quote Price" >'+
                      '<input type="text" id="pro_quote_price'+i+'" readonly name="pro_quote_price[]" value="'+quote_tot.toFixed(2)+'" class="form-control" placeholder="Proposed Quote Price">'+
                      '<span class="error_message" id="pro_quote_price_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th=" Tax Per." >'+
                      '<input type="text" id="tax_per'+i+'"  name="tax_per[]" value="'+proObj[i]["tax_per"]+'" class="percentage" placeholder="Tax Per."><span class="percentage">%</span>'+
                      '<span class="error_message" id="tax_per_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th=" HSN Code" >'+
                      '<input type="text" id="hsn_code'+i+'"  name="hsn_code[]" value="'+proObj[i]["hsn_code"]+'" class="form-control" placeholder="HSN Code">'+
                      '<span class="error_message" id="hsn_code_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Per. Online Price" >'+
                      '<input class="percentage" type="text" id="percent_online'+i+'" name="percent_online[]" value="'+online+'" class="form-control" placeholder="Percentage of Online Price" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="percent_online_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Online Price Site" >'+
                      '<input type="text" id="online_price'+i+'" name="online_price[]" value="'+online_tot.toFixed(2)+'" class="form-control" placeholder="Online Price Site">'+
                      '<span class="error_message" id="online_price_message" style="display: none">Field is required</span>'+
                   
                        '<span class="rq-box"><input type="checkbox" attr-id="'+i+'" class="form-check-input req_quote" id="req_quote'+i+'" onclick="handleClick('+i+',this)">Request Quote</span>'+
                       
                   
                      '</td>'+

                      '<td data-th="Discount" >'+
                      '<input type="text" id="discount'+i+'" name="discount[]" value="'+discount+'" class="percentage" placeholder="Discount" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="discount_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Discount Price" >'+
                      '<input class="percentage" type="text" id="discount_price'+i+'" readonly name="discount_price[]" value="'+discount_tot.toFixed(2)+'" class="form-control" placeholder="Discount" >'+
                      '<span class="error_message" id="discount_price_message" style="display: none">Field is required</span>'+
                      '</td>'+
                      '<td data-th="Incentive" >'+
                      '<input class="percentage" class="percentage" type="text" id="incentive'+i+'"  name="incentive[]" value="'+proObj[i]["incentive"]+'" class="form-control" placeholder="Incentive" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="incentive_message" style="display: none">Field is required</span>'+
                      '</td>'+
                      '<td data-th="Incentive Amount" >'+
                      '<input type="text" id="incentive_amount'+i+'"  name="incentive_amount[]" value="'+incentive_amount+'" class="form-control" placeholder="Incentive" readonly>'+
                      '<span class="error_message" id="incentive_amount_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Diff. MSP from Prev" >'+
                      '<input type="text" id="mspprev'+i+'" name="mspprev[]" value="" class="form-control" placeholder="Difference in MSP from Previous" >'+
                      '<span class="error_message" id="mspprev_message" style="display: none">Field is required</span>'+
                      '<a onclick="show_prv_msp('+proObj[i]["id"]+')">Prev MSP</a>'+
                      '<input type="hidden" id="product_id'+i+'" name="product_id[]" value="'+proObj[i]["id"]+'" class="form-control" placeholder="Product"  >  '+
                      '</td>'+

                     
                      '</tr>';
           

            //states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';

            }

            $("#form_product").html(htmls);
            $('.togglebutton').bootstrapToggle({
              on: 'Yes',
              off: 'No'
            });
        }

      });




});


       var oTable = $('#cmsTable').DataTable({
        });

      });


    /*$('.req_quote').change(function() {alert()
      var row_val=$(this).attr('attr-id');
      alert(row_val)<label><a href="'+url+'" target="_blank">'+proObj[i]["name"]+'</a></label>
        if(this.checked) {
          
          $("#label_quote").html("Request Quote");
          $(".hide_quote").hide();
        }
        else{
          $("#label_quote").html("Quote");
          $(".hide_quote").show();
        }
       // $('#quote_req').val(this.checked);        
    });*/

function handleClick(row_no,cb) {
  if(cb.checked) {
    $('#online_price'+row_no).prop('readonly', true);
    $('#discount'+row_no).prop('readonly', true);
    $('#online_price'+row_no).css('border-color', 'red');
    $('#discount'+row_no).css('border-color', 'red');
    $('#discount_price'+row_no).css('border-color', 'red');
  }else{
    $('#online_price'+row_no).prop('readonly', false);
    $('#discount'+row_no).prop('readonly', false);


     $('#online_price'+row_no).css('border-color', '');
     $('#discount'+row_no).css('border-color', '');
     $('#discount_price'+row_no).css('border-color', '');
  }
 // display("Clicked, new value = " + cb.checked);
}


$(document).ready(function() {


    //set initial state.

     

});

 function change_brand(){

 
var brand_id=$("#brand_id").val();
$("#category_type_id").val('');
$("#category_id").html('<option value="">Select Care Area</option>');
var category_type_id=$("#category_type_id").val();
var category_id=$("#category_id").val();
var search_word=$("#search_word").val();

$(".brand_gif").show();
var url = APP_URL+'/staff/sort_brand_use_category_type';
$.ajax({
    url: url,
   // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    //dataType: "json",
    data: {
      brand_id:brand_id
    },
    success: function( data ) {
      //alert( data.length );
     
$(".brand_gif").hide();
      var proObj = JSON.parse(data);
    //  console.log(proObj)
        var  htmls=' ';
        htmls +="<option value=''>Select Category </option>";
  
 /* $.each(proObj, function(k, v) {
    //alert(k+'--'+v)
    htmls +="<option value='"+k+"'>"+v+"</option>";
  });*/
  for (var j = 0; j < proObj.length; j++) {
    htmls +="<option value='"+proObj[j]["id"]+"'>"+proObj[j]["name"]+"</option>";
  }


        /* for (var i = 0; i < proObj.length; i++) {
          htmls +="<option value='"+proObj[i]+"'>"+proObj[i]+"</option>";
             }*/
     
        $("#category_type_id").html(htmls);
   
    }
    });



var url = APP_URL+'/staff/change_brand_for_product';
$(".brand_gif").show();
 $.ajax({

        type: "POST",

        cache: false,

        url: url,

        data:{

          brand_id: brand_id,category_type_id:category_type_id,category_id:category_id,search_word:search_word,

        },

        success: function (data)

        {   
          $("#cmsTable1").show();
          var proObj = JSON.parse(data);

          states_val='';

          states_val +='<option value="">Select Product</option>';
            var htmls='';
          for (var i = 0; i < proObj.length; i++) {

             var unit_price=proObj[i]["unit_price"];
         
           var trans_cost=proObj[i]["trans_cost"]
           var customs_cost=proObj[i]["customs_cost"]
           var other_cost=proObj[i]["other_cost"]
           var profit=proObj[i]["profit"]
           
          var total_per=parseFloat(trans_cost)+parseFloat(customs_cost)+parseFloat(other_cost);

          var total_peramount= unit_price*total_per/100;
           var tot_price= parseFloat(unit_price)+parseFloat(total_peramount);

           var tot_price=tot_price.toFixed(2);

           var propse_val=tot_price*profit/100;
           var propse_val=propse_val.toFixed(2);
           
           var propse_val=parseFloat(tot_price)+parseFloat(propse_val);

                   
           var quote=proObj[i]["quote"];
            //  var quote_per=propse_val*quote/100;
            //  var quote_tot=parseFloat(quote_per)+parseFloat(propse_val);
            var quote_tot=proObj[i]["quote_price"];
  
              var online=proObj[i]["percent_online"];
            //  var online_per=quote_tot*online/100;
            //  var online_tot=parseFloat(online_per)+parseFloat(quote_tot);
            var online_tot=proObj[i]["online_price"];
  
              var discount=proObj[i]["discount"];
            //  var discount_per=online_tot*discount/100;
            //  var discount_tot=parseFloat(online_tot)-parseFloat(discount_per);
            var discount_tot=proObj[i]["discount_price"];
           

           var incentive = proObj[i]["incentive"];
           var prop_total = parseFloat(propse_val)-parseFloat(tot_price);
           var incentive_amount=incentive*prop_total/100;
          // var discount_tot=parseFloat(online_tot)-parseFloat(discount_per);
          var product_id = proObj[i]["id"];
          var url = '{{ route("staff.products.edit", ":id") }}';
              url = url.replace(':id', product_id);


//alert(product_id);
            htmls +=' <tr>'+
                      '<td data-th="Show in Page"><input type="checkbox" '+proObj[i]['show_inPage']+' class="togglebutton" onchange="productstatuschange(this,'+product_id+')" data-toggle="toggle" data-size="mini" data-onstyle="success" data-offstyle="danger" data-on="Yes" data-off="No"></td>'+
            //{{ route('staff.products.edit','+product_id+') }}
            
                      '<td data-th="Product" >'+
                      '<label><a href="'+url+'" target="_blank">'+proObj[i]["name"]+'</a></label>'+
                     
                      '<span class="error_message" id="product_id_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Cost" >'+
                      '<input type="text" id="cost'+i+'" name="cost[]"  value="'+proObj[i]["unit_price"]+'" class="form-control" placeholder="Cost"  onkeyup="change_cost('+i+')">'+
                      '<span class="error_message" id="cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Transp. Cost" >'+
                      '<input class="percentage" type="text" id="trans_cost'+i+'" name="trans_cost[]" value="'+trans_cost+'" class="form-control" placeholder="Transportation Cost" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="trans_cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Customs Cost" >'+
                      '<input class="percentage" type="text" id="customs_cost'+i+'" name="customs_cost[]" value="'+customs_cost+'" class="form-control" placeholder="Customs Cost"  onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="customs_cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Other Cost" >'+
                      '<input class="percentage" type="text" id="other_cost'+i+'" name="other_cost[]" value="'+other_cost+'" class="form-control" placeholder="Other Cost"  onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="other_cost_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Total Cost" >'+
                      '<input type="text" id="total_cost'+i+'" readonly name="total_cost[]" value="'+tot_price+'" class="form-control" placeholder="Total Cost">'+
                      '<span class="error_message" id="total_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Profit" >'+
                      '<input class="percentage" type="text" id="profit'+i+'" name="profit[]" value="'+profit+'" class="form-control" placeholder="Profit" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="profit_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Proposed MSP" >'+
                      '<input type="text" id="pro_msp'+i+'" name="pro_msp[]" readonly value="'+propse_val.toFixed(2)+'" class="form-control" placeholder="Proposed MSP">'+
                      '<span class="error_message" id="pro_msp_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Quote" >'+
                      '<input class="percentage" type="text" id="quote'+i+'" name="quote[]" value="'+quote+'" class="form-control" placeholder="Quote" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="quote_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Quote Price" >'+
                      '<input type="text" id="pro_quote_price'+i+'" readonly name="pro_quote_price[]" value="'+quote_tot.toFixed(2)+'" class="form-control" placeholder="Proposed Quote Price">'+
                      '<span class="error_message" id="pro_quote_price_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th=" Tax Per." >'+
                      '<input type="text" id="tax_per'+i+'"  name="tax_per[]" value="'+proObj[i]["tax_per"]+'" class="percentage" placeholder="Tax Per."><span class="percentage">%</span>'+
                      '<span class="error_message" id="tax_per_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th=" HSN Code" >'+
                      '<input type="text" id="hsn_code'+i+'"  name="hsn_code[]" value="'+proObj[i]["hsn_code"]+'" class="form-control" placeholder="HSN Code">'+
                      '<span class="error_message" id="hsn_code_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Per. Online Price" >'+
                      '<input class="percentage" type="text" id="percent_online'+i+'" name="percent_online[]" value="'+online+'" class="form-control" placeholder="Percentage of Online Price" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="percent_online_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Online Price Site" >'+
                      '<input type="text" id="online_price'+i+'" name="online_price[]" value="'+online_tot.toFixed(2)+'" class="form-control sml-textbox" placeholder="Online Price Site">'+
                      '<span class="error_message" id="online_price_message" style="display: none">Field is required</span>'+
                   
                        '<span class="rq-box"><input type="checkbox" attr-id="'+i+'" class="form-check-input req_quote" id="req_quote'+i+'" onclick="handleClick('+i+',this)">Request Quote</span>'+
                       
                   
                      '</td>'+

                      '<td data-th="Discount" >'+
                      '<input class="percentage" type="text" id="discount'+i+'" name="discount[]" value="'+discount+'" class="percentage" placeholder="Discount" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="discount_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Discount Price" >'+
                      '<input type="text" id="discount_price'+i+'" readonly name="discount_price[]" value="'+discount_tot.toFixed(2)+'" class="form-control" placeholder="Discount" >'+
                      '<span class="error_message" id="discount_price_message" style="display: none">Field is required</span>'+
                      '</td>'+
                      '<td data-th="Incentive" >'+
                      '<input class="percentage" class="percentage" type="text" id="incentive'+i+'"  name="incentive[]" value="'+proObj[i]["incentive"]+'" class="form-control" placeholder="Incentive" onkeyup="change_cost('+i+')"><span class="percentage">%</span>'+
                      '<span class="error_message" id="incentive_message" style="display: none">Field is required</span>'+
                      '</td>'+
                      '<td data-th="Incentive Amount" >'+
                      '<input type="text" id="incentive_amount'+i+'"  name="incentive_amount[]" value="'+incentive_amount+'" class="form-control" placeholder="Incentive" readonly>'+
                      '<span class="error_message" id="incentive_amount_message" style="display: none">Field is required</span>'+
                      '</td>'+

                      '<td data-th="Diff. MSP from Prev" >'+
                      '<input style="display:none" type="text" id="mspprev'+i+'" name="mspprev[]" value="" class="form-control" placeholder="Difference in MSP from Previous" >'+
                      '<span class="error_message" id="mspprev_message" style="display: none">Field is required</span>'+
                      '<a onclick="show_prv_msp('+proObj[i]["id"]+')">Prev MSP</a>'+
                      '<input type="hidden" id="product_id'+i+'" name="product_id[]" value="'+proObj[i]["id"]+'" class="form-control" placeholder="Product"  >  '+
                      '</td>'+

                     
                      '</tr>';
           

            //states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';

            }

            $("#form_product").html(htmls);
            $(".brand_gif").hide();
            $(".box-footer").show();
            $('.togglebutton').bootstrapToggle({
              on: 'Yes',
              off: 'No'
            });
        }

      });

  
/*
var url = APP_URL+'/staff/change_brand_for_msplisting';
$.ajax({
       type: "POST",
       cache: false,
       url: url,
       data:{
       brand_id: brand_id,
       },
       success: function (data)
       {    
         var proObj = JSON.parse(data);
         var htmls='';
         for (var i = 0; i < proObj.length; i++) {

            htmls +='<tr>'+
                    '<td colspan="15">'+proObj[i]["created_at"]+' MSP</td>'+
                    
                  '</tr>';

          htmls +='<tr>'+
                    '<td>'+proObj[i]["pro_msp"]+'</td>'+
                   
                    '<td>'+proObj[i]["brand_name"]+'</td>'+
                    '<td>'+proObj[i]["product_name"]+'</td>'+
                    '<td>'+proObj[i]["cost"]+'</td>'+
                    '<td>'+proObj[i]["trans_cost"]+'</td>'+
                    '<td>'+proObj[i]["customs_cost"]+'</td>'+
                    '<td>'+proObj[i]["other_cost"]+'</td>'+
                    '<td>'+proObj[i]["total_cost"]+'</td>'+
                    '<td>'+proObj[i]["profit"]+'</td>'+
                    ' <td>'+proObj[i]["quote"]+'</td>'+
                    '<td>'+proObj[i]["pro_quote_price"]+'</td>'+
                    '<td>'+proObj[i]["percent_online"]+'</td>'+
                    '<td>'+proObj[i]["online_price"]+'</td>'+
                    '<td>'+proObj[i]["discount"]+'</td>'+
                    '<td>'+proObj[i]["discount_price"]+'</td>'+
                  '</tr>';
         }
         $("#dis_resp_product").html(htmls);
          
       }
     });*/
     
}



 function change_product(){
var product_id=$("#product_id").val();
var url = APP_URL+'/staff/change_particular_product_details';
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
           //proObj[i]["unit_price"]
           var unit_price=proObj[0]["unit_price"];
           $("#cost").val(unit_price);
           var trans_cost=$("#trans_cost").val();
           var customs_cost=$("#customs_cost").val();
           var other_cost=$("#other_cost").val();
           var profit=$("#profit").val();
           
          var total_per=parseFloat(trans_cost)+parseFloat(customs_cost)+parseFloat(other_cost);

          var total_peramount= unit_price*total_per/100;
           var tot_price= parseFloat(unit_price)+parseFloat(total_peramount);

           var tot_price=tot_price.toFixed(2);

           var propse_val=tot_price*profit/100;
           var propse_val=propse_val.toFixed(2);
           
           var propse_val=parseFloat(tot_price)+parseFloat(propse_val);

           
           var quote=$("#quote").val();
           var quote_per=propse_val*quote/100;
           var quote_tot=parseFloat(quote_per)+parseFloat(propse_val);

            var online=$("#percent_online").val();
           var online_per=quote_tot*online/100;
           var online_tot=parseFloat(online_per)+parseFloat(quote_tot);


           var discount=$("#discount").val();
           var discount_per=online_tot*discount/100;
           var discount_tot=parseFloat(online_tot)-parseFloat(discount_per);

          $("#total_cost").val(tot_price);
         $("#pro_msp").val(propse_val.toFixed(2));
         $("#pro_quote_price").val(quote_tot.toFixed(2));
         
         $("#online_price").val(online_tot.toFixed(2));
         $("#discount_price").val(discount_tot.toFixed(2));
         
        }
      });


    /*  
var url = APP_URL+'/staff/change_product_for_msplisting';
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
         var htmls='';
         for (var i = 0; i < proObj.length; i++) {

             htmls +='<tr>'+
                    '<td colspan="15">'+proObj[i]["created_at"]+' MSP</td>'+
                    
                  '</tr>';

          htmls +='<tr>'+
                    '<td>'+proObj[i]["pro_msp"]+'</td>'+
                 
                    '<td>'+proObj[i]["brand_name"]+'</td>'+
                    '<td>'+proObj[i]["product_name"]+'</td>'+
                    '<td>'+proObj[i]["cost"]+'</td>'+
                    '<td>'+proObj[i]["trans_cost"]+'</td>'+
                    '<td>'+proObj[i]["customs_cost"]+'</td>'+
                    '<td>'+proObj[i]["other_cost"]+'</td>'+
                    '<td>'+proObj[i]["total_cost"]+'</td>'+
                    '<td>'+proObj[i]["profit"]+'</td>'+
                    ' <td>'+proObj[i]["quote"]+'</td>'+
                    '<td>'+proObj[i]["pro_quote_price"]+'</td>'+
                    '<td>'+proObj[i]["percent_online"]+'</td>'+
                    '<td>'+proObj[i]["online_price"]+'</td>'+
                    '<td>'+proObj[i]["discount"]+'</td>'+
                    '<td>'+proObj[i]["discount_price"]+'</td>'+
                  '</tr>';
         }
         $("#dis_resp_product").html(htmls);
          
       }
     });*/

}

function change_cost(row_no)
{

 var unit_price= $("#cost"+row_no).val();
          
           var trans_cost=$("#trans_cost"+row_no).val();
           var customs_cost=$("#customs_cost"+row_no).val();
           var other_cost=$("#other_cost"+row_no).val();
           var profit=$("#profit"+row_no).val();
           var incentive=$("#incentive"+row_no).val();
           
          var total_per=parseFloat(trans_cost)+parseFloat(customs_cost)+parseFloat(other_cost);

          var total_peramount= unit_price*total_per/100;
           var tot_price= parseFloat(unit_price)+parseFloat(total_peramount);

           var tot_price=tot_price.toFixed(2);

           var propse_val=tot_price*profit/100;
           var propse_val=propse_val.toFixed(2);
           
           var propse_val=parseFloat(tot_price)+parseFloat(propse_val);

           var quote=$("#quote"+row_no).val();
          //  var quote_per=propse_val*quote/100;
          //  var quote_tot=parseFloat(quote_per)+parseFloat(propse_val);
           var quote_per=propse_val*quote/100;
           var quote_tot=parseFloat(quote_per)+parseFloat(propse_val);

            var online=$("#percent_online"+row_no).val();
          //  var online_per=quote_tot*online/100;
          //  var online_tot=parseFloat(online_per)+parseFloat(quote_tot);
           var online_per=propse_val*online/100;
           var online_tot=parseFloat(online_per)+parseFloat(propse_val);

           var discount=$("#discount"+row_no).val();
           var discount_per=online_tot*discount/100;
           var discount_tot=parseFloat(online_tot)-parseFloat(discount_per);

           var incentive=$("#incentive"+row_no).val(); 
           //var incentive=proObj[i]["incentive"];
           var prop_total = parseFloat(propse_val)-parseFloat(tot_price);
           var incentive_amount=incentive*prop_total/100;
           
         $("#total_cost"+row_no).val(tot_price);
         $("#incentive_amount"+row_no).val(incentive_amount);
         $("#pro_msp"+row_no).val(propse_val.toFixed(2));
         $("#pro_quote_price"+row_no).val(quote_tot.toFixed(2));
         $("#online_price"+row_no).val(online_tot.toFixed(2));
         $("#discount_price"+row_no).val(discount_tot.toFixed(2));
         
         

var product_id= $("#product_id"+row_no).val();
var proposed_val=propse_val.toFixed(2);
var url = APP_URL+'/staff/get_last_product_price_msp';
$.ajax({
       type: "POST",
       cache: false,
       url: url,
       data:{
        product_id: product_id,proposed_val:proposed_val
       },
       success: function (data)
       {    
        //alert(data)
        if(data!='')
        {
          $("#mspprev"+row_no).val(data);
        }
        
       }
});


}




  

  </script>

@endsection

